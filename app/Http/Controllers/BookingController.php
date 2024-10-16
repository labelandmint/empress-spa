<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Slot;
use App\Models\Setting;
use App\Models\Service;
use App\Mail\Booking as EmailBooking;
use DateTime;

class BookingController extends Controller
{
    //
    public function index(){
        $bookings = Booking::join('services','services.id','bookings.service_id')
        ->join('slots','slots.id','bookings.slot_id')
        ->select('bookings.*','services.description','slots.start_time','slots.end_time')
        ->where('bookings.member_id',Auth::user()->id)
        ->orderBy('bookings.booking_date')
        ->get();

        $bookingDates = $bookings->pluck('booking_date');
        return view('bookings.upcoming',compact('bookings','bookingDates'));
    }

    // public function store(Request $request)
    // {
    //     $data = $request->all();
    //     $data['member_id'] = Auth::user()->id;

    //     if ($request->id) {
    //         // Update existing booking
    //         $booking = Booking::find($request->id);
    //         $booking->update($data);
            
    //         return redirect()->back()->with('status', 'Your booking has been updated successfully.');
    //     } else {
    //         // Create a new booking
    //         Booking::create($data);
            
    //         return redirect()->back()->with('status', 'Your service booking has been successfully completed.')->with('modal', true);
    //     }
    // }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['member_id'] = Auth::user()->id;

        // Fetch the selected slot
        $slot = Slot::find($request->slot_id);

        // Check if the slot exists
        if (!$slot) {
            return redirect()->back()->withErrors(['error' => 'Selected slot does not exist.']);
        }

        // Validate if the user has already booked the same service, slot, and date
        $existingBooking = Booking::where('member_id', $data['member_id'])
                                   ->where('service_id', $slot->service_id)
                                   ->where('slot_id', $slot->id)
                                   ->where('booking_date', $data['booking_date'])
                                   ->first();

        if ($existingBooking) {
            return redirect()->back()->withErrors(['error' => 'You have already scheduled this service and time slot on the selected date.']);
        }

        // Count the number of existing bookings for the selected slot
        $currentBookings = Booking::where('slot_id', $slot->id)
                                  ->where('booking_date', $data['booking_date'])
                                  ->count();

        // Check if the slot's capacity is already full
        if ($currentBookings >= $slot->capacity) {
            return redirect()->back()->withErrors(['error' => 'The selected slot is fully booked. Please choose another slot.']);
        }

        // Check if all slots for the service are booked
        $bookedSlotsCount = Booking::where('booking_date', $data['booking_date'])
                                    ->where('service_id', $slot->service_id)
                                    ->groupBy('slot_id')
                                    ->havingRaw('COUNT(*) >= (SELECT capacity FROM slots WHERE id = slot_id)')
                                    ->count();

        // Fetch total slots for the service
        $totalSlots = Slot::where('service_id', $slot->service_id)->count();

        // If all slots are booked, return an error
        if ($bookedSlotsCount >= $totalSlots) {
            return redirect()->back()->withErrors(['error' => 'All slots for the selected service on this date are fully booked.']);
        }

        // Proceed with booking if capacity is not reached
        if ($request->id) {
            // Update existing booking
            $booking = Booking::find($request->id);
            $booking->update($data);

            return redirect()->back()->with('status', 'Your booking has been updated successfully.');
        } else {
            // Create a new booking
            Booking::create($data);

            // Retrieve email settings
            $setting = Setting::first();
            $service = Service::find($slot->service_id);

            $name = Auth::user()->f_name;
            $logo = $setting->email_logo;
            $service = $service->title;
            $booking_date = $request->booking_date;
            $startTime = new DateTime($slot->start_time);
            $endTime = new DateTime($slot->end_time);

            // Format the time with AM/PM
            $formattedStartTime = $startTime->format('g:i A');
            $formattedEndTime = $endTime->format('g:i A');

            // Concatenate the formatted times
            $time = $formattedStartTime . ' - ' . $formattedEndTime;
            $website_address = $setting->business_website_address;
            $phone = $setting->business_phone_number;
            $email = $setting->business_email_address;
            $location = $setting->business_address1 . ',' . $setting->business_address2 . ',' . $setting->city . ',' . $setting->state . ',' . $setting->postcode;

            // Send the booking confirmation email
            Mail::to(Auth::user()->email)->send(new EmailBooking($name, $logo, $service, $booking_date, $time, $website_address, $phone, $email, $location));
            
            return redirect()->back()->with('status', 'Your service booking has been successfully completed.')->with('modal', true);
        }
    }


    public function destroy(Request $request)
    {
        $booking = Booking::find($request->booking_id);
        $booking->forceDelete();
        return redirect()->back();
    }




}

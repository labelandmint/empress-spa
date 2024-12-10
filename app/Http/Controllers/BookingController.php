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
use Carbon\Carbon;
use DB;

class BookingController extends Controller
{
    //
    // public function index(){
    //     $bookings = Booking::join('services','services.id','bookings.service_id')
    //     ->select('bookings.*','services.description')
    //     ->where('bookings.member_id',Auth::user()->id)
    //     ->orderBy('bookings.booking_date')
    //     ->get();

    //     $title = "Bookings";
    //     $bookingDates = $bookings->pluck('booking_date');
    //     return view('bookings.upcoming',compact('title','bookings','bookingDates'));
    // }

    public function index()
    {

        $userId = Auth::user()->id;

        // $bookings = Booking::join('services', 'services.id', '=', 'bookings.service_id')
        //     ->select('bookings.*', 'services.description')
        //     ->where('bookings.member_id', Auth::user()->id)
        //     ->whereMonth('bookings.booking_date', $currentMonth)
        //     ->whereYear('bookings.booking_date', $currentYear)
        //     ->orderBy('bookings.booking_date')
        //     ->get();

        $title = "Bookings";
        // $bookingDates = $bookings->pluck('booking_date');

        return view('bookings.upcoming', compact('title', 'userId',));
    }

    public function getMemberBooking(Request $request, $id)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $query = Booking::join('services', 'services.id', '=', 'bookings.service_id')
            ->join('users', 'users.id', '=', 'bookings.member_id')
            ->select('bookings.*', 'services.description', DB::raw("CONCAT(users.f_name, ' ', users.l_name) as name"))
            ->where('bookings.member_id', $id)
            ->orderBy('bookings.booking_date');


        if ($request->has('month') && !empty($request->get('month')) &&  $request->get('date') != "month" && $request->has('year') && !empty($request->get('year')) &&  $request->get('date') != "year") {
            $query->whereMonth('bookings.booking_date', $request->get('month'))
                ->whereYear('bookings.booking_date', $request->get('year'));
        } else {
            $query->whereMonth('bookings.booking_date', $currentMonth)
                ->whereYear('bookings.booking_date', $currentYear);
        }

        // Execute the query and get the results
        $bookings = $query->get();


        // Pluck the booking dates for use in the response
        $bookingDates = $bookings->pluck('booking_date');

        // Return the response as JSON
        return response()->json([
            'title' => 'Bookings',
            'bookings' => $bookings,
            'bookingDates' => $bookingDates
        ]);
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

        $slot = Slot::find($request->slot_id);

        if (!$slot) {
            return redirect()->back()->withErrors(['error' => 'Selected slot does not exist.']);
        }

        $existingBooking = Booking::where('member_id', $data['member_id'])
            ->where('service_id', $slot->service_id)
            ->where('slot_id', $slot->id)
            ->where('booking_date', $data['booking_date'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->withErrors(['error' => 'You have already scheduled this service and time slot on the selected date.']);
        }

        $currentBookings = Booking::where('slot_id', $slot->id)
            ->where('booking_date', $data['booking_date'])
            ->count();

        if ($currentBookings >= $slot->capacity) {
            return redirect()->back()->withErrors(['error' => 'The selected slot is fully booked. Please choose another slot.']);
        }

        $bookedSlotsCount = Booking::where('booking_date', $data['booking_date'])
            ->where('service_id', $slot->service_id)
            ->groupBy('slot_id')
            ->havingRaw('COUNT(*) >= (SELECT capacity FROM slots WHERE id = slot_id)')
            ->count();

        $totalSlots = Slot::where('service_id', $slot->service_id)->count();

        if ($bookedSlotsCount >= $totalSlots) {
            return redirect()->back()->withErrors(['error' => 'All slots for the selected service on this date are fully booked.']);
        }

        if ($request->id) {
            $booking = Booking::find($request->id);
            $booking->update($data);

            return redirect()->back()->with('status', 'Your booking has been updated successfully.');
        } else {
            $data['booking_start_time'] = $slot->start_time;
            $data['booking_end_time'] = $slot->end_time;
            Booking::create($data);

            $setting = Setting::first();
            $service = Service::find($slot->service_id);

            $name = Auth::user()->f_name;
            $logo = $setting->email_logo;
            $service = $service->title;
            $booking_date = $request->booking_date;
            $startTime = new DateTime($slot->start_time);
            $endTime = new DateTime($slot->end_time);

            $formattedStartTime = $startTime->format('g:i A');
            $formattedEndTime = $endTime->format('g:i A');

            $time = $formattedStartTime . ' - ' . $formattedEndTime;
            $website_address = $setting->business_website_address;
            $phone = $setting->business_phone_number;
            $email = $setting->business_email_address;
            $location = $setting->business_address1 . ',' . $setting->business_address2 . ',' . $setting->city . ',' . $setting->state . ',' . $setting->postcode;

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

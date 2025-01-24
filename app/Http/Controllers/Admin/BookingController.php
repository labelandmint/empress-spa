<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Slot;
use Carbon\Carbon;
use DB;


class BookingController extends Controller
{
    //
    public function index()
    {
        return view('bookings.upcoming');
    }

    public function getById(Request $request, $id)
    {
        $booking = Booking::find($id);
        $slots = [];
        if ($booking) {
            $slots = Slot::where('service_id', $booking->service_id)->get();
        }
        return response()->json([
            'success' => true,
            'booking' => $booking,
            'slots' => $slots,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $booking = Booking::find($request->id);
        $booking->update($data);
        return redirect()->back()->with('status', 'The booking has been updated successfully.')->with('modal', true);
    }

    public function memberBooking(Request $request, $id = null)
    {
        $services = Category::with('services')->get();
        $title = "Bookings";
        $userId = $id;

        return view('admin.bookings.upcoming', compact('title', 'userId', 'services'));
    }
    public function getMemberBooking(Request $request, $id = null)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        // Start the query builder
        $query = Booking::join('services', 'services.id', '=', 'bookings.service_id')
            ->join('users', 'users.id', '=', 'bookings.member_id')
            ->select('bookings.*', 'services.description', DB::raw("CONCAT(users.f_name, ' ', users.l_name) as name"))
            ->orderBy('bookings.booking_date');

        if ($id && $id !== null && $id != "null" && $id != "undefined") {
            $query->where('bookings.member_id', $id);
        }

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
}

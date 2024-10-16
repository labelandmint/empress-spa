<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
Use App\Models\Slot;
Use App\Models\Booking;
use Carbon\Carbon;

class ServiceController extends Controller
{
    //
    public function index() {
        $categories = Category::with('services')->get();
        // return $categories;
        return view('services.index',compact('categories'));
    }


    // public function slots(Request $request)
    // {
    //     $serviceId = $request->service_id;
    //     $date = Carbon::createFromFormat('F jS, Y', $request->date)->format('Y-m-d');
    //     // return $date;
    //     // Check for existing bookings for the specified service ID and date
    //     $bookedSlots = Booking::where('service_id', $serviceId)
    //         ->whereDate('booking_date', $date)
    //         ->pluck('slot_id'); // Get IDs of booked slots

    //     // Fetch available slots for the specified service ID, excluding booked slots
    //     $availableSlots = Slot::where('service_id', $serviceId)
    //         ->whereNotIn('id', $bookedSlots) // Filter out booked slots
    //         ->get();

    //     // Return the available slots to the view
    //     return response()->json([
    //         'slots' => $availableSlots,
    //     ]);
    // }


    public function slots(Request $request)
    {
        $serviceId = $request->service_id;
        $date = Carbon::createFromFormat('F jS, Y', $request->date)->format('Y-m-d');

        // Get booked slots with their booking count
        $bookedSlots = Booking::where('service_id', $serviceId)
            ->whereDate('booking_date', $date)
            ->select('slot_id', \DB::raw('count(*) as bookings_count'))
            ->groupBy('slot_id')
            ->pluck('bookings_count', 'slot_id'); // ['slot_id' => bookings_count]

        // Get all slots for the service
        $availableSlots = Slot::where('service_id', $serviceId)->get();

        // Filter the slots to show free and partially booked ones
        $filteredSlots = $availableSlots->filter(function ($slot) use ($bookedSlots) {
            $bookedCount = $bookedSlots->get($slot->id, 0); // Get the number of bookings for this slot, default to 0
            return $bookedCount < $slot->capacity; // Show slot if not fully booked
        });

        // Check if there are no available slots
        if ($filteredSlots->isEmpty()) {
            return response()->json([
                'error' => 'All slots are fully booked for the selected date and service.'
            ], 404); // HTTP 404 Not Found
        }

        // Return the available (free and partially booked) slots
        return response()->json([
            'slots' => $filteredSlots->values()->toArray(),
        ]);
    }


}

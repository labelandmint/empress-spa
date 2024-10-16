<?php

namespace App\Http\Controllers\Admin;
Use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Slot;


class BookingController extends Controller
{
    //
    public function index(){
        return view('bookings.upcoming');
    }

    public function getById(Request $request, $id)
    {
        $booking = Booking::find($id);
        $slots = [];
        if($booking){
            $slots = Slot::where('service_id',$booking->service_id)->get();
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
}

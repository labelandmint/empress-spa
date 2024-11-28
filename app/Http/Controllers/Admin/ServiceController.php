<?php

namespace App\Http\Controllers\Admin;
Use App\Http\Controllers\Controller;

Use App\Models\Service;
Use App\Models\Booking;
Use App\Models\Category;
Use App\Models\Slot;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //
    public function index() {
        $services = Service::where('status',1)->get();
        $categories = Category::get();
        $title='Services';

        // return $categories;
        return view('admin.services.index' ,compact('title','services','categories'));
    }

    public function store(Request $request)
    {
        // Prepare data for insertion
        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $data['photo'] = $this->uploadImage($file, $fileName);
        }

           // Check if category exists, if not create it
        if (isset($data['category_id'])) {
            // Check for existing category by name (case-insensitive)
            $category = Category::find($data['category_id']);
            if(!$category){
                $category = Category::create([
                    'name'=>$data['category_id']
                ]);
                $data['category_id'] = $category->id;
            }
        }

        // Update or Create Service
        if($request->id) {
            $service = Service::find($request->id);
            if(!$request->hasFile('photo') && (!isset($request->photo_url) || $request->photo_url == '')) {
                $data['photo'] = null;
            }
            $service->update($data);
            $message = 'Service updated successfully.';
        } else {
            $service = Service::create($data);
            $message = 'Service added successfully.';
        }

        // Handle Service Availability slots
        $this->createServiceAvailability($service->id, $data['session_timeframe'], $data['blockout_time'], $data['session_capacity']);

        return redirect()->back()->with('success', $message);
    }

    private function createServiceAvailability($serviceId, $sessionTimeframe, $blockoutTime, $capacity)
    {
        // Step 1: Delete existing slots for the service
        Slot::where('service_id', $serviceId)->delete();

        $startTime = Carbon::createFromTime(10, 0); // Starting at 10 AM
        $endTime = Carbon::createFromTime(20, 0);  // Until 8 PM

        while ($startTime->lt($endTime)) {
            // Calculate end time for each session
            $sessionEndTime = $startTime->copy()->addMinutes($sessionTimeframe);

            $slot = [
                'service_id' => $serviceId,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $sessionEndTime->format('H:i:s'),
                'capacity' => $capacity,
            ];

            // Create the availability slot
            Slot::create($slot);
            // Add blockout time and prepare for next slot
            $startTime = $sessionEndTime->addMinutes($blockoutTime);
        }
    }

    private function uploadImage($file, $fileName)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        // Return the path where the file is stored
        return url('/') . '/public/images/'. $filename;
    }

    // In ProductController.php
    public function filter(Request $request) {
        $status = $request->input('status');

        $services = Service::where('status', $status)->get();

        // Return a partial view or JSON response
        return view('admin.services.partials.service_list', compact('services'))->render();
    }

    public function archive(Request $request,$id)
    {
        $Service = Service::find($id);
        // return $Service;
        if($Service->status == 1){
            $Service->status = 3;
            $Service->archived_at = now();
        }else{
            $Service->status = 1;
            $Service->archived_at = null;
        }

        $Service->save();
        return redirect()->back()->with('success','Services archived successfully.');
    }


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

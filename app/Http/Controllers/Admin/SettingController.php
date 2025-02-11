<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    //
    public function index()
    {

        $user_id = 1;
        $settings = Setting::first();
        $users = User::withTrashed()
        ->whereIn('user_role', [1, 3, 4])
        ->orderBy('id', 'desc')
        ->get();
        $title = 'Settings';

        return view('admin.settings.index', compact('title', 'settings', 'settings', 'users'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // Check if a file has been uploaded
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $url = $this->uploadImage($file);
                $data['logo'] = $url; // Save the URL or path in the data array
            }

            if ($request->hasFile('page_background_image')) {
                $file = $request->file('page_background_image');
                $url = $this->uploadImage($file);
                $data['page_background_image'] = $url; // Save the URL or path in the data array
            }

            if ($request->hasFile('email_logo')) {
                $file = $request->file('email_logo');
                $url = $this->uploadImage($file);
                $data['email_logo'] = $url; // Save the URL or path in the data array
            }

            if ($request->hasFile('email_background_image')) {
                $file = $request->file('email_background_image');
                $url = $this->uploadImage($file);
                $data['email_background_image'] = $url; // Save the URL or path in the data array
            }

            // Add user ID to the data array
            $data['user_id'] = Auth::guard('admin')->user()->id;

            if ($request->input('update_ratio_time') === 'true') {
                $data['ratio_update_time'] = Carbon::now();
            }

            // Check if ID exists to determine if we're updating or creating
            if ($request->id) {
                $setting = Setting::find($request->id);
                if ($setting) {
                    $setting->update($data);
                    return redirect()->back()->with('success', 'Settings updated successfully');
                } else {
                    return redirect()->back()->with('error', 'Setting not found');
                }
            } else {
                Setting::create($data);
                return redirect()->back()->with('success', 'Settings created successfully');
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            return $e->getMessage();
            // \Log::error('Error in store method: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Server Error: ' . $e->getMessage());
        }
    }

    private function uploadImage($file)
    {
        // Define the filename
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        // Store the file in the 'images' directory
        $file->storeAs('images', $fileName, 'public');

        // Return only the filename
        return $fileName;
    }



    // private function uploadImage($file, $fileName)
    // {
    //     $filename = time() . '.' . $file->getClientOriginalExtension();
    //     $file->move(public_path('images'), $filename);
    //     // Return the path where the file is stored
    //     return url('/') . '/public/images/'. $filename;

    // }

}

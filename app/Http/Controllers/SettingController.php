<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
class SettingController extends Controller
{
    //
    public function index(){
        $user_id = Auth::guard('admin')->user()->id;
        $settings = Setting::where('user_id',$user_id )->first();

        return view('admin.settings.index',compact('settings'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // Check if a file has been uploaded
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '.' . $file->getClientOriginalExtension(); // Generate a unique file name
                $url = $this->uploadImage($file, $fileName);
                $data['logo'] = $url; // Save the URL or path in the data array
            }

            if ($request->hasFile('page_background_image')) {
                $file = $request->file('page_background_image');
                $fileName = time() . '.' . $file->getClientOriginalExtension(); // Generate a unique file name
                $url = $this->uploadImage($file, $fileName);
                $data['page_background_image'] = $url; // Save the URL or path in the data array
            }

            if ($request->hasFile('email_logo')) {
                $file = $request->file('email_logo');
                $fileName = time() . '.' . $file->getClientOriginalExtension(); // Generate a unique file name
                $url = $this->uploadImage($file, $fileName);
                $data['email_logo'] = $url; // Save the URL or path in the data array
            }

            if ($request->hasFile('email_background_image')) {
                $file = $request->file('email_background_image');
                $fileName = time() . '.' . $file->getClientOriginalExtension(); // Generate a unique file name
                $url = $this->uploadImage($file, $fileName);
                $data['email_background_image'] = $url; // Save the URL or path in the data array
            }

            // Add user ID to the data array
            $data['user_id'] = Auth::guard('admin')->user()->id;

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

    private function uploadImage($file, $fileName)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        // Return the path where the file is stored
        return url('/') . '/public/images/'. $filename;
    }

}

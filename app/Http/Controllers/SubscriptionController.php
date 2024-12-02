<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Setting;
use App\Models\User;
use App\Mail\Cancel;

class SubscriptionController extends Controller
{
    //
    public function index(){
        return view('subscriptions.index');
    }

    
    public function pauseMembership(Request $request)
    {
        // return $request->all();
        // Retrieve the subscription based on the subscription ID
        $subscription = Subscription::find($request->subscription_id);
        
        // Retrieve the user based on the user ID
        $user = User::find($request->id);

        // Calculate the new end date based on pause days
        $pauseDays = $request->pause_days ?? 0;
        $currentEndDate = \Carbon\Carbon::parse($subscription->subscription_end);
        $newEndDate = $currentEndDate->addDays($pauseDays);

        // Update the subscription details
        $subscription->update([
            'reason_for_pausing' => $request->reason_for_pausing,
            'pause_days' => $pauseDays,
            'pause_date' => $request->pause_date,
            'subscription_end' => $newEndDate,
            'status' => 3, // Paused status
        ]);
        
        // Update the user's status to paused
        $user->status = 3;
        $user->save();

        return redirect()->back()->with('success', 'Membership updated successfully.');
    }

    public function resumeMembership(Request $request,$id)
    {
        $subscription = Subscription::find($id);
        if($subscription){
            // Update the user's status to paused
            $subscription->status = 1;
            $subscription->save();

            $user = User::find($subscription->user_id);
            $user->status = 1;
            $user->save();
        }       
        
        return redirect()->back()->with('success', 'Membership updated successfully.');
    }


    public function cancelSubscription(Request $request, $id)
    {
        // Find the subscription for the user
        $subscription = Subscription::where('user_id', $id)->first();

        if ($subscription) {
            // Cancel the subscription by updating its status
            $subscription->status = 2; // Assuming '2' means 'cancelled'
            $subscription->save();

            // Update the user's status
            $user = User::find($id);
            if ($user) {
                $user->status = 2; // Assuming '2' means 'inactive' or 'cancelled'
                $user->save();
            }

            // Retrieve email settings
            $setting = Setting::first();

            $name = $user->f_name;
            $website_address = $setting->business_website_address;
            $phone = $setting->business_phone_number;
            $email = $setting->business_email_address;

            // Send the welcome email
            Mail::to($user->email)->send(new Cancel($name, $website_address, $phone, $email));

            // Redirect back with success message
            return redirect()->back()->with('success', 'Your subscription has been cancelled successfully.');
        } else {
            // Redirect back with error message
            return redirect()->back()->with('error', 'You do not have an active subscription to cancel.');
        }
    }

}

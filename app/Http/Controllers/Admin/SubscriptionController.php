<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Mail\Cancel;
use App\Models\Service;

class SubscriptionController extends Controller
{
    //
    public function index()
    {
        $products = Product::get();
        $services = Service::get();
        $subscriptionPlans = SubscriptionPlan::get();
        $subs_id = SubscriptionPlan::max('id') + 1 ;
        $subscriptionCount = User::where('user_role',2)->count();

        $subscriptionValue = User::join('subscriptions','subscriptions.user_id','users.id')
        ->join('subscription_plans','subscription_plans.id','subscriptions.subscription_plan_id')
        ->where('users.user_role',2)
        ->sum('subscription_plans.price_of_subscription');

        $currentMonthSubscriptionValue = User::join('subscriptions','subscriptions.user_id','users.id')
        ->join('subscription_plans','subscription_plans.id','subscriptions.subscription_plan_id')
        ->where('users.user_role',2)
        ->whereMonth('users.created_at',date('m'))
        ->sum('subscription_plans.price_of_subscription');

        $lastMonthSubscriptionValue = User::join('subscriptions','subscriptions.user_id','users.id')
        ->join('subscription_plans','subscription_plans.id','subscriptions.subscription_plan_id')
        ->where('users.user_role', 2)
        ->whereMonth('users.created_at', date('m', strtotime('-1 month')))
        ->sum('subscription_plans.price_of_subscription');

        // Define the payment frequency to days mapping
        $frequencyDays = [
            1 => 7,    // Weekly
            2 => 30,   // Monthly
            3 => 90,   // Quarterly
            4 => 180,  // Half-Yearly
            5 => 365,  // Yearly
        ];
         // Attach products to each subscription plan (map product IDs to product objects)
        foreach ($subscriptionPlans as $plan) {
            $productIds = explode(',', $plan->subscription_package);
            $serviceIds = explode(',', $plan->subscription_services);
            $plan->products = $products->only($productIds); // Attach related products to the plan
            $plan->services = $services->only($serviceIds); // Attach related products to the plan
            $plan->days = $frequencyDays[$plan->payment_frequency] ?? 'Unknown';
        }

        $title='Subscriptions';

        return view('admin.subscriptions.index',compact('title','services','products','subscriptionPlans','subs_id','subscriptionCount','subscriptionValue','currentMonthSubscriptionValue','lastMonthSubscriptionValue'));
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $data = $request->all();

            if($request->hasFile('photo')){
                $file = $request->file('photo');
                // $fileName = time() . '.' . $file->getClientOriginalExtension();
                $data['photo']=$this->uploadImage($file);
            }
            $data['subscription_package'] = implode(',',$request->subscription_package);
            $data['subscription_services'] = implode(',',$request->subscription_services);
            // return $data;
            if ($request->id) {
                $subscription = SubscriptionPlan::find($request->id);
                if(!$request->hasFile('photo') && (!isset($request->photo_url) || $request->photo_url == '')){
                    $data['photo'] = null;
                }
                $subscription->update($data);
                return redirect()->back()->with('success', 'Subscription plan updated successfully.');
            } else {
                $subscription = SubscriptionPlan::create($data);
                return redirect()->back()->with('success', 'Subscription plan created successfully.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\SubscriptionPlan;
use App\Models\PasswordReset;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\BankDetail;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\StripeClient;
use Stripe\Exception\CardException;

use App\Mail\EmailTemplate;

use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;
use Square\Models\Currency;

use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

    public function register(Request $request,$sub_id)
    {
        try {
            $subscription = SubscriptionPlan::find($sub_id);
            if($subscription){
                $stripePublishableKey = get_setting('stripe_publishable_key');
                $plan = SubscriptionPlan::find($sub_id);
                if ($sub_id == 'null') {
                    abort(404, 'Page Not Found');
                }
                $settings = Setting::where('user_id',1)->first();
                $counter = [];
                if($settings){
                    $counter = explode(':',$settings->countdown_timer);
                }
                $products=[];
                if($plan){
                    $products = Product::whereIn('id',explode(',',$plan->subscription_package))->get();
                }

                return view('auth.premium-register', compact('stripePublishableKey','plan','products','settings','counter'));
            }else{
                abort(404, 'Page Not Found');
            }
            
        } catch (Exception $e) {
            abort(404, 'Page Not Found');
        }

    }

    // public function store(Request $request)
    // {
    //     // return $request->all();
    //     // Validate the input
    //     $validator = Validator::make($request->all(), [
    //         'f_name' => 'required|string|max:255',
    //         'l_name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'phone_no' => 'required|string|max:15',
    //         'password' => 'required|string|min:8|confirmed',
    //         'stripeToken' => 'required|string', // Token from Stripe Elements
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     // Fetch Stripe API keys from settings
    //     $stripeSecret = get_setting('stripe_secret_key');
    //     // Set your secret key
    //     Stripe::setApiKey($stripeSecret);

    //     try {
    //         // Create the charge on Stripe's servers
    //         $charge = Charge::create([
    //             'amount' => 100, // Amount in cents (e.g., $1.00)
    //             'currency' => 'usd',
    //             'description' => 'User Registration Fee',
    //             'source' => $request->input('stripeToken'),
    //         ]);
    //         // If the charge is successful, create the user
    //         $data = $request->all();
    //         $data['user_role'] = 2;
    //         $data['status'] = 1;
    //         $user = User::create($data);

    //         $plan = SubscriptionPlan::find($request->subscription_id);

    //         // Start date for the subscription
    //         $start_date = Carbon::now();

    //         // Determine the end date based on payment frequency
    //         switch ($plan->payment_frequency) {
    //             case 1: // Weekly
    //                 $subscription_end = $start_date->addWeek()->format('Y-m-d');
    //                 break;
    //             case 2: // Monthly
    //                 $subscription_end = $start_date->addMonth()->format('Y-m-d');
    //                 break;
    //             case 3: // Quarterly (every 3 months)
    //                 $subscription_end = $start_date->addMonths(3)->format('Y-m-d');
    //                 break;
    //             case 4: // Half-Yearly (every 6 months)
    //                 $subscription_end = $start_date->addMonths(6)->format('Y-m-d');
    //                 break;
    //             case 5: // Yearly
    //                 $subscription_end = $start_date->addYear()->format('Y-m-d');
    //                 break;
    //             default:
    //                 $subscription_end = $start_date->format('Y-m-d'); // Fallback to current date
    //         }

    //         $subscriptionData = [
    //             'user_id' => $user->id,
    //             'subscription_plan_id' => $request->subscription_id,
    //             'subscription_start' => date('y-m-d'),
    //             'subscription_end' => $subscription_end,
    //             'is_active' => 1,
    //         ];

    //         // return $subscriptionData;

    //         Subscription::create($subscriptionData);

    //         // Store the transaction
    //         Transaction::create([
    //             'member_id' => $user->id, // Assuming the user ID is stored as member_id
    //             'transaction_id' => str_replace('ch_', '', $charge->id), // Stripe's charge ID
    //             'subscription_plan_id' => $request->subscription_id, // Set this if applicable
    //             'transaction_type' => 'registration_fee', // Define based on your use case
    //             'invoice_id' => $charge->invoice, // Optional, depending on whether you're using invoices
    //             'amount' => $charge->amount / 100, // Convert cents to dollars
    //             'status' => 1, // Assuming the transaction was successful
    //         ]);

    //         $setting = Setting::first();

    //         $name = $user->f_name. ' ' .$user->l_name;
    //         $logo = $setting->email_logo;
    //         $graphic = $setting->email_background_image;
    //         $header = $setting->successful_registration_subject;
    //         $body = $setting->successful_registration_body;
    //         // Send the welcome email
    //         Mail::to($user->email)->send(new EmailTemplate($name,$logo,$graphic,$header,$body));

    //         return redirect('/')->with('success', 'Your registration was successful. You may now proceed to log in.');
    //     } catch (CardException $e) {
    //         // Handle card error
    //         return redirect()->back()->withErrors(['payment_error' => $e->getMessage()])->withInput();
    //     }
    // }


    public function store(Request $request)
    {
        // Custom validation for email to check if the user exists but is deleted or has a status of 2
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $existingUser = User::withTrashed()->where('email', $value)->first();
                    if ($existingUser) {
                        if (is_null($existingUser->deleted_at) && $existingUser->status != 2) {
                            return $fail('A user with this email already exists and cannot be registered again.');
                        }
                    }
                },
            ],
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'amount' => 'required|numeric|min:0',
            'nonce' => 'required|string',
            'subscription_id' => 'required|exists:subscription_plans,id',
        ]);

        $client = new SquareClient([
            'environment' => 'sandbox',
            'accessToken' => 'EAAAl1-w6tp0GufLR3981_y8UuKNgaDrWUM9Lty7S6BBoJSypezaKETFyVQ3M86D', 
        ]);

        $paymentsApi = $client->getPaymentsApi();

        try {
            // return $request->all();
            // Generate a unique idempotency key
            $idempotencyKey = uniqid('', true);

            // Create an instance of Money
            $money = new Money();
            $money->setAmount(floatval($request->amount) * 100); // Convert total to cents
            $money->setCurrency(Currency::USD); // Use the CAD currency

            // Create an instance of CreatePaymentRequest
            $create_payment_request = new CreatePaymentRequest($request->nonce, $idempotencyKey);

            // Set the amount_money for the payment request
            $create_payment_request->setAmountMoney($money);

            // Call the createPayment method with the CreatePaymentRequest instance
            $response = $paymentsApi->createPayment($create_payment_request);

            // Handle successful payment response
            if ($response->isSuccess()) {
                // Check if the user was previously soft deleted or had status 2 and restore or reuse the record
                $existingUser = User::withTrashed()->where('email', $request->email)->first();
                
                if ($existingUser && ($existingUser->trashed() || $existingUser->status == 2)) {
                    // If the user exists, restore them or reset the status
                    $existingUser->restore(); // Restore if soft deleted
                    $existingUser->update([
                        'user_role' => 2,
                        'status' => 1, // Reactivate the user
                        'password' => bcrypt($request->password), // Update password if needed
                    ]);
                    $user = $existingUser;
                } else {
                    // Create a new user if not found or cannot reuse
                    $data = $request->all();
                    $data['user_role'] = 2;
                    $data['status'] = 1;
                    $data['password'] = bcrypt($data['password']);
                    $user = User::create($data);
                }

                // Handle subscription and transaction logic
                $plan = SubscriptionPlan::find($request->subscription_id);
                $start_date = Carbon::now();
                $end_date = clone $start_date;

                switch ($plan->payment_frequency) {
                    case 1:
                        $subscription_end = $end_date->addWeek()->format('Y-m-d');
                        break;
                    case 2:
                        $subscription_end = $end_date->addMonth()->format('Y-m-d');
                        break;
                    case 3:
                        $subscription_end = $end_date->addMonths(3)->format('Y-m-d');
                        break;
                    case 4:
                        $subscription_end = $end_date->addMonths(6)->format('Y-m-d');
                        break;
                    case 5:
                        $subscription_end = $end_date->addYear()->format('Y-m-d');
                        break;
                    default:
                        $subscription_end = $start_date->format('Y-m-d');
                }

                $subscriptionData = [
                    'user_id' => $user->id,
                    'subscription_plan_id' => $request->subscription_id,
                    'subscription_start' => now()->format('Y-m-d'),
                    'subscription_end' => $subscription_end,
                    'is_active' => 1,
                ];

                Subscription::create($subscriptionData);

                Transaction::create([
                    'member_id' => $user->id,
                    'transaction_id' => $response->getResult()->getPayment()->getId(),
                    'subscription_plan_id' => $request->subscription_id,
                    'transaction_type' => 'registration_fee',
                    'amount' => $request->amount,
                    'status' => 1,
                ]);

                BankDetail::create([
                    'user_id' => $user->id,
                    'card_no' => $request->card_number,
                    'cardholder_name' => $request->name_on_card,
                    'expiration' => $request->expiry,
                    'security' => $request->ccv
                ]);


                // Retrieve email settings and send a welcome email
                $setting = Setting::first();
                $name = $user->f_name . ' ' . $user->l_name;
                $logo = $setting->email_logo;
                $graphic = $setting->email_background_image;
                $header = $setting->successful_registration_subject;
                $body = $setting->successful_registration_body;

                Mail::to($user->email)->send(new EmailTemplate($name, $logo, $graphic, $header, $body));

                return redirect('/')->with('success', 'Your registration was successful. You may now log in.');
            } else {
                return redirect()->back()->withErrors(['payment_error' => 'Payment failed. Please try again.'])->withInput();
            }
        } catch (\Square\Exceptions\ApiException $e) {
            return redirect()->back()->withErrors(['payment_error' => 'Payment processing error.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['payment_error' => 'An unexpected error occurred.'.$e->getMessage()])->withInput();
        }
    }




    public function profile()
    {
        // Retrieve the authenticated client user
        $client = Auth::user();

        if (!$client) {
            // Redirect if no user is authenticated
            return redirect()->route('/')->with('error', 'You need to be logged in to access this page.');
        }

        // Retrieve the user's profile and payment details
        $user = User::leftjoin('bank_details', 'users.id', '=', 'bank_details.user_id')
            ->select('users.*', 'bank_details.card_type', 'bank_details.card_no', 'bank_details.expiration', 'bank_details.cardholder_name', 'bank_details.security', 'bank_details.id as bank_id')
            ->where('users.id', $client->id)
            ->first();

        $isCancel = false;
        $timeLimit = 48; // Time limit in hours (you can make this dynamic)
        $userCreatedAt = $user->created_at; // Assuming this is a timestamp or Carbon instance
        // return Carbon::parse($userCreatedAt)->diffInHours(Carbon::now());
        if (Carbon::parse($userCreatedAt)->diffInHours(Carbon::now()) > $timeLimit) {
            $isCancel = true;
        }

        if (!$user) {
            // Handle case where user details are not found
            return redirect()->back()->with('error', 'Profile not found. Please try again later.');
        }

        $subscription = Subscription::where('user_id',Auth::user()->id)->first();


        return view('profile.index', compact('user','subscription','isCancel'));
    }

    public function updateProfile(Request $request)
    {

        // Retrieve the authenticated admin user id
        $clientId = Auth::guard('web')->user()->id;



        $validator = Validator::make($request->all(),  [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:255|unique:users,email,' . $clientId, // Ensure email is unique
            'phone_number' => 'required|string|max:20|unique:users,phone_no,' . $clientId, // Ensure phone number is unique
            'photo_input' => 'nullable|image|mimes:jpeg,png,jpg', // Optional image validation
            'photo' => 'nullable|string', // Optional image value
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $client = User::find($clientId);



        if (!$client) {
            // Handle the case where no client user is found (optional)
            return redirect()->intended('/');

        }

        // Update client properties
        $client->f_name = $request->first_name;
        $client->l_name = $request->last_name; // This can be null
        $client->address = $request->address;
        $client->email = $request->email_address;
        $client->phone_no = $request->phone_number;
        $client->photo = $request->photo ?? null;


        if ($request->hasFile('photo_input')) {

            // Store the file and get the file path
            $file = $request->file('photo_input');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $client->photo = $this->uploadImage($file, $fileName);
        }

        // Save the updated client data
        $client->save();

        return redirect()->intended('/profile')->with('success', 'Profile has been updated successfully.');
    }


    private function uploadImage($file, $fileName)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        // Return the path where the file is stored
        return url('/') . '/public/images/' . $filename;
    }


    public function admin_profile()
    {
        return view('admin.profile.index');
    }

    public function login(Request $request)
    {
        // Validate the login request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check if the user status is 2 (prevent login)
            if ($user->status == 2) {
                return redirect()->back()
                    ->withErrors(['email' => ' Your subscription has been canceled, and you no longer have access to your account. <br/>Please reach out to customer support for further assistance.'])
                    ->withInput();
            }

            // Admin users or users with specific roles (1, 3, 4)
            if ($user->user_role == 3 || $user->user_role == 4) {
                if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return redirect()->intended('admin/members');
                }
            } else {
                // Regular users with status 1 (active) or 3 (paused but allowed to login)
                if (($user->status == 1 || $user->status == 3) && $user->user_role == 2) {
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'user_role' => 2])) {
                        return redirect()->intended('transactions');
                    }
                }
            }
        }

        // If authentication fails, return an error message
        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials'])
            ->withInput();
    }



    public function members(Request $request)
    {
        return view('admin.members.index');
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'user_role' => 1
        ];


        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('admin/members');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials'])
            ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');

    }


    public function reset()
    {
        $settings = Setting::first();
        $counter = [];
        if($settings){
            $counter = explode(' ',$settings->countdown_timer);
        }
        return view('auth.passwords.reset',compact('settings','counter'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if the email exists in the database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        if ($user->status == 2) {
            return back()->withErrors(['email' => 'Your subscription has been canceled.']);
        }

        // Generate a token
        $token = Str::random(60);

        // Insert or update the token in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );


        $setting = Setting::where('user_id', 1)->first();

        $name = $user->f_name . ' ' . $user->l_name;
        $website_address = $setting->business_website_address;
        $email = $setting->business_email_address;
        $phone = $setting->business_phone_number;

        // Send the reset link to the user's email
        $link = url('password/confirm/' . $token);
        Mail::send('emails.password_reset', [
            'link' => $link,
            'name' => $name,
            'website_address' => $website_address,
            'email' => $email,
            'phone' => $phone,
        ], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset Request');
        });

        return back()->with('success', 'We have emailed your password reset link!');
    }

    public function showResetLinkRequestForm(Request $request, $token = null)
    {
        return view('auth.passwords.confirm')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    public function confirm(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Find the password reset entry
        $passwordReset = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        // Check if the password reset entry is found
        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }

        // Check token expiration (1 hour validity)
        $tokenCreatedAt = $passwordReset->created_at;
        $expirationTime = \Carbon\Carbon::parse($tokenCreatedAt)->addHour();
        if (now()->gt($expirationTime)) {
            return back()->withErrors(['token' => 'This password reset token has expired.']);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset entry
        DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->delete();

        if($user->user_role == 1){
            // Redirect to login page with success message
            return redirect('/admin')->with('success', 'Password has been reset successfully.');
        }else{
            return redirect('/')->with('success', 'Password has been reset successfully.');
        }
        
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        $userId = Auth::user()->id;

        $user = User::find($userId);

        if (!$user) {
            return back()->withErrors(['error' => 'We could not find a user.']);
        }

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The provided password does not match our records.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect to profile page with success message
        return redirect()->intended('profile')->with('success', 'Password has been changed successfully.');
    }

    public function addBankDetail(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        // return $data;
        if($request->bank_id){
            $BankDetail = BankDetail::find($request->bank_id);
            $BankDetail->update($data);
            return redirect()->back()->with('success','Bank information updated successfully');
        }else{
            BankDetail::create($data);
            return redirect()->back()->with('success','Bank information added successfully');
        }        
    }
}

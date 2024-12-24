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
use App\Models\Service;
use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\CreateCustomerRequest;
use Square\Models\CreateCustomerCardRequest;
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

    // public function register(Request $request, $sub_id)
    // {
    //     try {
    //         $subscription = SubscriptionPlan::find($sub_id);
    //         if ($subscription) {
    //             $stripePublishableKey = get_setting('stripe_publishable_key');
    //             $plan = SubscriptionPlan::find($sub_id);
    //             if ($sub_id == 'null') {
    //                 abort(404, 'Page Not Found');
    //             }
    //             $settings = Setting::where('user_id', 1)->first();
    //             $counter = [];
    //             if ($settings) {
    //                 $counter = explode(':', $settings->countdown_timer);
    //             }
    //             $products = [];
    //             if ($plan) {
    //                 $products = Product::whereIn('id', explode(',', $plan->subscription_package))->get();
    //                 $services = Service::whereIn('id', explode(',', $plan->subscription_services))->get();
    //             }

    //             return view('auth.premium-register', compact('stripePublishableKey', 'plan', 'products','services', 'settings', 'counter'));
    //         } else {
    //             abort(404, 'Page Not Found');
    //         }
    //     } catch (Exception $e) {
    //         abort(404, 'Page Not Found');
    //     }
    // }

    //       public function register(Request $request, $sub_id)
    // {
    //     try {
    //         $subscription = SubscriptionPlan::find($sub_id);
    //         if ($subscription) {
    //             $stripePublishableKey = get_setting('stripe_publishable_key');
    //             $plan = SubscriptionPlan::find($sub_id);
    //             if ($sub_id == 'null') {
    //                 abort(404, 'Page Not Found');
    //             }
    //             $settings = Setting::where('user_id', 1)->first();
    //             $counter = [];
    //             if ($settings) {
    //                 $counter = explode(':', $settings->countdown_timer);
    //             }
    //             $products = [];
    //             if ($plan) {
    //                 $products = Product::whereIn('id', explode(',', $plan->subscription_package))->get();
    //                 $services = Service::whereIn('id', explode(',', $plan->subscription_services))->get();
    //             }

    //             $ratio_1 = $settings->ratio_1; 
    //             $ratio_2 = $settings->ratio_2; 
    //             $number = $settings->number; 

    //             // Get the count of users with user_role = 2
    //             $userCount = User::where('user_role', 2)->count();

    //             // Calculate the number of seats taken (userdata)
    //             $userdata = $userCount / $ratio_1 * $ratio_2;

    //             // Calculate the remaining seats
    //             $remainingSeats = max($number - $userdata, 0); // Ensure it's not negative

    //             return view('auth.premium-register', compact('stripePublishableKey', 'plan', 'products', 'services', 'settings', 'counter', 'remainingSeats'));
    //         } else {
    //             abort(404, 'Page Not Found');
    //         }
    //     } catch (Exception $e) {
    //         abort(404, 'Page Not Found');
    //     }
    // }


    public function register(Request $request, $sub_id)
    {
        try {
            $subscription = SubscriptionPlan::find($sub_id);
            if ($subscription) {
                $stripePublishableKey = get_setting('stripe_publishable_key');
                $plan = SubscriptionPlan::find($sub_id);
                if ($sub_id == 'null') {
                    abort(404, 'Page Not Found');
                }

                // Retrieve the settings
                $settings = Setting::where('user_id', 1)->first();
                $counter = [];
                if ($settings && $settings->countdown_timer) {
                    $counter = explode(':', $settings->countdown_timer);
                }

                $products = [];
                $services = [];
                if ($plan) {
                    $products = Product::whereIn('id', explode(',', $plan->subscription_package))->get();
                    $services = Service::whereIn('id', explode(',', $plan->subscription_services))->get();
                }

                // Check if settings are found
                if ($settings) {
                    $ratio_1 = $settings->ratio_1 ?? 1; // Fallback to 1 if null
                    $ratio_2 = $settings->ratio_2 ?? 1; // Fallback to 1 if null
                    $number = $settings->number ?? 0;
                    $updatedAt = $settings->ratio_update_time;

                    $userCount = User::where('user_role', 2)
                        ->where('created_at', '>', $updatedAt)
                        ->count();
                    $userdata = (floor($userCount / $ratio_1)) * $ratio_2;

                    $remainingSeats = max($number - $userdata, 0);
                } else {
                    // Default values if no settings are found
                    $remainingSeats = 0;
                }

                return view('auth.premium-register', compact(
                    'stripePublishableKey',
                    'plan',
                    'products',
                    'services',
                    'settings',
                    'counter',
                    'remainingSeats'
                ));
            } else {
                abort(404, 'Page Not Found');
            }
        } catch (Exception $e) {
            abort(404, 'Page Not Found');
        }
    }




    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users',
                function ($attribute, $value, $fail) {
                    $existingUser = User::withTrashed()->where('email', $value)->first();
                    if ($existingUser && $existingUser->status != 2 && is_null($existingUser->deleted_at)) {
                        return $fail('A user with this email already exists and cannot be registered again.');
                    }
                },
            ],
            'phone_no' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
            'subscription_id' => 'required|exists:subscription_plans,id',
            'acceptTerms' => 'required|accepted',
        ], [
            'acceptTerms.required' => 'Please check to proceed',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Determine the payment method
        $paymentMethod = get_setting('payment_gateway'); // 'stripe' or 'square'

        if ($paymentMethod == '1') {
            return $this->handleStripePayment($request);
        } elseif ($paymentMethod == '2') {
            return $this->handleSquarePayment($request);
        } elseif ($paymentMethod == '3') {
            return $this->handleAfterpayPayment($request);
        } else {
            return redirect()->back()->withErrors(['payment_error' => 'Invalid payment method.'])->withInput();
        }
    }

    protected function handleStripePayment(Request $request)
    {
        // Fetch Stripe API keys from settings
        $stripeSecret = get_setting('stripe_secret_key');
        Stripe::setApiKey($stripeSecret);

        try {
            // Sanitize and verify the payment method ID
            $paymentMethodId = $request->stripeToken;

            // Create a Payment Intent to handle the actual charge
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => floatval($request->amount * 100), // Amount in cents (e.g., $1.00)
                'currency' => 'usd',
                'payment_method' => $paymentMethodId,
                'confirm' => true, // Immediately confirm the payment
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never', // Prevent redirects
                ],
            ]);

            // Check the status of the Payment Intent
            if ($paymentIntent->status === 'succeeded') {
                // Create the user if the payment was successful
                $data = $request->all();
                $data['user_role'] = 2;
                $data['status'] = 1;
                $data['password'] = bcrypt($data['password']);
                $user = User::create($data);

                $paymentID = str_replace('pi_', '', $paymentIntent->id);
                // Handle subscription and transaction logic
                return $this->finalizeRegistration($user, $request, $paymentID);
            } else {
                return redirect()->back()->withErrors(['payment_error' => 'Payment not successful. Please try again.'])->withInput();
            }
        } catch (CardException $e) {
            return redirect()->back()->withErrors(['payment_error' => $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['payment_error' => 'An unexpected error occurred: ' . $e->getMessage()])->withInput();
        }
    }

    protected function handleSquarePayment(Request $request)
    {

        // return $request->all();

        $client = new SquareClient([
            'environment' => 'production',
            'accessToken' => get_setting('square_access_token'), // Use your Square access token
        ]);

        $paymentsApi = $client->getPaymentsApi();

        $customersApi = $client->getCustomersApi();

        try {

            $cardNonce = $request->nonce; // Pass the nonce received from the frontend

            // Step 1: Create a Customer
            $name = $request->f_name . $request->l_name;
            $email = $request->email;
            $customerRequest = new CreateCustomerRequest();
            $customerRequest->setGivenName($name);
            $customerRequest->setEmailAddress($email);

            $customerResponse = $customersApi->createCustomer($customerRequest);

            if (!$customerResponse->isSuccess()) {
                \Log::error('Failed to create customer', $customerResponse->getErrors());
                return response()->json(['error' => 'Failed to create customer'], 500);
            }

            $customerId = $customerResponse->getResult()->getCustomer()->getId();

            // Step 2: Save the Card on File
            $createCustomerCardRequest = new CreateCustomerCardRequest($cardNonce);
            $cardResponse = $customersApi->createCustomerCard($customerId, $createCustomerCardRequest);

            if (!$cardResponse->isSuccess()) {
                \Log::error('Failed to save card on file', $cardResponse->getErrors());
                return response()->json(['error' => 'Failed to save card on file'], 500);
            }

            $cardId = $cardResponse->getResult()->getCard()->getId();

            // Create an instance of Money
            $money = new Money();
            $money->setAmount(floatval($request->amount) * 100); // Convert total to cents
            $money->setCurrency(Currency::AUD); // Adjust as needed

            // Create payment request
            $createPaymentRequest = new CreatePaymentRequest($cardId, uniqid('', true));
            $createPaymentRequest->setCustomerId($customerId);
            $createPaymentRequest->setAmountMoney($money);
            $createPaymentRequest->setAutocomplete(false);
            // Process payment
            $paymentResponse = $paymentsApi->createPayment($createPaymentRequest);

            if (!$paymentResponse->isSuccess()) {
                \Log::error('Failed to process payment', $paymentResponse->getErrors());
                return response()->json(['error' => 'Failed to process payment'], 500);
            }
            // Success
            $paymentId = $paymentResponse->getResult()->getPayment()->getId();

            // Check if the user was previously soft deleted or had status 2 and restore or reuse the record
            $existingUser = User::withTrashed()->where('email', $request->email)->first();
            if ($existingUser && ($existingUser->trashed() || $existingUser->status == 2)) {
                $existingUser->restore();
                $existingUser->update([
                    'user_role' => 2,
                    'status' => 1,
                    'password' => bcrypt($request->password),
                ]);
                $user = $existingUser;
            } else {
                $data = $request->all();
                $data['user_role'] = 2;
                $data['status'] = 1;
                $data['profile_id'] = $customerId;
                $data['card_id'] = $cardId;
                $data['password'] = bcrypt($data['password']);
                $user = User::create($data);
            }

            return $this->finalizeRegistration($user, $request, $paymentId);
        } catch (\Square\Exceptions\ApiException $e) {
            return redirect()->back()->withErrors(['payment_error' => 'Payment processing error.'])->withInput();
        }
    }

    protected function handleAfterpayPayment(Request $request)
    {

        // Check if the user was previously soft deleted or had status 2 and restore or reuse the record
        $existingUser = User::withTrashed()->where('email', $request->email)->first();
        if ($existingUser && ($existingUser->trashed() || $existingUser->status == 2)) {
            $existingUser->restore();
            $existingUser->update([
                'user_role' => 2,
                'status' => 1,
                'password' => bcrypt($request->password),
            ]);
            $user = $existingUser;
        } else {
            $data = $request->all();
            $data['user_role'] = 2;
            $data['status'] = 1;
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
        }

        return $this->finalizeRegistration($user, $request, $request->afterpay_token);
    }

    protected function finalizeRegistration($user, Request $request, $paymentId)
    {
        $plan = SubscriptionPlan::find($request->subscription_id);
        $start_date = Carbon::now();
        switch ($plan->payment_frequency) {
            case 1: // Weekly
                $subscription_end = $start_date->addWeek()->format('Y-m-d');
                break;
            case 2: // Monthly
                $subscription_end = $start_date->addMonth()->format('Y-m-d');
                break;
            case 3: // Quarterly
                $subscription_end = $start_date->addMonths(3)->format('Y-m-d');
                break;
            case 4: // Half-Yearly
                $subscription_end = $start_date->addMonths(6)->format('Y-m-d');
                break;
            case 5: // Yearly
                $subscription_end = $start_date->addYear()->format('Y-m-d');
                break;
            default:
                $subscription_end = $start_date->format('Y-m-d');
        };

        // Create subscription
        Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $request->subscription_id,
            'subscription_start' => now()->format('Y-m-d'),
            'subscription_end' => $subscription_end,
            'is_active' => 1,
        ]);

        $paymentMethod = get_setting('payment_gateway');

        // Store the transaction
        Transaction::create([
            'member_id' => $user->id,
            'transaction_id' => $paymentId,
            'subscription_plan_id' => $request->subscription_id,
            'transaction_type' => 'registration_fee',
            'payment_method' => $paymentMethod, // Adjust if necessary
            'amount' => $request->amount, // Adjust if necessary
            'status' => 1,
        ]);

        BankDetail::create([
            'user_id' => $user->id, // Ensure user_id is included
            'cardholder_name' => $request->name_on_card,
            'card_no' => $request->card_number,
            'expiration' => $request->expiry,
            'security' => $request->ccv,
        ]);

        // Send welcome email
        $setting = Setting::first();
        $name = $user->f_name . ' ' . $user->l_name;
        Mail::to($user->email)->send(new EmailTemplate($name, $setting->email_logo, $setting->email_background_image, $setting->successful_registration_subject, $setting->successful_registration_body));

        return redirect('/')->with('success', 'Your registration was successful. You may log in now.');
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

        $subscription = Subscription::where('user_id', Auth::user()->id)->first();
        $title = "Profile";

        return view('profile.index', compact('title', 'user', 'subscription', 'isCancel'));
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
            // $fileName = time() . '.' . $file->getClientOriginalExtension();
            $client->photo = $this->uploadImage($file);
        }

        // Save the updated client data
        $client->save();

        return redirect()->intended('/profile')->with('success', 'Profile has been updated successfully.');
    }


    private function uploadImage($file)
    {
        // Define the filename
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        // Store the file in the 'images' directory
        $file->storeAs('images', $fileName, 'protected');

        // Return only the filename
        return $fileName;
    }
    // private function uploadImage($file, $fileName)
    // {
    //     $filename = time() . '.' . $file->getClientOriginalExtension();
    //     $file->move(public_path('images'), $filename);
    //     // Return the path where the file is stored
    //     return url('/') . '/public/images/' . $filename;
    // }


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
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_role' => 2])) {
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
        if ($settings) {
            $counter = explode(' ', $settings->countdown_timer);
        }


        if ($settings) {
            $ratio_1 = $settings->ratio_1 ?? 1; // Fallback to 1 if null
            $ratio_2 = $settings->ratio_2 ?? 1; // Fallback to 1 if null
            $number = $settings->number ?? 0;
            $updatedAt = $settings->ratio_update_time;

            $userCount = User::where('user_role', 2)
                ->where('created_at', '>', $updatedAt)
                ->count();
            $userdata = (floor($userCount / $ratio_1)) * $ratio_2;

            $remainingSeats = max($number - $userdata, 0);
        } else {
            // Default values if no settings are found
            $remainingSeats = 0;
        }


        return view('auth.passwords.reset', compact('settings', 'counter', 'remainingSeats'));
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


        $setting = Setting::first();

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
        $settings = Setting::first();
        $counter = [];
        if ($settings) {
            $counter = explode(' ', $settings->countdown_timer);
        }


        if ($settings) {
            $ratio_1 = $settings->ratio_1 ?? 1; // Fallback to 1 if null
            $ratio_2 = $settings->ratio_2 ?? 1; // Fallback to 1 if null
            $number = $settings->number ?? 0;
            $updatedAt = $settings->ratio_update_time;

            $userCount = User::where('user_role', 2)
                ->where('created_at', '>', $updatedAt)
                ->count();
            $userdata = (floor($userCount / $ratio_1)) * $ratio_2;

            $remainingSeats = max($number - $userdata, 0);
        } else {
            // Default values if no settings are found
            $remainingSeats = 0;
        }


        // return view('auth.passwords.reset', compact('settings', 'counter','remainingSeats'));

        return view('auth.passwords.confirm', compact('settings', 'counter', 'remainingSeats'))->with(
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

        if ($user->user_role == 1) {
            // Redirect to login page with success message
            return redirect('/admin')->with('success', 'Password has been reset successfully.');
        } else {
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
        if ($request->bank_id) {
            $BankDetail = BankDetail::find($request->bank_id);
            $BankDetail->update($data);
            return redirect()->back()->with('success', 'Bank information updated successfully');
        } else {
            BankDetail::create($data);
            return redirect()->back()->with('success', 'Bank information added successfully');
        }
    }
}

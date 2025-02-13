<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\StripeClient;
use Stripe\Exception\CardException;
use App\Models\User;
use App\Models\Transaction;
use App\Models\PasswordReset;
use App\Exports\MembersExport;
use App\Models\SubscriptionPlan;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\BankDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\UserRegistered;
use App\Mail\AddUser;
use Carbon\Carbon;
use PDF;

use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\CreateCustomerRequest;
use Square\Models\CreateCustomerCardRequest;
use Square\Models\Money;
use Square\Models\Currency;

class AdminController extends Controller
{
    //
    public function index()
    {

        $settings = Setting::where('user_id', 1)->first();
        return view('auth.login', compact('settings'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'stripeToken' => 'required|string', // Token from Stripe Elements
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Fetch Stripe API keys from settings
        $stripeSecret = get_setting('stripe_secret_key');

        // Set your secret key
        Stripe::setApiKey($stripeSecret);

        try {
            // Create the charge on Stripe's servers
            $charge = Charge::create([
                'amount' => 100, // Amount in cents (e.g., $1.00)
                'currency' => 'usd',
                'description' => 'User Registration Fee',
                'source' => $request->input('stripeToken'),
            ]);
            // If the charge is successful, create the user
            $data = $request->all();
            $data['user_role'] = 2;
            $data['status'] = 1;
            $user = User::create($data);

            // Store the transaction
            Transaction::create([
                'member_id' => $user->id, // Assuming the user ID is stored as member_id
                'transaction_id' => str_replace('ch_', '', $charge->id), // Stripe's charge ID
                'subscription_plan_id' => null, // Set this if applicable
                'transaction_type' => 'registration_fee', // Define based on your use case
                'invoice_id' => $charge->invoice, // Optional, depending on whether you're using invoices
                'amount' => $charge->amount / 100, // Convert cents to dollars
                'status' => 1, // Assuming the transaction was successful
            ]);

            // Send the welcome email
            Mail::to($user->email)->send(new UserRegistered($user));

            return redirect('login')->with('success', 'Your registration was successful. You may now proceed to log in.');
        } catch (CardException $e) {
            // Handle card error
            return redirect()->back()->withErrors(['payment_error' => $e->getMessage()])->withInput();
        }
    }


    public function profile()
    {
        $title = 'Profile';
        return view('profile.index', compact('title'));
    }

    public function admin_profile()
    {
        // Retrieve the authenticated admin user
        $adminUser = Auth::guard('admin')->user();

        if (!$adminUser) {
            // Handle the case where no admin user is found (optional)
            return redirect()->route('admin.admin_login')->with('error', 'You need to be logged in to access this page.');
        }

        // Retrieve relevant attributes from the admin user
        $profileData = [
            'firstName' => $adminUser->f_name,
            'lastName'  => $adminUser->l_name,
            'email'     => $adminUser->email,
            'phoneNo'   => $adminUser->phone_no,
            'photo'   => $adminUser->photo,
            'address'   => $adminUser->address,
            'role'      => $adminUser->user_role,
            'status'    => $adminUser->status, // Include this if you need it in the view
        ];

        $title = 'Profile';

        // Return the profile view with the collected data
        return view('admin.profile.index', $profileData, compact('title'));
    }

    public function update_admin_profile(Request $request)
    {

        // Retrieve the authenticated admin user id
        $adminId = Auth::guard('admin')->user()->id;

        $validator = Validator::make($request->all(),  [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:255|unique:users,email,' . $adminId, // Ensure email is unique
            'phone_number' => 'required|string|max:20,phone_no,' . $adminId, // Ensure phone number is unique
            'photo_input' => 'nullable|image|mimes:jpeg,png,jpg', // Optional image validation
            'photo' => 'nullable|string', // Optional image value
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



        $admin = User::find($adminId);

        if (!$admin) {
            // Handle the case where no admin user is found (optional)
            return redirect()->route('admin.admin_login')->with('error', 'You need to be logged in to access this page.');
        }



        // Update admin properties
        $admin->f_name = $request->first_name;
        $admin->l_name = $request->last_name; // This can be null
        $admin->address = $request->address;
        $admin->email = $request->email_address;
        $admin->phone_no = $request->phone_number;
        $admin->photo = $request->photo ?? null;

        if ($request->hasFile('photo_input')) {
            // Store the file and get the file path
            $file = $request->file('photo_input');
            // $fileName = time() . '.' . $file->getClientOriginalExtension();
            $admin->photo = $this->uploadImage($file);
        }

        // Save the updated admin data
        $admin->save();

        return redirect()->intended('admin/profile')->with('success', 'Profile has been updated successfully.');
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
    //     return url('/') . '/public/images/' . $filename;
    // }



    public function login(Request $request)
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
            'user_role' => 2
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('transactions');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials'])
            ->withInput();
    }

    // public function admin(Request $request)
    // {
    //     $settings = Setting::where('user_id',1)->first();
    //     $counter = [];
    //     if($settings){
    //         $counter = explode(' ',$settings->countdown_timer);
    //     }
    //     return view('auth.admin_login',compact('settings','counter'));
    // }

    public function admin(Request $request)
    {
        $settings = Setting::where('user_id', 1)->first();
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

        return view('auth.admin_login', compact('settings', 'counter', 'remainingSeats'));
    }

    public function members(Request $request)
    {

        // Total Members (assuming 'user_role' = 2 is for users)
        $totalMember = User::where('user_role', 2)->count();

        // Total Active Members
        $activeMember = User::where('user_role', 2)->where('status', 1)->count();

        // Total Paused Members
        $pauseMember = User::where('user_role', 2)->where('status', 3)->count();

        // Total Cancelled Members
        $cancelledMember = User::where('user_role', 2)->where('status', 2)->count();

        // Member Detail

        $members = User::withTrashed()
            ->leftjoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'subscriptions.subscription_plan_id')
            ->leftjoin('bookings', function ($join) {
                $join->on('bookings.member_id', '=', 'users.id')
                    ->where('bookings.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('bookings as b')
                            ->whereColumn('b.member_id', 'bookings.member_id');
                    });
            })
            ->leftjoin('services', 'services.id', '=', 'bookings.service_id')
            ->leftjoin('transactions', function ($join) {
                $join->on('transactions.member_id', '=', 'users.id')
                    ->where('transactions.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('transactions as t')
                            ->where('t.status', 1)
                            ->whereColumn('t.member_id', 'transactions.member_id');
                    });
            })
            ->where('user_role', 2)
            ->select('users.id', 'users.f_name', 'users.l_name', 'users.deleted_at', 'subscription_plans.title', 'subscriptions.status', 'services.id AS service_id', 'services.title AS service', 'services.description AS service_desc', 'bookings.booking_date', 'bookings.id as booking_id', 'transactions.created_at as payment_date', 'users.rating', 'bookings.slot_id', 'bookings.booking_start_time', 'bookings.booking_end_time')
            ->get();



        $title = 'Members';
        return view('admin.members.index', compact('title', 'totalMember', 'activeMember', 'pauseMember', 'cancelledMember', 'members'));
    }

    public function exportExcel()
    {
        return Excel::download(new MembersExport, 'members.xlsx');
    }

    public function exportPDF()
    {
        $members = User::leftjoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'subscriptions.subscription_plan_id')
            ->leftjoin('bookings', function ($join) {
                $join->on('bookings.member_id', '=', 'users.id')
                    ->where('bookings.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('bookings as b')
                            ->whereColumn('b.member_id', 'bookings.member_id');
                    });
            })
            ->leftjoin('services', 'services.id', '=', 'bookings.service_id')
            ->leftjoin('slots', 'bookings.slot_id', 'slots.id')
            ->leftjoin('transactions', function ($join) {
                $join->on('transactions.member_id', '=', 'users.id')
                    ->where('transactions.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('transactions as t')
                            ->where('t.status', 1)
                            ->whereColumn('t.member_id', 'transactions.member_id');
                    });
            })
            ->where('user_role', 2)
            ->select('users.id', 'users.f_name', 'users.l_name', 'subscription_plans.title', 'subscriptions.status', 'services.title AS service', 'bookings.booking_date', 'transactions.created_at as payment_date', 'users.rating', 'slots.start_time', 'slots.end_time')
            ->get();

        $setting = Setting::first();
        $subsCount = User::where('user_role', 2)->count();
        $subsCountThisMonth = User::where('user_role', 2)
            ->whereMonth('created_at', date('m'))
            ->count();
        $subsCountLastMonth = User::where('user_role', 2)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $subsAmount = Transaction::where('status', 1)->sum('amount');

        $pdf = PDF::loadView('admin.members.pdf', compact('members', 'setting', 'subsCount', 'subsCountThisMonth', 'subsCountLastMonth', 'subsAmount'));
        $pdf->setOptions(['defaultFont' => 'Poppins']);
        return $pdf->download('members.pdf');
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
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->intended('/admin');
        } else {
            Auth::logout();
            return redirect()->intended('/');
        }
    }


    public function reset()
    {
        return view('auth.passwords.reset');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if the email exists in the database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate a token
        $token = Str::random(60);

        // Insert or update the token in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Send the reset link to the user's email
        $resetLink = url('password/confirm/' . $token);
        Mail::send('emails.password_reset', ['link' => $resetLink], function ($message) use ($request) {
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
            'password' => 'required|confirmed',
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

        // Redirect to login page with success message
        return redirect('/login')->with('success', 'Password has been reset successfully.');
    }


    public function addRating(Request $request)
    {
        $user = User::find($request->user_id);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5' // Assuming rating is an integer between 1 and 5
        ]);

        // Set the rating for the user
        $user->rating = $request->rating;

        // Save the updated user information
        $user->save();

        // Optionally return a success response
        return redirect()->back()->with('success', 'Rating added successfully');
    }

    public function addUser(Request $request)
    {


        // If validation passes, continue with user creation
        $data = $request->all();

        if ($request->id) {
            $user = User::find($request->id);
            $user->update($data);
        } else {
            // Validate the request
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'f_name' => 'required|string|max:255',
                'l_name' => 'required|string|max:255',
                // Add other required fields and their validation rules here
            ]);

            $data['password'] = bcrypt('123456');
            $user = User::create($data);

            // Generate a token
            $token = Str::random(60);

            // Insert or update the token in the password_resets table
            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => now()]
            );

            // Send the reset link to the user's email
            $resetLink = url('password/confirm/' . $token);

            // Retrieve email settings
            $setting = Setting::first();

            $name = $user->f_name;
            $userRoles = [
                1 => 'Admin',
                2 => 'Member',
                3 => 'Contractor',
                4 => 'Staff',
            ];

            $user_role = $userRoles[$user->user_role] ?? 'Unknown';

            $website_address = $setting->business_website_address;
            $phone = $setting->business_phone_number;
            $email = $setting->business_email_address;

            // Send the welcome email
            Mail::to($user->email)->send(new AddUser($name, $user_role, $website_address, $phone, $email, $resetLink));
        }

        return redirect()->back()->with('success', 'User added successfully');
    }


    public function editUser(Request $request, $id)
    {
        $user = User::withTrashed()->find($id);
        $subscription = Subscription::where('user_id', $id)->first();
        $BankDetail = BankDetail::where('user_id', $id)->first();

        $title = 'Update Member';

        return view('admin.members.update_member', compact('title', 'user', 'subscription', 'BankDetail'));
    }

    public function updateUser(Request $request)
    {
        $data = $request->all();
        // return $data;
        $user = User::find($request->id);
        if ($request->hasFile('photo_input')) {
            // Store the file and get the file path
            $file = $request->file('photo_input');
            // $fileName = time() . '.' . $file->getClientOriginalExtension();
            $data['photo'] = $this->uploadImage($file);
        }
        $user->update($data);
        return redirect()->back();
    }

    public function deleteUser(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Delete the related subscription before deleting the user
        if (Subscription::where('user_id', $user->id)->exists()) {
            Subscription::where('user_id', $user->id)->forceDelete();
        }
        
        if (BankDetail::where('user_id', $user->id)->exists()) {
            BankDetail::where('user_id', $user->id)->forceDelete();
        }
        

        // Now delete the user
        $user->forceDelete();

        return response()->json(['success' => 'User deleted successfully']);
    }

    public function archiveUser(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if ($user->deleted_at) {
            // If the user is archived, restore them
            $user->restore();
            return response()->json(['success' => 'User unarchived successfully']);
        } else {
            // If the user is active, archive them
            $user->delete();
            return response()->json(['success' => 'User archived successfully']);
        }
    }



    public function addBankDetail(Request $request)
    {
        $data = $request->all();
        // return $data;
        if ($request->nonce && $request->profile_id) {

            $cardId =  $this->updateSquarePayment($request->profile_id, $request->card_id, $request->nonce);
            $user = User::find($request->user_id);
            $user->card_id = $cardId;
            $user->save();
            \Log::info('Successfully updated customer card', [
                'user_id' => $user->id,
                'profile_id' => $request->profile_id,
                'card_id' => $cardId,
            ]);
        } else {

            $response = $this->addSquarePaymentDetail($request->cardholder_name, $request->email, $request->nonce);
            if ($response) {
                $profileId = $response[0];
                $cardId = $response[1];
            }
            $user = User::find($request->user_id);
            $user->profile_id = $profileId;
            $user->card_id = $cardId;
            $user->save();
            \Log::info('Successfully added customer card', [
                'user_id' => $user->id,
                'profile_id' => $request->profile_id,
                'card_id' => $cardId,
            ]);
        }

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


    protected function addSquarePaymentDetail($name, $email, $cardNonce)
    {

        $client = new SquareClient([
            'environment' => 'production',
            'accessToken' => get_setting('square_access_token'), // Use your Square access token
        ]);

        $customersApi = $client->getCustomersApi();

        try {
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

            return [$customerId, $cardId];
        } catch (\Square\Exceptions\ApiException $e) {
            \Log::error('Catch exception: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['square_error' => 'Update customer card.'])->withInput();
        }
    }

    protected function updateSquarePayment($customerId, $cardId, $cardNonce)
    {

        $client = new SquareClient([
            'environment' => 'production',
            'accessToken' => get_setting('square_access_token'), // Use your Square access token
        ]);

        $paymentsApi = $client->getPaymentsApi();

        $customersApi = $client->getCustomersApi();

        try {
            $deleteResponse = $client->getCustomersApi()->deleteCustomerCard($customerId, $cardId);

            if (!$deleteResponse->isSuccess()) {
                \Log::error('Failed to delete customer previos card', $deleteResponse->getErrors());
                return response()->json(['error' => 'Failed to delete customer card'], 500);
            }

            // Step 2: Save the Card on File
            $createCustomerCardRequest = new CreateCustomerCardRequest($cardNonce);
            $cardResponse = $customersApi->createCustomerCard($customerId, $createCustomerCardRequest);

            if (!$cardResponse->isSuccess()) {
                \Log::error('Failed to save card on file', $cardResponse->getErrors());
                return response()->json(['error' => 'Failed to save card on file'], 500);
            }

            $cardId = $cardResponse->getResult()->getCard()->getId();


            return $cardId;
        } catch (\Square\Exceptions\ApiException $e) {
            \Log::error('Catch exception: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['square_error' => 'Update customer card.'])->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        $userId =  Auth::guard('admin')->user()->id;

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
        return redirect()->intended('admin/profile')->with('success', 'Password has been changed successfully.');
    }

    public function changeUserPassword(Request $request)
    {
        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->back()->with('success', 'Password has been changed successfully.');
    }
}

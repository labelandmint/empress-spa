<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();


//user Controller
Route::get('/', [UserController::class, 'index']);
Route::get('/admin', [AdminController::class, 'admin']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/admin', [AdminController::class, 'adminLogin']);
Route::get('/register/{sub_id}', [UserController::class, 'register']);
Route::post('/store', [UserController::class, 'store']);

// Route to send the password reset link
Route::post('password/send-link', [UserController::class, 'sendResetLinkEmail']);

// Route to show the form for resetting the password
Route::get('password/confirm/{token}', [UserController::class, 'showResetLinkRequestForm']);

// Route to reset the password
Route::get('password/forgot', [UserController::class, 'reset']);
Route::post('password/confirm', [UserController::class, 'confirm']);

// Route to Change the password
Route::post('/change-password', [UserController::class, 'changePassword']);

Route::get('/images/{filename}', function ($filename) {

    $filePath = 'images/' . $filename;
    if(Storage::disk('public')->exists($filePath))
    {
        return response()->file(Storage::disk('public')->path($filePath));
    }
    elseif( Storage::disk('protected')->exists($filePath) )
    {
            if (Auth::guard('web')->check() || Auth::guard('admin')->check()) {
                return response()->file(Storage::disk('protected')->path($filePath));
            }
            // Return a 403 Forbidden if the user is not authorized
            abort(403, 'Unauthorized access to protected file');
    }
    // If the file exists in the protected directory, apply middleware
    
    // If the file is not found in either location, return 404
    abort(404, 'File not found');
});


Route::group(['middleware' => 'auth'], function () {
    //User Controller
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('bank-detail/store', [UserController::class, 'addBankDetail']);

    Route::get('/logouts', [UserController::class, 'logout']);

    // Transaction Controller
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/download/invoice/{id}', [TransactionController::class, 'downloadInvoice']);


    Route::middleware(['checkUserPaused'])->group(function () {
        // Services Controller
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/slots', [ServiceController::class, 'slots']);

        // Booking Controller
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/booking/store', [BookingController::class, 'store']);
        Route::post('/booking/delete', [BookingController::class, 'destroy']);
    });
    Route::get('/booking/member/{id}', [BookingController::class, 'getMemberBooking']);
    // Subscription Controller
    Route::get('/subscription/cancel/{id}', [SubscriptionController::class, 'cancelSubscription']);
    Route::post('/subscription/pause', [SubscriptionController::class, 'pauseMembership']);
    Route::get('/subscription/resume/{id}', [SubscriptionController::class, 'resumeMembership']);
    Route::get('/subscription/cancel/{id}', [SubscriptionController::class, 'cancelSubscription']);
});

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {

    //Admin Controller
    Route::middleware(['permission:view_members'])->group(function () {
        Route::get('/members', [AdminController::class, 'members']);
        Route::get('/members/export/excel', [AdminController::class, 'exportExcel']);
        Route::get('/members/export/pdf', [AdminController::class, 'exportPDF']);
        Route::middleware('permission:change_password')->post('members/password/change', [AdminController::class, 'changeUserPassword']);

        Route::middleware('permission:edit_members')->get('/members/edit/{id}', [AdminController::class, 'editUser']);
        Route::middleware('permission:edit_members')->post('/members/update', [AdminController::class, 'updateUser']);
        Route::post('/rating/add', [AdminController::class, 'addRating']);
        Route::middleware('permission:payment_information')->post('bank-detail/store', [AdminController::class, 'addBankDetail']);

        Route::middleware('permission:add_user')->post('/add-user', [AdminController::class, 'addUser']);
    });




    Route::get('/logout', [AdminController::class, 'logout']);


    //Profile Controller
    Route::get('/profile', [AdminController::class, 'admin_profile']);
    Route::post('/profile/update', [AdminController::class, 'update_admin_profile']);
    Route::post('/change-password', [AdminController::class, 'changePassword']);

    // Transaction Controller
    Route::middleware(['permission:view_transactions'])->group(function () {
        Route::get('/transactions', [AdminTransactionController::class, 'index']);
        Route::get('/transactions/export/excel', [AdminTransactionController::class, 'exportExcel']);
        Route::get('/transactions/export/pdf', [AdminTransactionController::class, 'exportPDF']);
        Route::get('/transactions/download/invoice/{id}', [AdminTransactionController::class, 'downloadInvoice']);
    });

    // Subscription Controller
    Route::middleware(['permission:view_subscriptions'])->group(function () {
        Route::get('/subscriptions', [AdminSubscriptionController::class, 'index']);

        Route::middleware(['permission:add_subscriptions'])->post('/subscription/plan/store', [AdminSubscriptionController::class, 'store']);

        Route::post('/subscription/pause', [AdminSubscriptionController::class, 'pauseMembership']);
        Route::get('/subscription/resume/{id}', [AdminSubscriptionController::class, 'resumeMembership']);
        Route::get('/subscription/cancel/{id}', [AdminSubscriptionController::class, 'cancelSubscription']);
    });

    // Product Controller
    Route::middleware(['permission:view_products'])->group(function () {
        Route::get('/products', [AdminProductController::class, 'index']);
        Route::middleware(['permission:add_subscriptions'])->post('/product/store', [AdminProductController::class, 'store']);
        Route::get('/product/archive/{id}', [AdminProductController::class, 'archive']);
        Route::get('/product/filter', [AdminProductController::class, 'filter']);
    });

    // services Controller
    Route::middleware(['permission:view_services'])->group(function () {
        Route::get('/services', [AdminServiceController::class, 'index']);
        Route::post('/services/store', [AdminServiceController::class, 'store']);
        Route::get('/services/filter', [AdminServiceController::class, 'filter']);
        Route::get('/services/archive/{id}', [AdminServiceController::class, 'archive']);
        Route::get('services/slots', [AdminServiceController::class, 'slots']);
    });

    // Setting Controller
    Route::get('/settings', [AdminSettingController::class, 'index']);
    Route::post('/store', [AdminSettingController::class, 'store']);

    // BookingController
    Route::get('booking/getById/{id}', [AdminBookingController::class, 'getById']);
    Route::get('bookings/view/{id?}', [AdminBookingController::class, 'memberBooking'])->name('admin.bookings.view');
    Route::get('bookings/user/{id}', [AdminBookingController::class, 'getMemberBooking']);
    Route::post('booking/update', [AdminBookingController::class, 'update']);
});

<?php

use App\Models\User;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProductController;

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


Route::get('login', function () {
    if (session()->has('user')) {
        return redirect(route('home'));
    } else {
        return view('login');
    }
})->name('login');

// Route::get('/', function () {
//     return view('home');
// })->name('home')->middleware('guard');

Route::get('/', [ProductController::class, 'getHome'])->name('home')->middleware('guard');

Route::get('register', function () {
    return view('register');
})->name('register');

Route::get('contactform', [UserController::class, 'getContactPage'])->name('contactform')->middleware('guard');
// Route::get('bookingform', function () {
//     return view('bookingForm');
// })->name('bookingform')->middleware('guard');

Route::get('bookingform/{id}', [ProductController::class, 'getBookingForm'])->name('bookingform')->middleware('guard');



Route::get('addItem', function () {
    return view('addItemForm');
})->middleware('guard');

Route::post('addnewitem', [ProductController::class, 'addItem'])->name('addnewitem');

Route::post('getuser', [UserController::class, 'login'])->name('login.post');
Route::post('newuser', [UserController::class, 'register'])->name('register.post');
Route::post('contact', [UserController::class, 'contact'])->name('contact');
Route::post('booking', [BookingController::class, 'booking'])->name('booking');
Route::post('verify', [UserController::class, 'verifyUser'])->name('verify');

Route::get('validateForm', function () {
    return view('validateForm');
});

Route::get('forget', function () {
    return view('forget');
})->name('forget');

Route::post('forgetpassword', [UserController::class, 'forgetpassword']);

Route::get('forgetValidateForm', function () {
    return view('forgetValidateForm');
});

Route::post('forgetpasswordotp', [UserController::class, 'forgetPasswordOtpVerify'])->name('forgetpasswordotp');

Route::get('changepassword', function () {
    return view('changepassword');
});

Route::post('changingpassword', [UserController::class, 'changePassword'])->name('changingpassword');

Route::get('logout', function () {
    Auth::logout();
    Session::flush();
    return redirect('login');
})->name('logout');

// });

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

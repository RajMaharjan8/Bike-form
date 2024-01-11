<?php

use App\Models\User;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group (['middleware'=>'web'],function(){

    Route::group(['prefix' => 'admin'], function(){
        Voyager::routes();
    });

    Route::get('/login',function(){
        return view('login');
    });

    Route::get('/',function(){
        return view('home');
    });

    Route::get('/register',function(){
        return view('register');
    });
    // Route::get('/contact',function(){
    //     return view('contact');
    // });

    Route::get('/contact', [UserController::class, 'getContactPage']);
    Route::get('/bookingform', function(){
        return view('bookingForm');
    });


    Route::post('/login', [UserController::class,'login']);
    Route::post('/register', [UserController::class,'register']);
    Route::post('/contact',[UserController::class,'contact']);
    Route::post('/booking',[BookingController::class,'booking']);



    // Route::get('/logout', function(){
    //     Session::forget('user');
    //     return redirect('/login');
    // });

    Route::get('/logout', function(){
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }); 

// Route::get('/logout',[UserController::class, 'logout']);



});
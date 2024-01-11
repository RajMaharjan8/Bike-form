<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    //
    public function booking(Request $req){
        try {

            $req->validate([
                'name'=> 'required',
                'email'=> 'required|email',
                'phone'=>'required|min:10',
            ]);
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            Booking::insert([
                'user_id'=> $req->user_id,
                'name'=> $req->name,
                'price'=> $req->price,
                'phone'=> $req->phone,
                'image'=>$imageName
            ]);
            return view('bookingform',[
                'message'=>'Your booking has been successfully done'
            ]);
        } catch (ValidationException $e) {
            return redirect('/bookingform')->withErrors($e->errors());
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An internal error has occurred'
            ], 500);
        }
        // return $req->input();
    }
}

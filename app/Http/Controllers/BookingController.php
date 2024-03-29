<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    //
    public function booking(Request $req)
    {
        try {

            $req->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|min:10|max:10',
            ]);
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/booking/'), $imageName);

            Booking::insert([
                'user_id' => $req->user_id,
                'name' => $req->name,
                'price' => $req->price,
                'phone' => $req->phone,
                'image' => ('booking/' . $imageName),
            ]);

            $id = $req->prod_id;
            $prod_data = Product::find($id);
            // dd($prod_data);
            return view('bookingform', [
                'message' => 'Your booking has been successfully done',
                'data' => $prod_data
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

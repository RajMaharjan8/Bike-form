<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    //
    public function getHome()
    {
        $all_prod = Product::paginate(3);

        return view('home', [
            'data' => $all_prod
        ]);
    }

    public function getBookingForm($id)
    {
        $data = Product::find($id)->toArray();
        return view('bookingForm', [
            'data' => $data
        ]);

        // dd($data);
    }
    public function addItem(Request $req)
    {
        try {
            $req->validate([
                'name' => 'required',
                'price' => 'required',
                'phone' => 'required|min:10|max:10',
            ]);
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/product/'), $imageName);

            Product::create([
                'user_id' => $req->user_id,
                'name' => $req->name,
                'price' => $req->price,
                'description' => $req->description,
                'phone' => $req->phone,
                'image' => ('product/' . $imageName),
            ]);
            return view('addItemForm');
        } catch (ValidationException $e) {
            return redirect('/bookingform')->withErrors($e->errors());
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An internal error has occurred'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    //
    public function login(Request $req){
        try {
            $req->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
    
        $user = User::where(['email'=>$req->email])->first();
        if($user || Hash::check($req->password, $user->password)){
            $req->session()->put('user',$user);
            return redirect('/');
           
        }else{
            return response()->json([
                'message'=>'Email and password doesnt match'
            ]);
        } 
    } catch (ValidationException $e) {
        return redirect('/login')->withErrors($e->errors());
    } catch (\Throwable $th) {
        return response()->json([
            'message' => 'An internal error has occurred'
        ], 500);
    }
        
    }

    public function register(Request $req)
{
    try {
        $req->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'role' => 'required',
            'password' => 'required|confirmed|regex:/[0-9]/',
            'password_confirmation'=>'required'
        ], [
            'password.regex' => 'The password must contain at least one number.'
        ]);

        User::create([
            'name' => $req->name,
            'email' => $req->email,
            'role' => $req->role,
            'password' => Hash::make($req->password)
        ]);
        $user = User::where(['email'=>$req->email])->first();
        $req->session()->put('user',$user);
        return redirect('/');
        
    } catch (ValidationException $e) {
        return redirect('/register')->withErrors($e->errors());
    } catch (\Throwable $th) {
        return response()->json([
            'message' => 'An internal error has occurred'
        ], 500);
    }
}


public function getContactPage(){
    $user = Session::get('user');
    if (!$user) {
        return redirect()->route('login')->with('error', 'Please log in to access the contact page.');
    }

    return view('contact', [
        'user' => $user
    ]);
}

public function contact(Request $req){
    try {
        $req->validate([
            'name' => 'required',
            'phone' => 'required',
            'message' => 'required'
        ]);

        ContactUs::create([
            'user_id' => $req->user_id,
            'name' => $req->name,
            'phone' => $req->phone,
            'message' => $req->message
        ]);

        return view('contact', [
            'message' => 'Your message has been successfully sent'
        ]);
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors());
    } catch (\Throwable $th) {
        return response()->json([
            'message' => 'An internal error has occurred'
        ], 500);
    }
}

}

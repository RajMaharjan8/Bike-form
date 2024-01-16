<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Observers\OptService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Ichtrojan\Otp\Models\Otp as ModelsOtp;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    //
    public function login(Request $req)
    {
        try {
            $req->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where(['email' => $req->email])->first();
            if (($user && Hash::check($req->password, $user->password)) && $user->email_verified_at !== null) {
                $req->session()->put('user', $user);
                return redirect(route('home'));
            } else {
                return response()->json([
                    'message' => 'Email and password doesnt match'
                ]);
            }
        } catch (ValidationException $e) {
            return redirect('/login')->withErrors($e->errors());
        } catch (\Throwable $th) {
            // Log the exception message for debugging
            Log::error($th->getMessage());

            return response()->json([
                'message' => 'An internal error has occurred'
            ], 500);
        }
    }

    // public function register(Request $req)
    // {
    //     try {
    //         $req->validate([
    //             'name' => 'required',
    //             'email' => 'required|unique:users|email',
    //             'role' => 'required',
    //             'password' => 'required|confirmed|regex:/[0-9]/|regex:/[A-Z]/',
    //             'password_confirmation' => 'required'
    //         ], [
    //             'password.regex' => 'The password must contain at least one number and one capital letter'
    //         ]);


    //         $user_create = User::create([
    //             'name' => $req->name,
    //             'email' => $req->email,
    //             'role' => $req->role,
    //             'password' => Hash::make($req->password)
    //         ]);
    //         $verificationToken = Str::random(10);

    //         // Store verification token in the database
    //         $user_create->update(['verification_token' => $verificationToken]);

    //         // Send verification email
    //         $mailsent = Mail::send(
    //             'emails.userRegistered',
    //             ['user' => $user_create, 'verificationToken' => $verificationToken],
    //             function ($message) use ($user_create) {
    //                 $message->to($user_create->email, 'LaravelProject')
    //                     ->subject('Verify Your Email');
    //             }
    //         );
    //         dd($mailsent);

    //         $user = User::where(['email' => $req->email])->first();
    //         $req->session()->put('user', $user);
    //         return redirect('/');
    //     } catch (ValidationException $e) {
    //         return redirect('/register')->withErrors($e->errors());
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'message' => 'An internal error has occurred'
    //         ], 500);
    //         // return $th;
    //     }
    // }


    // public function verifyOtp(Request $request)
    // {
    //     // Validate the OTP

    //     $otp = OTP::where('email', $request->email)
    //         ->where('token', $request->otp)
    //         ->where('validity', '>', now()) // Checking if the OTP is still valid
    //         ->first();

    //     if (!$otp) {
    //         return redirect('/otp-verification')->with('error', 'Invalid OTP. Please try again.');
    //     }

    //     // Updating user's email_verified_at
    //     $user = User::where('email', $request->email)->first();
    //     $user->update(['email_verified_at' => now()]);

    //     // Delete the used OTP
    //     $otp->delete();

    //     return redirect('/register');
    // }



    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'role' => 'required',
                'password' => 'required|confirmed|regex:/[0-9]/',
                'password_confirmation' => 'required',
            ], [
                'password.regex' => 'The password must contain at least one number and one capital letter',
            ]);

            $validator->validate();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            // Generate and store OTP
            // $otp = OTP::create([
            //     'email' => $request->email,
            //     'token' => Str::random(6),
            //     'validity' => now()->addMinutes(15),
            // ]);
            $otp = OptService::generate($user->email, 6, 30);

            Mail::send('emails.display', ['otp' => $otp], function ($mailable) use ($user) {
                $mailable->to($user->email)
                    ->subject('User Verification');
            });

            return view('validateForm', [
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return redirect('/register')->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An internal error has occurred',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function forgetpassword(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
        ]);
        $enter_email = $req->email;
        $user_data = User::where('email', $enter_email)->first();

        if ($user_data) {
            if ($user_data->email_verified_at !== null) {
                $otp = OptService::generate($user_data->email, 6, 30);
                Mail::send('emails.forgetotp', ['otp' => $otp], function ($mailable) use ($user_data) {
                    $mailable->to($user_data->email)
                        ->subject('User Verification');
                });
                return view('forgetValidateForm', [
                    'user' => $user_data
                ]);
            } else {
                return 'not able';
            }
        } else {
            return 'no data';
        }
    }

    public function forgetPasswordOtpVerify(Request $req)
    {
        try {
            $userId = $req->user_id;
            $findUser = User::find($userId);

            if (!$findUser) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $getEmail = $findUser->email;

            // $otp = ModelsOtp::where('identifier', $getEmail)
            //     ->where('valid', 1)
            //     ->first();

            $otp = ModelsOtp::where('identifier', $getEmail)
                ->latest('updated_at')  // Order by updated_at in descending order
                ->first();


            $enteredOtp = $req->input('otp');

            if ($otp && $otp->token === $enteredOtp) {
                $otp = ModelsOtp::find($otp->id);
                $otp->update(['valid' => false]);
                // redirecting to change passowrd page
                return view('changepassword', [
                    'user' => $findUser
                ]);

                // return view('changepassword', ['user' => $findUser]);


                // return view(route('home'));
            } else {
                return response()->json([
                    'message' => 'Invalid OTP',
                ], 422);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An internal error has occurred',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function changePassword(Request $req)
    {
        try {
            $req->validate([
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);

            $password = $req->get('password');
            $confirmPassword = $req->get('password_confirmation');
            $userId = $req->get('user_id');

            $findUser = User::find($userId);
            // if (!Hash::check($password, $findUser->password)) {
            if (!Hash::check($password, $findUser->password)) {
                $findUser->update(['password' => Hash::make($password)]);
                return redirect()->route('login')->with('success', 'Now You can Login using your credentials');
            } else {
                // return redirect()->route('changepassword')->with('error', 'Already have the same password');
                return view('/changepassword', [
                    'user' => $findUser,
                    'error' => 'Already have the same password'
                ]);
            }
        } catch (ValidationException $e) {
            return redirect('/changepassword')->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An internal error has occurred',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function verifyUser(Request $req)
    {
        try {
            $userId = $req->user_id;
            $findUser = User::find($userId);

            if (!$findUser) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $getEmail = $findUser->email;

            $otp = ModelsOtp::where('identifier', $getEmail)
                ->where('valid', 1)
                ->first();

            $enteredOtp = $req->input('otp');

            if ($otp && $otp->token === $enteredOtp) {
                $otp = ModelsOtp::find($otp->id);
                $otp->update(['valid' => false]);
                $findUser->update(['email_verified_at' => now()]);
                // return response()->json([
                //     'message' => 'Email verification successful',
                // ], 200);
                $req->session()->put('user', $findUser);
                return redirect(route('home'));
            } else {
                return response()->json([
                    'message' => 'Invalid OTP',
                ], 422);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An internal error has occurred',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getContactPage()
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access the contact page.');
        }

        return view('contact', [
            'user' => $user
        ]);
    }

    public function contact(Request $req)
    {
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

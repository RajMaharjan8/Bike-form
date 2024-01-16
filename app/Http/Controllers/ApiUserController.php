<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Observers\OptService;
use Ichtrojan\Otp\Models\Otp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiUserController extends Controller
{
    //this is for reistration
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:10', 'confirmed'],
            'password_confirmation' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Failed to meet the registration credentials',
                'errors' => $validator->errors()
            ], 400);
        } else {

            $data = [
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password)
            ];
            DB::beginTransaction();
            try {
                $user = User::create($data);

                $otp = OptService::generate($user->email, 6, 30);

                Mail::send('emails.display', ['otp' => $otp], function ($mailable) use ($user) {
                    $mailable->to($user->email)
                        ->subject('User Verification');
                });
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'message' => 'Internal Server Error',
                    'error' => $e->getMessage()
                ], 500);
            }
            return response()->json([
                'message' => 'OTP Successfully sent'
            ], 200);
        }
    }

    // public function verifyOtpApi(Request $req)
    // {
    //     $validator = Validator::make($req->all(), [
    //         'otp' => ['required'],
    //         'email' => ['required']
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Failed to meet the requirement. Enter otp and email',
    //             'errors' => $validator->errors()
    //         ], 400);
    //     }

    //     $entered_otp = $req->input('otp');
    //     $entered_email = $req->input('email');
    //     $user_otp_data = Otp::where('identifier', $entered_email)
    //         ->where('valid', 1)
    //         ->first();
    //     $user_data = User::where('email', $entered_email)->first();
    //     $stored_otp = $user_otp_data->token;
    //     try {
    //         // if ($entered_otp === $stored_otp ) {
    //         if ($entered_otp === $stored_otp) {

    //             $user_otp_data->update(['valid' => false]);
    //             $user_data->update(['email_verified_at' => now()]);

    //             // $user = User::create($data);
    //             $token = $user_data->createToken('app')->accessToken;
    //             // dd($token->token);
    //             $user_data->update(['remember_token' => $token->token]);
    //             return response()->json(
    //                 [
    //                     'token' => $token->token,
    //                     'message' => 'Your Account Has Been Verified.'
    //                 ]
    //             );
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function verifyOtpApi(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'otp' => ['required'],
            'email' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Failed to meet the requirement. Enter otp and email',
                'errors' => $validator->errors()
            ], 400);
        }

        $entered_otp = $req->input('otp');
        $entered_email = $req->input('email');
        $user_otp_data = Otp::where('identifier', $entered_email)
            ->where('valid', 1)
            ->first();
        $user_data = User::where('email', $entered_email)->first();
        $stored_otp = $user_otp_data->token;

        try {
            if ($entered_otp === $stored_otp && $user_otp_data->valid !== false) {
                $now = now();
                $validity = $user_otp_data->created_at->addSeconds($user_otp_data->validity);
                //false = 0
                //true = 1
                if ($now->greaterThan($validity)) {
                    $user_otp_data->valid = true;
                    $user_otp_data->save();

                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                } else {
                    $user_otp_data->update(['valid' => false]);
                    $user_data->update(['email_verified_at' => now()]);

                    $token = $user_data->createToken('app')->accessToken;

                    $user_data->update(['remember_token' => $token->token]);

                    return response()->json([
                        'token' => $token->token,
                        'message' => 'Your Account Has Been Verified.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP or OTP has already been used.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetch(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => ['required'],
            'id' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Enter the id you want to get and email',
                'errors' => $validator->errors()
            ], 400);
        }
        $id = $req->input('id');
        $user = User::find($id);
        if (is_null($user)) {
            $response = [
                'message' => "User not found",
                'status' => 0,
            ];
        } else {
            $response = [
                'message' => "User found",
                "status" => 1,
                "data" => $user
            ];
        }
        return response()->json($response, 200);
    }

    public function getAllRegistered()
    {
        try {
            $data = User::where('email_verified_at', '!=', null)->get();
            return response()->json(['message' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    function apiLogin(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'message' => 'Failed to meet the login credentials',
                ]);
            }

            $user = User::where('email', $req->email)->where('email_verified_at', '!=', null)->get();

            if (count($user) > 0) {
                $user = $user->first(); // Get the first user from the collection

                if ($user->remember_token !== null) {
                    $response = [
                        'message' => 'You are already logged in to the system',
                    ];
                } else {
                    $token = $user->createToken('app')->accessToken;
                    $user->update(['remember_token' => $token->token]);

                    //putting api token in session for middleware 
                    // Session::put('api_token', $token->token);
                    $response = [
                        'message' => 'You are now logged in',
                        'token' => $token->token,
                        'data' => $user,
                    ];
                }

                return response()->json($response, 200);
            } else {
                $response = [
                    'message' => 'User Not Found or not verified',
                ];
                return response()->json($response, 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function forgetPasswordEmail(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => ['required', 'email']
        ]);
        if (!$validator) {
            return response()->json([
                'message' => 'enter email'
            ], 400);
        } else {
            try {
                $entered_email = $req->input('email');
                $user_data = User::where('email', $entered_email)->where('email_verified_at', '!=', null)->first();
                if ($user_data) {
                    $otp = OptService::generate($user_data->email, 6, 30);
                    Mail::send('emails.forgetotp', ['otp' => $otp], function ($mailable) use ($user_data) {
                        $mailable->to($user_data->email)
                            ->subject('User Verification');
                    });
                    return response()->json([
                        'message' => 'The Otp was successfully sent. Please check your email.'
                    ]);
                } else {
                    return 'Failed to sent Otp.';
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    public function verifyOptForgetPassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'otp' => ['required'],
            'email' => ['required', 'email']

        ]);
        if (!$validator) {
            return response()->json([
                'message' => 'enter your otp'
            ], 400);
        }
        try {

            $entered_otp = $req->input('otp');
            $entered_email = $req->input('email');
            $user_otp_data = Otp::where('identifier', $entered_email)
                ->latest('updated_at')
                ->first();
            $user_data = User::where('email', $entered_email)->first();
            $stored_otp = $user_otp_data->token;
            if ($entered_otp === $stored_otp) {
                $now = now();
                $validity = $user_otp_data->created_at->addSeconds($user_otp_data->validity);
                //false = 0
                //true = 1
                if ($now->greaterThan($validity)) {
                    $user_otp_data->valid = true;
                    $user_otp_data->save();

                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                } else {
                    $user_otp_data->update(['valid' => false]);
                    $user_data->update(['email_verified_at' => now()]);
                    return response()->json([
                        'message' => 'Otp verified. You can now set up your new password.'
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Encorrect Otp. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'new_password' => ['required', 'confirmed'],
            'confirm_password' => ['required'],
            'email' => ['required'],
        ]);
        if (!$validator) {
            return response()->json([
                'message' => 'enter your new_password and confirm_password otp'
            ], 400);
        }

        try {
            $new_password = $req->input('new_password');
            $confirm_password = $req->input('confirm_password');
            $entered_email = $req->input('email');
            $user_data = User::where('email', $entered_email)->first();
            $user_otp_data = Otp::where('identifier', $entered_email)
                ->first();

            if (!Hash::check($new_password, $user_data->password) && $user_otp_data->valid === 0) {
                $user_data->update(['password' => Hash::make($new_password)]);
                return response()->json([
                    'message' => 'Your password has been successfully changed.'
                ]);
            } else {
                return response()->json([
                    'message' => 'Fail to change your password.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function apiLogout(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => ['required'],
        ]);
        if (!$validator) {
            return response()->json([
                'message' => 'enter your email'
            ], 400);
        }

        $entered_email = $req->input('email');
        $user_data = $user_data = User::where('email', $entered_email)->first();
        try {
            if ($user_data->remember_token != null) {
                $user_data->update(['remember_token' => null]);
                // Session::flush();
                return response()->json([
                    'message' => 'You have successfully been logged out of the system.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

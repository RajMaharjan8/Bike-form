<?php

namespace App\Observers;

use Carbon\Carbon;
use Ichtrojan\Otp\Models\Otp;
use Illuminate\Database\Eloquent\Model;

class OptService
{
    public static function generate(string $email, int $digits = 4, int $validity = 1440): object
    {
        Otp::where('identifier', $email)->where('valid', 0)->delete();

        $token = str_pad(self::generatePin(), 4, '0', STR_PAD_LEFT);

        if ($digits == 5)
            $token = str_pad(self::generatePin(5), 5, '0', STR_PAD_LEFT);

        if ($digits == 6)
            $token = str_pad(self::generatePin(6), 6, '0', STR_PAD_LEFT);


        // dd($identifier);

        Otp::create([
            'identifier' => $email,
            'token' => $token,
            'validity' => $validity,
        ]);

        return (object)[
            'status' => true,
            'token' => $token,
            'message' => 'OTP generated'
        ];
    }

    /**
     * @param string $identifier
     * @param string $token
     * @return mixed
     */

    public static function validate(string $email, string $token, $change = true): object
    {
        $otp = Otp::where('identifier', $email)->where('token', $token)->first();


        if ($otp == null) {
            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        } else {
            if ($otp->valid == true) {
                $carbon = new Carbon();
                $now = $carbon->now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                if (strtotime($validity) < strtotime($now)) {
                    $otp->valid = false;
                    $otp->save();

                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                } else {
                    if ($change) {
                        $otp->valid = false;
                        $otp->save();
                    }
                    return (object)[
                        'status' => true,
                        'message' => 'OTP is valid'
                    ];
                }
            } else {
                return (object)[
                    'status' => false,
                    'message' => 'OTP is not valid'
                ];
            }
        }
    }

    /**
     * @param int $digits
     * @return string
     */
    private static function generatePin($digits = 4)
    {
        $i = 0;
        $pin = "";

        while ($i < $digits) {
            $pin .= rand(0, 9);
            $i++;
        }

        return $pin;
    }
}

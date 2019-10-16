<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\EmailVerifiy;
use App\mail\EmailVerificationToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{

    /**
     * Show unverified email page message
     * 
     * @return View
     */
    public function emailUnverified() {

        $user = Auth::user();

        if($user->email_verified_at != null) {
            return redirect(route('dashboard.index'));
        }

        return view('pages.auth.emailVerification.emailUnverified');
    }


    /**
     * Send email verification link
     * 
     * @return View
     */
    public function sendToken () {

        $user = Auth::user();

        if($user->email_verified_at != null) {
            return redirect(route('dashboard.index'));
        }

        // Begin generate token and store it
        $token          = str_random(60);
        $emailVerify    = new EmailVerifiy();

        $emailVerify->email       = $user->email;
        $emailVerify->token       = $token;

        $emailVerify->save();
        // End generate token and store it


        // Begin create reset password link and send email
        $emailVeririficationLink = route('user.verifyEmail', [
            'token' => $token
        ]);

        Mail::to($user)->send(new EmailVerificationToken($emailVeririficationLink));
        
        return view('pages.auth.emailVerification.tokenSent');
        // End create reset password link and send email

    }


    /**
     * Verify email
     * 
     * @param String $token
     * @return View 
     */
    public function verify ($token) {

        $user           = Auth::user();
        $errors         = [];
        $data['token']  = $token;

        if($user->email_verified_at != null) {
            return redirect(route('dashboard.index'));
        }

        // Begin validate token
        $validationResult = Validator::make($data, [
            'token' => ['required', 'string', 'size:60']
        ]);
        
        if($validationResult->fails() == true) {
            $errors[] = 'Invalid token !';
        }
        // End validate token

        
        // Begin check token exist
        $emailVerify = EmailVerifiy::where([
                                                'email' => $user->email,
                                                'token' => $token
                                            ])
                                            ->first();

        if($emailVerify == null) {
            $errors[] = 'Token is not exist !';
        }
        // End check token exist


        // Begin verify user email
        if(count($errors) == 0) {
            $user->email_verified_at = Carbon::now();
            $user->save();

            $emailVerify->delete();
        }
        // End verfify user emaill

        return view('pages.auth.emailVerification.verify', [
                                                    'errors'    => $errors
                                                ]);

    }

}

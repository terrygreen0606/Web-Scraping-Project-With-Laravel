<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\User;
use App\PasswordReset;
use App\Mail\ResetPasswordToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class PasswordController extends Controller
{
    
    /**
     * Show forgot password form
     * 
     * @return View
     */
    public function index () {
        
        return view('pages.auth.passwords.forgotPassword');

    }


    /**
     * Show email has been sent page
     * 
     * @return View
     */
    public function showEmailSent () {

        return view('pages.auth.passwords.tokenSent');

    }


    /**
     * Show set new password form
     * 
     * @return View
     */
    public function resetPasswordForm ($token = '') {

        $errors = [];
        $data['token']   = $token;

        $validationResult = Validator::make($data, [
            'token' => ['required', 'string', 'size:60']
        ]);
        
        if($validationResult->fails() == true) {
            $errors[] = 'Invalid token !';
        }

        
        // Begin check token exist
        $resetPasswordToken = PasswordReset::where([
                                                    'token' => $token
                                                    ])
                                                    ->first();

        if($resetPasswordToken == null) {
            $errors[] = 'Token is not exist !';
        }
        // End check token exist

        return view('pages.auth.passwords.resetPassword', [
                                                        'errors'    => $errors,
                                                        'token'     => $token
                                                    ]);

    }


    /**
     * Generate reset password token and send email
     * 
     * @return Json
     */
    public function sendToken (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'email' => ['required', 'string', 'email'],
        ]);
                
        if($validationResult->fails() == true) {

            $result['success']  = false;

            $messages = $validationResult->errors();
            $messages = $messages->messages();

            foreach ($messages as $key => $value) {
                $result['messages'][] = $value;
            }

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End validate request


        // Begin get and check user email
        $email  = $request->input('email');
        $user   = User::where([
                                'email' => $email
                        ])
                        ->first();

        if ($user == null) {

            $result['success']      = false;
            $result['messages'][]   = array('Your email address is not exist !');

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get and check user email


        // Begin generate token and store it
        $token          = str_random(60);
        $passwordReset  = new PasswordReset();

        $passwordReset->email       = $request->input('email');
        $passwordReset->token       = $token;
        $passwordReset->created_at  = Carbon::now();

        $passwordReset->save();
        // End generate token and store it


        // Begin create reset password link and send email
        $resetPasswordLink = route('form.resetPassword', [
            'token' => $token
        ]);

        $emailResult = Mail::to($user)->send(new ResetPasswordToken($resetPasswordLink));
        
        $result = array (
            'success'   => true,
            'messages'  => array('Reset passowrd link has been sent to your email.'),
            'data'      => array(
                                    'action'    => 'redirect',
                                    'url'       => route('user.forgotPasswordTokenSent')
                                )
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End create reset password link and send email

    }


    /**
     * Set new password with token
     * 
     * 
     * @return JSON
     */
    public function setNewPassword (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );

        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'reset_password_token'  => ['required', 'string', 'size:60']
        ]);
                
        if($validationResult->fails() == true) {

            $result['success']  = false;

            $messages = $validationResult->errors();
            $messages = $messages->messages();

            foreach ($messages as $key => $value) {
                $result['messages'][] = $value;
            }

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End validate request


        // Begin get and check password token
        $token          = $request->input('reset_password_token');
        $passwordReset  = PasswordReset::where([
                                                    'token' => $token
                                                ])
                                                ->first();

        if ($passwordReset == null) {

            $result['success']      = false;
            $result['messages'][]   = array('Your reset password token is invalid !');

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get and check password token


        // Begin get and check user
        $user = User::where([
                                'email' => $passwordReset->email
                            ])
                            ->first();

        if ($user == null) {

            $result['success']      = false;
            $result['messages'][]   = array('Your reset password token is invalid !');

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get and check user


        // Begin save password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        Auth::attempt([
                        'email'     => $user->email, 
                        'password'  => $request->input('password')
                    ]);

        PasswordReset::where([
                                'token' => $token
                            ])
                            ->delete();

        $result = array (
            'success'   => true,
            'messages'  => array('Your password updated successfully, You will redirec to your dashboard ...'),
            'data'      => array(
                                    'action'    => 'redirect',
                                    'url'       => route('dashboard.index')
                                )
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End save password

    }


    /**
     * Show change password page
     * 
     * @return View
     */
    public function changePasswordForm () {

        return view('pages.clientarea.changePassword', [
            'user'          => Auth::user(),
            'breadcrumb'    => [
                'Home'              => route('dashboard.index'),
                'Change Password'   => ''
            ]
        ]);

    }


    /**
     * Change password
     * 
     * @return JSON
     */
    public function changePassword (Request $request) {

        $result = array(
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $data   = $request->input();


        // Begin validate request
        $validationResult = Validator::make($data, [
            'user_id'       => ['required', 'string', 'uuid'],
            'old_password'  => ['required', 'string', 'min:8'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        // End validate request

        
        // Begin check validation result
        if($validationResult->fails() == true) {

            $result['success']  = false;

            $messages = $validationResult->errors();
            $messages = $messages->messages();

            foreach ($messages as $key => $value) {
                $result['messages'][] = $value;
            }


            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check validation result


        // Begin get and check user
        $user = Auth::user();
        if ($user->uuid != $request->input('user_id')) {
            
            $result['success']  = false;
            $result['messages'] = ['User is not exist !'];
            $result['data']     = [];

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get and check user


        // Begin check old password
        // $hashedPassword = User::where(['uuid', $request->input('user_id')])->first()->password;
        if(Hash::check($request->input('old_password'), $user->password) == false) {

            $result['success']  = false;
            $result['messages'] = ['Your current password is incorrect !'];
            $result['data']     = [];

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check old password


        // Begin save new password
        $user->password = Hash::make($request->input('password'));
        $user->save();
        // End save new password


        $result['success']  = true;
        $result['messages'] = ['Your password has been updated successfully'];
        $result['data']     = [];

        $response = response(
                    json_encode($result),
                    200
                )
                ->header('Content-Type', 'application/json');

        return $response;

    }

}

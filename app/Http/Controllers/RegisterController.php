<?php

namespace App\Http\Controllers;


use App\User;
use App\EmailVerifiy;
use App\Mail\EmailVerificationToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Hamidjavadi\guid;

class RegisterController extends Controller
{
    
    /**
     * Display user registeration form
     * 
     * @return View
     */
    public function index() {
        return view('pages.auth.register');
    }


    /**
     * Register new user
     * 
     * @param Illuminate\Http\Request $request
     * @return Json
     */
    public function register(Request $request) 
    {

        $inputs = $request->input();
        $result = $this->validateRequest($inputs);
        
        
        // Begin check validation result
        if($result['success'] == false) {

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check validation result

        
        // Begin add new user
        $registerUserResult = $this->registerUser($inputs['name'], $inputs['family'], $inputs['email'], $inputs['password']);

        $response = response(
                        json_encode($registerUserResult),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End add new user
        
    }


    /**
     * 
     * Validate the request
     * 
     * @param Array $data
     * @return Array ['success', 'data']
     * 
     */
    private function validateRequest(array $data) {

        $result     = array(
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $validationResult = Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'family'    => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
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

        } else {

            $result['success']  = true;

        }
        // End check validation result

        return $result;

    }

    
    /**
     * Add new user to database
     * 
     * @param String $name
     * @param String $family
     * @param String $email
     * @param String $password
     * 
     * @return Boolean
     */
    private function registerUser($name, $family, $email, $password) 
    {
        
        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin add new user
        $user = User::create([
            'email'     => $email,
            'family'    => $family,
            'name'      => $name,
            'password'  => Hash::make($password),
            'rule_id'   => 1,
            'status_id' => 1,
            'uuid'      => guid::generate()
        ]);
        // End add new user


        $credentials = [
            'email'     => $email,
            'password'  => $password,
        ];

        if (Auth::attempt($credentials)) {

            // Begin generate token, store it and send mail
            $token          = str_random(60);
            $emailVerify    = new EmailVerifiy();

            $emailVerify->email       = $user->email;
            $emailVerify->token       = $token;

            $emailVerify->save();

            $emailVeririficationLink = route('user.verifyEmail', [
                'token' => $token
            ]);

            Mail::to($user)->send(new EmailVerificationToken($emailVeririficationLink));
            // End generate token, store it and send mail

            $result['success']      = true;
            $result['data']         = array(
                                                'action'    => 'redirect',
                                                'url'       => route('dashboard.index')
                                            );
            $result['messages'][]   = array('You are redirecting to your dashboard ...');
            
        }

        return $result;

    }
    
}

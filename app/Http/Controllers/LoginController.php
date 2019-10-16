<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    /**
     * Display user login form
     * 
     * @return View
     */
    public function index () {
        return view('pages.auth.login');
    }


    /**
     * Login user to application
     * 
     * @param Illuminate\Http\Request $request
     * @return String 
     * 
     */
    public function login (Request $request) {

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

        
        // Begin user login
        $userLoginResult = $this->loginUser($inputs['email'], $inputs['password'], $inputs['remember']);

        $response = response(
                        json_encode($userLoginResult),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End user login

    }


    /**
     * Validate the request
     * 
     * @param Array $data
     * @return Array ['success', 'data']
     */
    private function validateRequest(array $data) {

        $result     = array(
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        if ($data['remember'] == 'true') {
            $data['remember'] = true;
        } else {
            $data['remember'] = false;
        }


        // Begin validate request
        $validationResult = Validator::make($data, [
            'email'     => ['required', 'string', 'email'],
            'password'  => ['required', 'string'],
            'remember'  => ['boolean'],
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
    private function loginUser($email, $password, $remember) 
    {
        
        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );

        $credentials = [
            'email'     => $email,
            'password'  => $password,
        ];

        if (Auth::attempt($credentials, $remember) == true) {

            $result['success']      = true;
            $result['data']         = array(
                                                'action'    => 'redirect',
                                                'url'       => route('dashboard.index')
                                            );
            $result['messages'][]   = array('You will redirect to your dashboard ...');
            
        } else {

            $result['success']      = false;
            $result['messages'][]   = array('Your email or password are incorrect !');

        }

        return $result;

    }

}
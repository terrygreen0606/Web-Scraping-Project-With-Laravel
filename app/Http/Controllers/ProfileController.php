<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    
    /**
     * Show update profile form
     * 
     * @return View
     */
    public function index () {

        return view('pages.clientarea.profile', [
            'user'          => Auth::user(),
            'breadcrumb'    => [
                                    'Home'      => route('dashboard.index'),
                                    'Profile'   => ''
                                ]
        ]);

    }


    /**
     * Update user profile
     * 
     * @return JSON
     */
    public function update (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user   = Auth::user();

        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'name'      => ['required', 'string', 'max:64'],
            'family'    => ['required', 'string', 'max:64'],
            'user_id'   => ['required', 'string', 'uuid']
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


        // Begin get and check user uuid
        if ($user->uuid != $request->input('user_id')) {

            $result['success']      = false;
            $result['messages'][]   = array('User is not exist !');

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get and check user uuid


       // Begin update user data
       $user->name      = $request->input('name');
       $user->family    = $request->input('family');

       $user->update();
       // End update user data
        
        $result = array (
            'success'   => true,
            'messages'  => array('Your profile updated successfully'),
            'data'      => []
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End create reset password link and send email

    }

}

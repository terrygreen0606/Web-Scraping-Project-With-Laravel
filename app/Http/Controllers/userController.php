<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    

    /**
     * Update left sidebar status
     * 
     * @param Request $request
     * @return JOSN
     */
    public function updateSidebarStatus (Request $request) {

        $result = array(
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user       = Auth::user();

        
        // Begin check inputs
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'user_id'   => ['required', 'string', 'uuid'],
            'status'    => ['required', 'string'],
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
        // End check inputs

        
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


        // Begin update sidebar status
        $user->left_sidebar_status = $request->input('status');
        $user->save();
        // End update sidebar status


        // Begin return response
        $result['success']      = true;
        $result['messages'][]   = 'The left sidebar status has been updated.';

        $response = response(
                    json_encode($result),
                    200
                )
                ->header('Content-Type', 'application/json');

        return $response;
        // End return response

    }


}

<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    
    public function index (Request $request) {
    
        Auth::logout();

        if($request->ajax() == false) {

            return redirect(route('form.login'));

        } else {

            $result = array (
                'success'   => true,
                'messages'  => array('You are logged out successfully ...'),
                'data'      => array(
                                        'action'    => 'redirect',
                                        'url'       => route('form.login')
                                    )
            );

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        
    }

}

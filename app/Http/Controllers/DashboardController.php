<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    /**
     * Display dashboard home page
     * 
     * @return View
     */
    public function index() {

        $user = Auth::user();
        return view('pages.clientarea.dashborad', [
            'user'          => $user,
            'breadcrumb'    => [
                                    'Home'      => ''
                                ]
        ]);
        
    }

}

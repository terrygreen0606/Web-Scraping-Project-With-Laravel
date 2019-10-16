<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DropboxController as Dropbox;
use App\Http\Controllers\OneHourIndexingController as OHIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class TestController extends Controller
{
    
    use SendsPasswordResetEmails;

    public function index() {

        $user = Auth::user();

        return $user;

    }

    public function sendForgotPasswordMail (Request $request) {

        $response = $this->broker()->sendResetLink(
                                                        $this->credentials($request)
                                                    );

        dd($response);

    }

    public function logout() {
        return view('logout');
    }


    public function CreateOHIBatch () {

        $OHI = new OHIController('0f666550-ee36-4111-a2e0-3f937b195959', '8e5c131c-252b-4984-be02-e3c3e1cb7344');

        $batchId = $OHI->createBatch('Test Batch', []);
        return $batchId;

    }


    public function OHIBatchAddLinks () {

        $OHI        = new OHIController('0f666550-ee36-4111-a2e0-3f937b195959', '8e5c131c-252b-4984-be02-e3c3e1cb7344');
        $batchId    = '2b7642a2-1f38-4fc1-996b-2b25bd8f5c29';
        
        $links      = [
            'https://google.com',
            'https://youtube.com',
        ];

        $links2 = [
            'https://yahoo.com',
            'https://facebook.com',
        ];

        $OHI->uploadLinks($batchId, $links2, 10);

    }


    public function DropboxDeleteFile () {

        $file = '/campaigns/Tier 1 CSV/Hamid Test Campaign 20 Tier 1.csv';
        
    }

}

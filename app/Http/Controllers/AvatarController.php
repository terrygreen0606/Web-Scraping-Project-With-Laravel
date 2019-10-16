<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as ImageManager;


class AvatarController extends Controller
{
 
    /**
     * Show change avatar form
     * 
     * @return View
     */
    public function index () {
        return view('pages.clientarea.changeAvatar', [
            'user'          => Auth::user(),
            'breadcrumb'    => [
                'Home'          => route('dashboard.index'),
                'Update Avatar' => ''
            ]
        ]);
    }


    public function changeAvatar (Request $request) {

        $result = array(
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user       = Auth::user();


        // Begin check file
        if($request->hasFile('image') == false) {

            $result['success']      = false;
            $result['messages'][]   = 'Please send avatar file !';

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;
        }
        // End check file


        // Begin validate file
        $files = $request->file();
        $validationResult = Validator::make($files, [
            'image'   => ['required', 'image', 'mimes:jpeg,png,jpg','max:10240'],
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
        // End validate file


        // Begin check inputs
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'user_id'   => ['required', 'string', 'uuid'],
            'crop'      => ['required', 'array']
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


        // Begin check and create avatars dirctory
        $storagePath = Storage::disk('public')->path('avatars/');

        if(File::isDirectory($storagePath) == false) {
            Storage::disk('public')->makeDirectory('avatars');
        }
        // End check and create avatars dirctory


        // Begin store image
        $cropCoordinates    = $request->input('crop');
        $image              = $request->file('image');
        $storagePath        = Storage::disk('public')->path('/avatars/');
        $extenstion         = $image->extension();
        $imageName          = 'avatar-' . $request->input('user_id') . '.' . $extenstion;
        $imagePath          = $storagePath . $imageName;

        ImageManager::make($image->getRealPath())
                            ->crop($cropCoordinates['w'], $cropCoordinates['h'], $cropCoordinates['x'], $cropCoordinates['y'])
                            ->resize(512, 512)
                            ->save($imagePath);
        
        $imageUrl           = asset('storage/avatars/'. $imageName);
        // End store image


        // Begin check old avatar and delete it and save new avatar
        if($user->avatar != $imageName) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $imageName;
        $user->save();
        // End check old avatar and delete it and save new avatar


        $result['success']      = true;
        $result['messages'][]   = 'Your avatar has been updated';
        $result['data']         = [
            'imageUrl'  => $imageUrl
        ];

        $response = response(
                    json_encode($result),
                    200
                )
                ->header('Content-Type', 'application/json');

        return $response;

    }

}

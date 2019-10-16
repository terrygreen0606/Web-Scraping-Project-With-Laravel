<?php

namespace App\Http\Controllers;

use App\Category;
use App\Image;
use Hamidjavadi\guid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as ImageManager;

class ImageController extends Controller
{
    
    /**
     * Show upload image form
     * 
     * @return View
     */
    public function showUploadForm () {

        return view ('pages.clientarea.uploadImage', [
                                                        'user'       => Auth::user(),
                                                        'breadcrumb' =>  [
                                                            'Home'          => route('dashboard.index'),
                                                            'Upload image'  => ''
                                                        ]
                                                    ]);

    }


    /**
     * Show image list page
     * 
     * @return View
     */
    public function showImageList () {

        $user = Auth::user();

        return view ('pages.clientarea.imageList', [
                                                        'user'       => $user,
                                                        'categories' => $user->Categories,
                                                        'breadcrumb' =>  [
                                                            'Home'      => route('dashboard.index'),
                                                            'Images'    => ''
                                                        ]
                                                    ]);

    }

    
    /**
     * Upload an image
     * 
     * @return JSON
     */
    public function uploadImage (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user   = Auth::user();


        // Begin check file
        if($request->hasFile('image') == false) {

            $result['success']      = false;
            $result['messages'][]   = 'Please send image !';

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


        // Begin check and create images dirctory
        $storagePath = Storage::disk('public')->path('images/');

        if(File::isDirectory($storagePath) == false) {
            Storage::disk('public')->makeDirectory('images');
        }
        // End check and create images dirctory


        // Begin store image into storage
        $imageUUID          = guid::generate();
        $image              = $request->file('image');
        $storagePath        = Storage::disk('public')->path('images/');
        $extenstion         = $image->extension();
        $imageName          = $user->uuid . '-' . $imageUUID . '.' . $extenstion;
        $imagePath          = $storagePath . $imageName;
        $imageUrl           = 'images/' . $imageName;


        $image = ImageManager::make($image->getRealPath());
        $image->save($imagePath);
        // End store image into storage
        

        // Begin create and store image thumbnail
        $width      = $image->width();
        $height     = $image->height();
        $thumbnail  = null;

        if($width >= $height) {
           
            // $thumbnail = $image->resize(null, 320, function ($constraint) {
            //     $constraint->aspectRatio();
            // });

            $thumbnail = $image->resizeCanvas($width, $width);

        } else {
            
            $thumbnail = $image->resizeCanvas($height, $height);

            // $thumbnail = $image->resize(320, null, function ($constraint) {
            //     $constraint->aspectRatio();
            // });

        }

        $thumbnail      = $thumbnail->resize(320, 320);

        $thumbnailName  = $user->uuid . '-' . $imageUUID . '-thumbnail.' . $extenstion;
        $thumbnailPath  = $storagePath . $thumbnailName;
        $thumbnailUrl   = 'images/' . $thumbnailName;

        $thumbnail->save($thumbnailPath);
        // End create and store image thumbnail

        
        // Begin store image into database
        Image::create([
            'file'      => $imageUrl,
            'user_id'   => $user->id,
            'uuid'      => $imageUUID,
            'thumbnail' => $thumbnailUrl
        ]);
        // End store image into database


        $result['success'] = true;
        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;

    }


    /**
     * Get images
     * 
     * @return JSON
     */
    public function getImages (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user   = Auth::user();


        // Begin check inputs
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'page'      => ['required', 'integer'],
            'count'     => ['required', 'integer'],
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


        // Begin get images
        $offset     = $request->input('page') * $request->input('count');
        $allImages  = Image::where([
                                'user_id'   => $user->id,
                            ])
                            ->get();
        $total      = $allImages->count();
        $images     =  Image::where([
                                        'user_id'   => $user->id,
                                    ])
                                    ->offset($offset)
                                    ->limit($request->input('count'))
                                    ->get();

        $imageList = [];
        foreach ($images as $key => $image) {
            
            $imageList [] = array(
                'category'      => $image->Category,
                'description'   => $image->description,
                'title'         => $image->title,
                'url'           => asset('storage/' . $image->thumbnail),
                'uuid'          => $image->uuid,
            );

        }
        // End get images
        

        $result['success']  = true;
        $result['data']     = [
                                    'images'    => $imageList,
                                    'total'     => $total
                                ];

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;

    }



    /**
     * Remove Image
     * 
     * 
     */
    public function removeImage (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user   = Auth::user();


        // Begin check inputs
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'image_id'   => ['required', 'string', 'uuid'],
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


        // Begin get and check user
        $image = Image::where([
                                'uuid'      => $request->input('image_id'),
                                'user_id'   => $user->id
                            ])->first();

        if($image == null) {

            $result['success']      = false;
            $result['messages'][]   = array('Image is not exist !');

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get and check user
        
        
        // Begin remove image
        Storage::disk('public')->delete($image->file);
        Storage::disk('public')->delete($image->thumbnail);
        $image->delete();
        // End remove image
        

        $result['success']      = true;
        $result['messages'][]   = 'Image has been removed successfully.';

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;

    }

}

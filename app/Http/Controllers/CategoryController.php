<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Hamidjavadi\guid;

class CategoryController extends Controller
{
    
    /**
     * Show category list and add a new category form
     * 
     * @return View
     */
    public function index () {

        $user = Auth::user();

        return view('pages.clientarea.categories', [
            'breadcrumb'    => [
                'Home'          => route('dashboard.index'),
                'Manage Categories' => ''
            ],
            'categories'    => $user->categories,
            'user'          => $user,
        ]);

    }


    /**
     * Add a new category
     * 
     * @return JSON
     */
    public function addCategory (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user = Auth::user();


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'user_id'           => ['required', 'string', 'uuid'],
            'category_title'    => ['required', 'string', 'min:3', 'max:60']
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


        // Begin check user id
        if($user->uuid != $request->input('user_id')) {

            $result['success']  = false;
            $result['messages'][] = "User is not exist !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check user id


        // Begin check category is duplicate
        $isDuplicate = Category::where([
                                            'title'     => $request->input('category_title'),
                                            'user_id'   => $user->id,
                                        ])
                                ->get()
                                ->count();

        if($isDuplicate > 0) {

            $result['success']      = false;
            $result['messages'][]   = "This category exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check category is duplicate


        // Begin save new category
        $newCategory = Category::create([
                                'title'     => $request->input('category_title'),
                                'user_id'   => $user->id,
                                'uuid'      => guid::generate()
                            ]);
        // End save new category


        $result = array (
            'success'   => true,
            'messages'  => array('New category added successfully'),
            'data'      => array(
                                    'action'    => 'addNewCategory',
                                    'category'  => $newCategory
                                )
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;

    }


    /**
     * Delete a category
     * 
     * @return JOSN
     */
    // TODO: Check images and templates
    public function deleteCategory(Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user = Auth::user();


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'user_id'           => ['required', 'string', 'uuid'],
            'category_id'    => ['required', 'string', 'uuid']
        ]);
                
        if ($validationResult->fails() == true) {

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


        // Begin get check user
        if ($user->uuid != $request->input('user_id')) {

            $result['success']  = false;
            $result['messages'][] = 'User not exists';

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get check user


        // Begin check category exist
        $category = Category::where([
                                    'user_id'   => $user->id,
                                    'uuid'  => $request->input('category_id')
                                ])
                                ->first();

        if ($category->count() == 0) {

            $result['success']  = false;
            $result['messages'][] = 'Category not exists';

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check category exist


        // Begin delete category
        $category->delete();

        $result['success']      = true;
        $result['messages'][]   = 'Categery has been deleted';
        $result['data']         = [];

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;

    }


    /**
     * Update a category
     * 
     * @return JOSN
     */
    public function updateCategory(Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );
        $user = Auth::user();


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'user_id'           => ['required', 'string', 'uuid'],
            'category_id'       => ['required', 'string', 'uuid'],
            'category_title'    => ['required', 'string', 'min:3'],
        ]);
                
        if ($validationResult->fails() == true) {

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


        // Begin get check user
        if ($user->uuid != $request->input('user_id')) {

            $result['success']  = false;
            $result['messages'][] = 'User not exists';

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End get check user


        // Begin check category exist
        $category = Category::where([
                                    'user_id'   => $user->id,
                                    'uuid'      => $request->input('category_id')
                                ])
                                ->first();

        if ($category->count() == 0) {

            $result['success']  = false;
            $result['messages'][] = 'Category not exists';

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check category exist


        // Begin update category
        $category->title = $request->input('category_title');
        $category->save();

        $result['success']      = true;
        $result['messages'][]   = 'Categery has been updated';
        $result['data']         = [];

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;

    }

}

<?php

namespace App\Http\Controllers;

use App\Provider;
use App\Tier1;
use App\Tier2;
use Illuminate\Http\Request;
use Hamidjavadi\guid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_decode;

class ProviderController extends Controller
{


    /**
     * Display a form to add a new client.
     *
     * @return View
     */
    public function index()
    {

        return view('pages.clientarea.providers', [
            'user'          => Auth::user(),
            'clients'       => $this->getClients(),
            'breadcrumb'    => [
                'Home'          => route('dashboard.index'),
                'Providers'       => ''
            ]
        ]);

    }


    /**
     * Get client list.
     *
     * @return Array
     */
    private function getClients () {

        $clients = Provider::orderby('id', 'asc')->get();
        $clients = json_decode(json_encode($clients, true), true);

        return $clients;

    }


    /**
     * Add a new client.
     *
     * @param Request $request
     * @return JSON
     */
    public function addNewProvider (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'client_name'    => ['required', 'string', 'min:3', 'max:60']
        ]);

        if($validationResult->fails() == true) {

            $result['success']  = false;

            $messages = $validationResult->errors();
            $messages = $messages->messages();

            foreach ($messages as $key => $value) {
                $result['messages'][] = $value[0];
            }

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End validate request


        // Begin check client is duplicate
        $isDuplicate = Provider::where([
                                            'title' => $request->input('client_name'),
                                        ])
                                ->get()
                                ->count();

        if($isDuplicate > 0) {

            $result['success']      = false;
            $result['messages'][]   = "Provider exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check client is duplicate


        // Begin create new client
        $newClient = Provider::create([
                                'title'     => $request->input('client_name'),
                                'uuid'      => guid::generate()
                            ]);
        // End create new client


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('New provider added successfully'),
            'data'      => array(
                                    'client'  => $newClient
                                )
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End return response

    }


    /**
     * Remove a client.
     *
     * @param Request $request
     * @return JSON
     */
    public function removeProvider (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'client_id'    => ['required', 'string', 'uuid']
        ]);

        if($validationResult->fails() == true) {

            $result['success']  = false;

            $messages = $validationResult->errors();
            $messages = $messages->messages();

            foreach ($messages as $key => $value) {
                $result['messages'][] = $value[0];
            }

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End validate request


        // Begin check client exists
        $exists = Provider::where([
                                    'uuid' => $request->input('client_id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Provider does not exist !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check client exists


        // TODO: Remove all things belongs to the client
        // Begin remove client

        Provider::where('uuid', $request->input('client_id'))->delete();
        $tier1_link = Tier1::where('provider_id', $request->input('client_id'))->value('tier1_link');
        Tier1::where('provider_id', $request->input('client_id'))->delete();
        
        $tier1_link_exists = Tier1::where([
                                            'tier1_link' => $tier1_link,
                                        ])
                                        ->get()
                                        ->count();
        if($tier1_link_exists == 0){
            Tier2::where('tier1_link_id', $tier1_link)->delete();
            Tier2::where('provider_id', $request->input('client_id'))->delete();
        }else{
            Tier2::where('provider_id', $request->input('client_id'))->delete();
        }

        // End remove client


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Provider has been removed successfully'),
            'data'      => array(
                                    'client_id' => $request->input('client_id')
                                )
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End return response

    }


    /**
     * Update a client.
     *
     * @param Request $request
     * @return JSON
     */
    public function updateProvider (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'client_id'     => ['required', 'string', 'uuid'],
            'client_name'   => ['required', 'string', 'min:3', 'max:64']
        ]);

        if($validationResult->fails() == true) {

            $result['success']  = false;

            $messages = $validationResult->errors();
            $messages = $messages->messages();

            foreach ($messages as $key => $value) {
                $result['messages'][] = $value[0];
            }

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End validate request


        // Begin check client exists
        $exists = Provider::where([
                                    'uuid' => $request->input('client_id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Provider does not exist !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check client exists


        // Begin update client
        Provider::where('uuid', $request->input('client_id'))
                    ->update([
                                'title' => $request->input('client_name')
                            ]);
        // End update client


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Provider has been updated successfully'),
            'data'      => array(
                                    'client_id' => $request->input('client_id'),
                                    'client_name' => $request->input('client_name')
                                )
        );

        $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

        return $response;
        // End return response

    }

}

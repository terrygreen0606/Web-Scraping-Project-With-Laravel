<?php

namespace App\Http\Controllers;

use App\Client;
use App\Tier1;
use App\Tier2;
use Hamidjavadi\guid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_decode;

class ClientController extends Controller
{


    /**
     * Display a form to add a new client.
     *
     * @return View
     */
    public function index()
    {

        return view('pages.clientarea.clients', [
            'user'          => Auth::user(),
            'clients'       => $this->getClients(),
            'breadcrumb'    => [
                'Home'          => route('dashboard.index'),
                'Clients'       => ''
            ]
        ]);

    }


    /**
     * Get client list.
     *
     * @return Array
     */
    private function getClients () {

        $clients = Client::orderby('id', 'asc')->get();
        $clients = json_decode(json_encode($clients, true), true);

        return $clients;

    }


    /**
     * Add a new client.
     *
     * @param Request $request
     * @return JSON
     */
    public function addNewClient (Request $request) {

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
        $isDuplicate = Client::where([
                                            'title' => $request->input('client_name'),
                                        ])
                                ->get()
                                ->count();

        if($isDuplicate > 0) {

            $result['success']      = false;
            $result['messages'][]   = "Client exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check client is duplicate


        // Begin create new client
        $newClient = Client::create([
                                'title'     => $request->input('client_name'),
                                'uuid'      => guid::generate()
                            ]);
        // End create new client


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('New client added successfully'),
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
    public function removeClient (Request $request) {

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
        $exists = Client::where([
                                    'uuid' => $request->input('client_id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Client does not exists !";

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

        Client::where('uuid', $request->input('client_id'))->delete(); 
        $tier1_link = Tier1::where('client_id', $request->input('client_id'))->value('tier1_link');
        Tier1::where('client_id', $request->input('client_id'))->delete();
        $tier1_link_exists = Tier1::where([
                                            'tier1_link' => $tier1_link,
                                        ])
                                        ->get()
                                        ->count();
        if($tier1_link_exists == 0){
            Tier2::where('tier1_link_id', $tier1_link)->delete();
            Tier2::where('client_id', $request->input('client_id'))->delete();
        }else{
            Tier2::where('client_id', $request->input('client_id'))->delete();
        }
        

        // End remove client


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Client has been removed successfully'),
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
    public function updateClient (Request $request) {

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
        $exists = Client::where([
                                    'uuid' => $request->input('client_id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Client does not exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check client exists


        // Begin update client
        Client::where('uuid', $request->input('client_id'))
                    ->update([
                                'title' => $request->input('client_name')
                            ]);
        // End update client


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Client has been updated successfully'),
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

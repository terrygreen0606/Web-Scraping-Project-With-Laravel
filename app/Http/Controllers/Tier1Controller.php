<?php

namespace App\Http\Controllers;

use App\Tier1;
use App\Client;
use App\Provider;
use App\Tier2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_decode;

class Tier1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('linkmonitor.tier1link', [
            'user'          => Auth::user(),
            'tier1'       => $this->getTier1(),
            'client' => $this->getClient(),
            'provider' => $this->getProvider(),
            'breadcrumb'    => [
                'Home'          => route('dashboard.index'),
                'Tier 1 Link'       => ''
            ]
        ]);
    }

    /*  End index function   */

     /**
     * Get Tier1 list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTier1()
    {
        $tier1 = Tier1::orderby('id', 'asc')->get();

        for( $i=0; $i < count($tier1); $i++)
        {
            $tier1[$i]['client_id'] = Client::where('uuid', $tier1[$i]['client_id'])->value('title');
            $tier1[$i]['provider_id'] = Provider::where('uuid', $tier1[$i]['provider_id'])->value('title');
        }
        $tier1 = json_decode(json_encode($tier1, true), true);

        return $tier1;

    }


    /** End getTier1 fuction */

    /**
     * Get client list.
     *
     * @return Array
     */
    private function getClient() {

        $client = Client::orderby('id', 'asc')->get();
        $client = json_decode(json_encode($client, true), true);

        return $client;

    }
    /** End getClients function */

    /**
     * Get provider list.
     *
     * @return Array
     */
    private function getProvider () {

        $provider = Provider::orderby('id', 'asc')->get();
        $provider = json_decode(json_encode($provider, true), true);

        return $provider;

    }
    /** End getProvider function */


    /**
     * add Tier1 list.
     * @param Request $request
     * @return JSON
     */
    public function addTier1(Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );

        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'client_id'    => ['required', 'string'],
            'provider_id'    => ['required', 'string'],
            'tier1_link'    => ['required', 'string'],
            'emUrl'    => ['required','string'],
            'anchor_text'    => ['required', 'string'],
            'target_url'    => ['required', 'string']
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

        $client_uuid = Client::where('title', $request->input('client_id'))->value('uuid');
        $provider_uuid = Provider::where('title', $request->input('provider_id'))->value('uuid');

        // Begin create new Tier1
        $newTier1 = Tier1::create([
                                'client_id'     => $client_uuid,
                                'provider_id'     => $provider_uuid,
                                'tier1_link'     => $request->input('tier1_link'),
                                'emUrl'     => $request->input('emUrl') == "null" ? "" : $request->input('emUrl'),
                                'anchor_text'     => $request->input('anchor_text'),
                                'target_url'     => $request->input('target_url')
                            ]);
        // End create new client


        //Begin create new Tier1
            $newTier1['client_id'] = $request->input('client_id');
            $newTier1['provider_id'] = $request->input('provider_id');
        //End create new Tier1


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('New Tier1 added successfully'),
            'data'      => array(
                                    'tier1'  => $newTier1
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
    /** End addTier1 function */



      /**
     * Remove a Tier1.
     *
     * @param Request $request
     * @return JSON
     */
    public function removeTier1 (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'id'    => ['required']
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


        // Begin check tier1 exists
        $exists = Tier1::where([
                                    'id' => $request->input('id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Tier1 does not exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check tier1 exists



        // TODO: Remove all things belongs to the tier1

        // Begin remove tier1

        $tier1_link = Tier1::where('id', $request->input('id'))->value('tier1_link');

        Tier1::where('id', $request->input('id'))->delete();
        Tier2::where('tier1_link_id', $tier1_link)->delete();

        // End remove tier1


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Tier1 has been removed successfully'),
            'data'      => array(
                                    'id' => $request->input('id')
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
     * Update a Tier1.
     *
     * @param Request $request
     * @return JSON
     */
    public function updateTier1 (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'id'   => ['required'],
            'client_id'   => ['required', 'string'],
            'provider_id'   => ['required', 'string'],
            'tier1_link'   => ['required', 'string'],
            'emUrl'   => ['required', 'string'],
            'anchor_text'   => ['required', 'string'],
            'target_url'   => ['required', 'string']
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


        // Begin check Teir1 exists
        $exists = Tier1::where([
                                    'id' => $request->input('id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Tier1 does not exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check Tier1 exists

        $client_uuid = Client::where('title', $request->input('client_id'))->value('uuid');
        $provider_uuid = Provider::where('title', $request->input('provider_id'))->value('uuid');
        $tier1_link = Tier1::where('id', $request->input('id'))->value('tier1_link');

        // Begin update Tier1
        Tier1::where('id', $request->input('id'))
                    ->update([
                                'client_id' => $client_uuid,
                                'provider_id' => $provider_uuid,
                                'tier1_link' => $request->input('tier1_link'),
                                'emUrl'     => $request->input('emUrl') == "null" ? "" : $request->input('emUrl'),
                                'anchor_text' => $request->input('anchor_text'),
                                'target_url' => $request->input('target_url')
                            ]);
        // End update Tier1

        $tier1_link_count = Tier1::where([
            'tier1_link' => $tier1_link,
        ])
        ->get()
        ->count();

        if($tier1_link_count == 0){

            Tier2::where('tier1_link_id', $tier1_link)->update(['tier1_link_id' => $request->input('tier1_link')]);

        }

        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Tier1 has been updated successfully'),
            'data'      => array(
                                    'tier1_id' => $request->input('id'),
                                    'client_id' => $request->input('client_id'),
                                    'provider_id' => $request->input('provider_id'),
                                    'tier1_link' => $request->input('tier1_link'),
                                    'emUrl' => $request->input('emUrl'),
                                    'anchor_text' => $request->input('anchor_text'),
                                    'target_url' => $request->input('target_url')
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

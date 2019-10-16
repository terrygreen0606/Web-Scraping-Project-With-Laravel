<?php

namespace App\Http\Controllers;

use App\Tier2;
use App\Tier1;
use App\Client;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_decode;

class Tier2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('linkmonitor.tier2link', [
            'user'          => Auth::user(),
            'tier1'       => $this->getTier1(),
            'tier2'  => $this->getTier2(),
            'client' => $this->getClient(),
            'provider' => $this->getProvider(),
            'breadcrumb'    => [
                'Home'          => route('dashboard.index'),
                'Tier 2 Link'       => ''
            ]
        ]);
    }

    /*  End index function   */

     /**
     * Get Tier2 list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTier1()
    {
        $tier1 = Tier1::orderby('id', 'asc')->get();
        $tier1 = json_decode(json_encode($tier1, true), true);

        return $tier1;

    }
    /** End getTier1 fuction */


    /**
     * Get Tier2 list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTier2()
    {
        $tier2 = Tier2::orderby('id', 'asc')->get();

        for( $i=0; $i < count($tier2); $i++)
        {
            $tier2[$i]['client_id'] = Client::where('uuid', $tier2[$i]['client_id'])->value('title');
            $tier2[$i]['provider_id'] = Provider::where('uuid', $tier2[$i]['provider_id'])->value('title');
        }

        $tier2 = json_decode(json_encode($tier2, true), true);

        return $tier2;

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
     * add Tier2 list.
     * @param Request $request
     * @return JSON
     */
    public function addTier2(Request $request) {

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
            'tier1_link_id'    => ['required', 'string'],
            'tier2_link'    => ['string'],
            'anchor_text'    => ['required', 'string']
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

        // Begin create new Tier2
        $newTier2 = Tier2::create([
                                'client_id'     => $client_uuid,
                                'provider_id'     => $provider_uuid,
                                'tier1_link_id'     => $request->input('tier1_link_id'),
                                'anchor_text'     => $request->input('anchor_text'),
                                'tier2_link'     => $request->input('tier2_link')
                            ]);
        // End create new Tier2

        //Begin create new Tier2 client_id, provider_id
            $newTier2['client_id'] = $request->input('client_id');
            $newTier2['provider_id'] = $request->input('provider_id');
        //End create new Tier2 client_id, provider_id

        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('New Tier2 added successfully'),
            'data'      => array(
                                    'tier2'  => $newTier2
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
     * Remove a Tier2.
     *
     * @param Request $request
     * @return JSON
     */
    public function removeTier2 (Request $request) {

        $result = array (
            'success'   => null,
            'messages'  => [],
            'data'      => [],
        );


        // Begin validate request
        $data = $request->input();
        $validationResult = Validator::make($data, [
            'id'    => ['required', 'int']
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
        $exists = Tier2::where([
                                    'id' => $request->input('id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Tier2 does not exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check tier2 exists


        // TODO: Remove all things belongs to the tier2

        // Begin remove tier2

        Tier2::where('id', $request->input('id'))->delete();

        // End remove tier2


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Tier2 has been removed successfully'),
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

    //End remove Tier2


      /**
     * Update a Tier2.
     *
     * @param Request $request
     * @return JSON
     */
    public function updateTier2 (Request $request) {

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
            'tier1_link_id'   => ['required', 'string'],
            'anchor_text'   => ['required', 'string'],
            'tier2_link'   => ['required', 'string']
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


        // Begin check Tier2 exists
        $exists = Tier2::where([
                                    'id' => $request->input('id'),
                                ])
                                ->get()
                                ->count();

        if($exists == 0) {

            $result['success']      = false;
            $result['messages'][]   = "Tier2 does not exists !";

            $response = response(
                        json_encode($result),
                        200
                    )
                    ->header('Content-Type', 'application/json');

            return $response;

        }
        // End check Tier2 exists

        $client_uuid = Client::where('title', $request->input('client_id'))->value('uuid');
        $provider_uuid = Provider::where('title', $request->input('provider_id'))->value('uuid');

        // Begin update Tier2
        Tier2::where('id', $request->input('id'))
                    ->update([
                                'client_id' => $client_uuid,
                                'provider_id' => $provider_uuid,
                                'tier1_link_id' => $request->input('tier1_link_id'),
                                'anchor_text' => $request->input('anchor_text'),
                                'tier2_link' => $request->input('tier2_link')
                            ]);
        // End update Tier2


        // Begin return response
        $result = array (
            'success'   => true,
            'messages'  => array('Tier2 has been updated successfully'),
            'data'      => array(
                                    'tier1_id' => $request->input('id'),
                                    'client_id' => $request->input('client_id'),
                                    'provider_id' => $request->input('provider_id'),
                                    'tier1_link_id' => $request->input('tier1_link_id'),
                                    'anchor_text' => $request->input('anchor_text'),
                                    'tier2_link' => $request->input('tier2_link')
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

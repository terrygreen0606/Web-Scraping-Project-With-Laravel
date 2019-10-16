<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as GuzzleHttpClient;

class OneHourIndexingController extends Controller
{
    
    private $appKey;
    private $apiKey;


    /**
     * Construct the class
     * 
     * @param String $appKey
     * @param String $apiKey
     * @return Void
     */
    public function __construct($appId, $apiKey)
    {
        
        $this->appId = $appId;
        $this->apiKey = $apiKey;

    }


    /**
     * Create a new batch
     * 
     * @param String $batchName
     * @param Array $links
     * @return String $batchId
     */
    public function createBatch ($batchName, $links) {

        // Begin prepare url and data
        $apiUrl = 'http://onehourindexing.co/api/createbatch?'. 
                        'AppId=' . $this->appId . 
                        '&ApiKey=' . $this->apiKey . 
                        '&Name=' . $batchName;

        $batchContent = implode("\n", $links);
        // End prepare url and data


        // Begin init client and send request
        $apiClient = new GuzzleHttpClient([
                                            'base_uri'  => $apiUrl,
                                            'timeout'   => 30,
        ]);

        $response = $apiClient->request('POST', '', [
            'body' => $batchContent
        ]);
        // End init client and send request

        $response = $this->parseResponse($response);

        return $response['BatchId'];

    }


    /**
     * Upload links into a batch
     * 
     * @param Int $batchId
     * @param Array $links
     * @param Int $linksPerDay
     * @return Int
     */
    public function uploadLinks ($batchId, $links, $linksPerDay) {


        // Begin prepare url and data
        $apiUrl = 'http://onehourindexing.co/api/uploadlinks?'.
                        'AppId=' . $this->appId . 
                        '&ApiKey=' . $this->apiKey . 
                        '&BatchId=' . $batchId .
                        '&LinksPerDay=' . $linksPerDay;

        $batchContent = implode("\n", $links);
        // End prepare url and data


        // Begin init client and send request
        $apiClient = new GuzzleHttpClient([
                                            'base_uri'  => $apiUrl,
                                            'timeout'   => 30,
        ]);

        $response = $apiClient->request('POST', '', [
            'body' => $batchContent
        ]);
        // End init client and send request

        $response = $this->parseResponse($response);


    }


    /**
     * 
     * Parse Response
     * 
     * @param GuzzleResponse $response
     * @return Array
     */
    private function parseResponse($response) {

        $responseBody       = $response->getBody();
        $responseContent    = $responseBody->getContents();

        return json_decode($responseContent, true);

    }


}
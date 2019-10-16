<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class DropboxController extends Controller
{

    private $accessToken = 'i9sDjGJguLAAAAAAAAAAIWCq-G9HXGo9wKt6_OPeQGYmyNbywwxdwQ0wMCXvwIOu';


    /**
     * Dropbox contructor
     * 
     * @param String $accessToken The Dropbox API access token
     * @return Void
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }


    /**
     * Get folder list of the specific path
     * 
     * @param String $path
     * @return JSON
     */
    public function folderList ($path = '') {

        $param = [
            "path"                      => $path,
            "recursive"                 => false,
            "include_media_info"        => false,
            "include_deleted"           => false,
            "include_mounted_folders"   => true,
            "include_has_explicit_shared_members"   => false,
            "include_non_downloadable_files"        => true
        ];

        $response = $this->sendRequest('files/list_folder', $param);
        $response = $this->parseResponse($response);

        dd($response);

    }


    /**
     * Upload file into the specific path and folder
     * 
     * @param String $path Path to store file on the Dropbox
     * @param String $fileName The file name that will be stored by that
     * @param String $fileContent File content to store on the Dropbox
     * @return Array
     */
    public function uploadFile ($filePath, $fileContent) {

        // Begin set params
        $params = [
            "path"              => $filePath,
            "mode"              => 'overwrite',
            "autorename"        => false,
            "mute"              => false,
            "strict_conflict"   => false
        ];

        $params = json_encode($params, true);
        // End set params


        // Begin setup client
        $dropboxClient  = new Client([
                                        'base_uri'  => 'https://content.dropboxapi.com/2/',
                                        'timeout'   => 300,
                                        'headers' => [
                                            'Authorization'     => 'Bearer ' . $this->accessToken,
                                            'Content-Type'      => 'application/octet-stream',
                                            'Dropbox-API-Arg'   => $params,
                                        ],
                                    ]);
        // End setup client


        // Begin send request
        $response = $dropboxClient->request('POST', 'files/upload', [
                                                                        'body'  => $fileContent
                                                                    ]
                                        );
        // End send request

        $response = $this->parseResponse($response);
        return $response;

    }


    /**
     * Delete file from the specific path
     * 
     * @param String $path File path to remove
     * @return Array
     */
    public function deleteFile ($filePath) {

        $params = [
            "path" => $filePath,
        ];

        $response = $this->sendRequest('files/delete_v2', $params);
        $response = $this->parseResponse($response);

        return $response;

    }


    /**
     * Send request to dropbox
     * 
     * @param String $URI
     * @param Array $params
     * @return Array
     */
    private function sendRequest($URI, $params) {

        $dropboxClient  = new Client([
                                        'base_uri'  => 'https://api.dropboxapi.com/2/',
                                        'timeout'   => 300,
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $this->accessToken
                                        ]
                                    ]);

        $response = $dropboxClient->request('POST',
                                             $URI, 
                                             [
                                                'json'   => $params,
                                            ]
                                        );


        return $response;

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

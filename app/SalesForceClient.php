<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Class SalesForceClient
 * @package App
 */
class SalesForceClient
{
    /**
     * @var string
     */
    private $grant_type = 'password';
    /**
     * @var string
     */
    private $client_id;
    /**
     * @var string
     */
    private $client_secret;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $token_uri;
    /**
     * @var string
     */
    private $api_uri;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var
     */
    private $access_token;
    /**
     * @var string
     */
    private $apex_uri;

    /**
     * SalesForceClient constructor.
     * @param string $client_id
     * @param string $client_secret
     * @param string $username
     * @param string $password
     * @param string $token_uri
     * @param string $api_uri
     * @param string $apex_uri
     * @param Client $client
     */
    public function __construct(
        $client_id,
        $client_secret,
        $username,
        $password,
        $token_uri,
        $api_uri,
        $apex_uri,
        $client
    ) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->username = $username;
        $this->password = $password;
        $this->token_uri = $token_uri;
        $this->api_uri = $api_uri;
        $this->apex_uri = $apex_uri;
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function token()
    {
        if (!$this->access_token) {
            $this->access_token = Cache::get('access_token');
        }

        return $this->access_token;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, $uri = '', $options = [])
    {
        $token = $this->token();
        if (!$token) {
            $this->generateToken();
        }

        if (isset($options['json'])) {
            $options['json']['guid'] = Auth::user()->guid;
            if (isset(Auth::user()->viewas_guid)) {
                $options['json']['guid'] = Auth::user()->viewas_guid;
            }
        } elseif (isset($options['query'])) {
            $options['query']['guid'] = Auth::user()->guid;
            if (isset(Auth::user()->viewas_guid)) {
                $options['query']['guid'] = Auth::user()->viewas_guid;
            }
        }

        $options = array_merge($options, [
            'headers' => ['Authorization' => 'Bearer ' . $this->token()]
        ]);
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (ClientException $ex) {
            if ($ex->getCode() == 401) {
                $content = json_decode($ex->getResponse()->getBody()->getContents(), true);
                if ($content[0] && $content[0]['errorCode'] == 'INVALID_SESSION_ID') {
                    $this->generateToken();
                    $response = $this->client->request($method, $uri, array_merge(
                        $options,
                        [
                            'headers' => ['Authorization' => 'Bearer ' . $this->token()]
                        ]
                    ));
                } else {
                    throw $ex;
                }
            } else {
                throw $ex;
            }
        } catch (RequestException $ex) {
            if ($ex->getCode() == 500) {
                $content = json_decode($ex->getResponse()->getBody()->getContents(), true);
                $uniqueRefId = uniqid();

                if ($content[0] && isset($content[0]['message'])) {
                    log_sf_error($uri, $content[0], $options['json'], $uniqueRefId);
                }
                return response()
                    ->json(
                        [
                            'message' =>
                            'Support team is notified. Reference number for tracking: ' . $uniqueRefId
                        ]
                    );
            }
        }
        $content = json_decode($response->getBody()->getContents(), true);

        if (isset($content['errorCode']) && $content['errorCode'] >= 400) {
            $uniqueRefId = uniqid();

            if (isset($content['message'])) {
                log_sf_error($uri, $content['message'], $options['json'], $uniqueRefId);
            }
            return response()
                ->json(
                    [
                        'message' =>
                        'Support team is notified. Reference number for tracking: ' . $uniqueRefId
                    ]
                );
        }

        return $content;
    }

    /**
     * @param string $uri
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $uri, $data = [])
    {
        $options = [
            'json' => $data
        ];
        $uri = "{$this->apex_uri}/{$uri}";
        return $this->request('POST', $uri, $options);
    }


    /**
     * @return mixed
     */
    public function generateToken()
    {
        $response = $this->client->post(
            $this->token_uri,
            [
                'form_params' => [
                    'grant_type' => $this->grant_type,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'username' => $this->username,
                    'password' => $this->password
                ]
            ]
        );

        $content = json_decode($response->getBody()->getContents(), true);
        $access_token = $content['access_token'];
        Cache::forever('access_token', $access_token);
        $this->access_token = $access_token;
        return $access_token;
    }

    /**
     * @param $query
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query($query)
    {
        $uri = $this->api_uri . '/query';
        $options = [
            'query' => [
                'q' => $query
            ]
        ];
        return $this->request('GET', $uri, $options);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function describe($name)
    {
        $uri = sprintf("%s/sobjects/%s/describe/", $this->api_uri, $name);
        return $this->request('GET', $uri);
    }


    /**
     * @param string $object
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $object, array $data)
    {
        $options = [
            'json' => $data
        ];
        $uri = "{$this->api_uri}/sobjects/{$object}";
        return $this->request('POST', $uri, $options);
    }

    /**
     * @param string $object
     * @param string $id
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(string $object, string $id, array $data)
    {
        $options = [
            'json' => $data
        ];
        $uri = "{$this->api_uri}/sobjects/{$object}/{$id}";
        return $this->request('PATCH', $uri, $options);
    }

    /**
     * @param string $object
     * @param string $id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $object, string $id)
    {
        $uri = "{$this->api_uri}/sobjects/{$object}/{$id}";
        return $this->request('DELETE', $uri);
    }

    /**
     * Upload a file to a project from SalesForce
     *
     * @param $file_name
     * @param $projectId
     * @param $type
     * @param $source_path
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFile($file_name, $projectId, $type, $source_path)
    {
        $this->projectId = $projectId;

        $contents = [
            "Title" => $file_name,
            "Description" => $file_name,
            "PathOnClient" => $file_name,
            "MyWork_Project__c" => $projectId,
            "File_Type__c" => $type,
            "FirstPublishLocationId" => "0585C0000008qlt"
        ];

        $options = [
            'headers' => [
                "Accept" => "application/json"
            ],
            'multipart' => [
                [
                    'name' => 'entity_content',
                    'contents' => json_encode($contents),
                    'headers' => ['Content-Type' => 'application/json']
                ],
                [
                    'name' => 'VersionData',
                    'contents' => Storage::get($source_path),
                    "filename" => $file_name,
                    'headers' => ['Content-Type' => 'application/octet-stream']
                ]
            ]
        ];

        $uri = env('SF_API_URI') . "/sobjects/ContentVersion";

        $this->client->request('POST', $uri, $options);

        if (Storage::disk('local')->exists($source_path)) {
            Storage::disk('local')->delete($source_path);
        }

        $this->projectId = "";

        return 'OK';
    }
}

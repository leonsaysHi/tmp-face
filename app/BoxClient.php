<?php

namespace App;

use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Facades\App\SalesForceClient;

class BoxClient
{
    /**
     * @var string
     */
    private $grant_type = 'jwt-bearer';
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
    private $redirect_uri;
    /**
     * @var string
     */
    private $enterprise_id;
    /**
     * @var string
     */
    private $app_user_id;
    /**
     * @var string
     */
    private $app_user_name;
    /**
     * @var string
     */
    private $public_key_id;
    /**
     * @var string
     */
    private $passphrase;
    /**
     * @var string
     */
    private $auth_url;
    /**
     * @var string
     */
    private $api_url;
    /**
     * @var string
     */
    private $expiration;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Client
     */
    private $access_token;

    /**
     * BoxClient constructor.
     * @param string $client_id
     * @param string $client_secret
     * @param string $redirect_uri
     * @param string $enterprise_id
     * @param string $app_user_name
     * @param string $app_user_id
     * @param string $public_key_id
     * @param string $passphrase
     * @param string $auth_url
     * @param string $api_url
     * @param Integer $expiration
     * @param Client $client
     */
    public function __construct(
        string $client_id,
        string $client_secret,
        string $redirect_uri,
        string $enterprise_id,
        string $app_user_name,
        string $app_user_id,
        string $public_key_id,
        string $passphrase,
        string $auth_url,
        string $api_url,
        int $expiration,
        Client $client
    ) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->enterprise_id = $enterprise_id;
        $this->app_user_name = $app_user_name;
        $this->app_user_id = $app_user_id;
        $this->public_key_id = $public_key_id;
        $this->passphrase = $passphrase;
        $this->auth_url = $auth_url;
        $this->api_url = $api_url;
        $this->expiration = $expiration;
        $this->client = $client;
    }

    /**
     * @param $type
     * @param $uri
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($type, $uri, $data = [])
    {
        $token = $this->getToken();

        if (!$token) {
            $this->generateToken();
        }
        try {
            $response = $this->client->$type($this->api_url . $uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'content-type' => 'application/json',
                    "As-User" => config('box.as_user_id')
                ],
                'body' => json_encode($data),
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $ex) {
            throw $ex;
        }
    }

    /**
     * @return Client|mixed
     */
    public function getToken()
    {
        if (!$this->access_token) {
            $this->access_token = Cache::get('box_access_token');
        }
        return $this->access_token;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generateToken()
    {
        $path = base_path("private_key_box.pem");
        if (!\File::exists($path)) {
            throw new \Exception(sprintf("File must exist %s", $path));
        }
        $key = openssl_pkey_get_private("file://" . $path, $this->passphrase);

        $claims = [
            'iss' => $this->client_id,
            'sub' => $this->enterprise_id,
            'box_sub_type' => 'enterprise',
            'aud' => $this->auth_url,
            'jti' => base64_encode(random_bytes(64)),
            'exp' => $this->expiration,
            'kid' => $this->public_key_id
        ];

        $assertion = JWT::encode($claims, $key, 'RS512');

        $params = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:' . $this->grant_type,
            'assertion' => $assertion,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ];


        $response = $this->client->request('POST', $this->auth_url, [
            'form_params' => $params,
        ]);

        $data = $response->getBody()->getContents();
        $access_token = json_decode($data)->access_token;
        Cache::put('box_access_token', $access_token, 3600);
        $this->access_token = $access_token;
        return $access_token;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeRawGetProjectCall($id)
    {
        $access_token = SalesForceClient::generateToken();
        $response = $this->client->request(
            'GET',
            env("BOX_RAW_PROJECT_API") . $id . "'",
            [
                'headers' => ['Authorization' => 'Bearer ' . $access_token]
            ]
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $id
     * @param $path
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function downloadFile($id, $path)
    {
        $file = $this->request('GET', 'files/' . $id);

        $content = $this->request('GET', 'files/' . $file['id'] . "?fields=download_url");

        $tempFile = fopen($path, 'w');

        return $this->request('GET', $content['download_url'], ['sink' => $tempFile]);
    }

    /**
     * Remove a file from the box folder with given id
     *
     * @param $file_id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroyFile($file_id)
    {
        return $this->request('DELETE', sprintf("files/%s", $file_id));
    }

    /**
     * @param $id
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getItemsInFolder($id)
    {
        return $this->request('GET', 'folders/' . $id . '/items');
    }
}

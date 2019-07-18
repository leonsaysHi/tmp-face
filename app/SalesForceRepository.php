<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * Class SalesForceRepository
 * @package App
 */
class SalesForceRepository
{

    /**
     * @var int
     */
    protected $page = 1;
    /**
     * @var int
     */
    protected $pageSize = 999;
    /**
     * @var string
     */
    protected $sortBy = 'start_date';
    /**
     * @var string
     */
    protected $sortOrder = 'asc';

    /**
     * @var SalesForceClient
     */
    private $client;


    /**
     * SalesForceRepository constructor.
     * @param SalesForceClient $client
     */
    public function __construct(SalesForceClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $uri, $data = [])
    {
        return $this->client->post($uri, $data);
    }

    /**
     * @param string $query
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query(string $query)
    {
        return $this->client->query($query);
    }

    /**
     * @param string $object
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $object, array $data)
    {
        return $this->client->create($object, $data);
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
        $this->client->update($object, $id, $data);
        return 'OK';
    }

    /**
     * @param string $object
     * @param string $id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $object, string $id)
    {
        $this->client->delete($object, $id);
        return 'OK';
    }

    /**
     * Example transform to make api match a more
     * Laravel/Bootstrap compatible pagination
     */
    protected function transformRecords($records)
    {
        $data = Arr::get($records, 'records', []);
        $paginated = [];
        $paginated['data'] = collect($data)->map(
            function ($record) {
                return $this->transformRecord($record);
            }
        );

        $paginated['total'] = Arr::get($records, 'totalSize', 0);
        return $paginated;
    }

    /**
     * Here per record type we can guard againts
     * api changes impacted our front end
     * as well as alter info as needed
     */
    protected function transformRecord($record)
    {
        return $record;
    }

    /**
     * @param array $contents
     * @param $file
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(array $contents, $file)
    {
        $fileName = normalize_string(
            basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())
        ) . '.' . $file->getClientOriginalExtension();

        $path = storage_path("app/public/project-attachments/{$fileName}");

        $contents = [
            "Title" => $contents['fileTitle'],
            "Description" => $file->getClientMimeType(),
            "PathOnClient" => $file->getClientOriginalName(),
            "MyWork_Project__c" => $contents['projectId'],
            "File_Type__c" => $contents['fileType'],
            "FirstPublishLocationId" => $this->determineEnvAndReturnFirstPublishLocationId()
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
                    'contents' => fopen($path, "r"),
                    "filename" => "{$fileName}",
                    'headers' => ['Content-Type' => 'application/octet-stream']
                ]
            ]
        ];

        $uri = env('SF_API_URI') . "/sobjects/ContentVersion";

        $response = $this->client->request('POST', $uri, $options);
        $fileExists = Storage::disk('local')->exists("public/project-attachments/{$fileName}");

        if ($fileExists) {
            unlink(storage_path("app/public/project-attachments/{$fileName}"));
        }

        return ["fileSfdcId" => $response['id']];
    }

    /**
     * @param $id
     * @param $fileName
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download($id, $fileName)
    {
        $uri = "ContentVersion/{$id}/VersionData";
        $file = fopen(storage_path("app/public/project-attachments/{$fileName}"), 'w');

        $options = [
            'sink' => $file,
        ];

        $uri = env('SF_API_URI') . "/sobjects/{$uri}";

        return $this->client->request('GET', $uri, $options);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteFile(string $id)
    {
        $options = [
            "json" => [
                "files" => [
                    ["fileSfdcId" => $id]
                ]
            ],
            'headers' => [
                "Accept" => "application/json"
            ],
        ];

        $uri = env('SF_APEX_URI') . "/mywork/v1/delete_file";

        return $this->client->request('POST', $uri, $options);
    }

    /**
     * Return the correct id based on the environment the app is working on
     *
     * @return string
     */
    private function determineEnvAndReturnFirstPublishLocationId()
    {
        switch (env('APP_ENV')) {
            case 'staging':
                return '0585C0000008qlt';
                break;
            case 'uat':
                return '0585B000000E81X';
                break;
            case 'production':
                return '0580P000000Vx4Q';
                break;
            case 'local':
                return '';
                break;
            default:
                return '';
        }
    }
}

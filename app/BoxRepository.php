<?php

namespace App;

use App\Mail\SupportEmailForInvalidBoxFiles;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Facades\App\SalesForceClient;

class BoxRepository
{
    public $error = '';

    /**
     * @var BoxClient
     */
    private $client;

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var SalesForceClient
     */
    private $SFClient;

    /**
     * @var SalesForceClient
     */
    private $projectId;

    /**
     * BoxRepository constructor.
     * @param BoxClient $client
     * @param ProjectRepository $projectRepository
     * @param SalesForceClient $SFClient
     */
    public function __construct(BoxClient $client, ProjectRepository $projectRepository, SalesForceClient $SFClient)
    {
        $this->client = $client;
        $this->SFClient = $SFClient;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Iterate through files in the box folder and process them:
     *
     * If it matches with the naming convention;
     * - download the file
     * - upload the file to the project
     * - delete the file from box folder
     *
     * If it does not, send an email to support team with the reason
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processFiles()
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            $path = storage_path(sprintf("app/public/project-attachments/%s", $file['name']));
            if ($this->checkIfTheFileMatchesTheNamingConvention($file)) {
                $this->client->downloadFile($file['id'], $path);
                $file_name = $file['name'];
                $projectId = strtok($file_name, '_');
                $type = strtok($file_name, $projectId . '_');
                $path = storage_path("app/public/project-attachments/{$file['name']}");
                SalesForceClient::uploadFile($file_name, $projectId, $type, $path);
                $this->client->destroyFile($file['id']);
            } else {
                $this->sendEmailToSupportTeam();
            }
        }
        Log::info('Hourly box file iteration has been complete - ' . date('H:i'));
        return 'OK';
    }

    /**
     * Check if the file name matches the naming convention.
     *
     * @param $file
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkIfTheFileMatchesTheNamingConvention($file)
    {
        if (!is_null($file)) {
            $projectId = strtok($file['name'], '_');
            $type = strtok($file['name'], $projectId . '_');

            if (!$this->checkProject($projectId)) {
                $this->sendEmailToSupportTeam();
                $this->error = '';
                return false;
            }

            if (!$this->checkType($type)) {
                $this->sendEmailToSupportTeam();
                $this->error = '';
                return false;
            }

            return true;
        }
        return false;
    }


    /**
     * Get a list of files in the folder.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getFiles()
    {
        $data = $this->client->getItemsInFolder(config('box.folder_id'));
        $files = collect($data['entries'])->filter(function ($file) {
            return $file['type'] === 'file';
        });

        return $files;
    }

    /**
     * Check if project with given id is exists and not closed
     *
     * @param $id
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkProject($id)
    {
        if (!is_null($id)) {
            $response = $this->client->makeRawGetProjectCall($id);
            if (is_array($response) && isset($response['totalSize']) && $response['totalSize'] > 0) {
                if ($response['records'][0]['Status__c'] !== 'Cancelled') {
                    $this->projectId = $response['records'][0]['Id'];
                    return true;
                } else {
                    $this->setErrorMessage('Can not upload attachments to a cancelled project: ' . $id);
                    return false;
                }
            } else {
                $this->setErrorMessage('No project found with given id: ' . $id);
                return false;
            }
        }
    }

    /**
     * Check if given type is valid
     *
     * @param $type
     * @return bool
     */
    private function checkType($type)
    {
        $types = ["AEC", "EXRPT", "FINR", "RAWD", "QUEST", "SOW", "RFP", "OTHR"];

        if (!in_array($type, $types)) {
            $this->setErrorMessage('Invalid document type: ' . $type);
        }

        return in_array($type, $types);
    }

    /**
     * Set an error message for support email
     *
     * @param $string
     */
    private function setErrorMessage($string)
    {
        $this->error = $string;
    }

    /**
     * Send an email to given mail
     *
     * @return string
     */
    private function sendEmailToSupportTeam()
    {
        Mail::to(config('box.support_email'))->send(new SupportEmailForInvalidBoxFiles($this->error));

        return 'OK';
    }
}

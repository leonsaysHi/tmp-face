<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\BoxRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportEmailForInvalidBoxFiles;
use Mockery;
use App\BoxClient;
use Facades\App\SalesForceClient;

class BoxRepositoryTest extends TestCase
{
    /**
     * @covers \App\BoxRepository::processFiles
     * @covers \App\BoxRepository::checkIfTheFileMatchesTheNamingConvention
     * @covers \App\BoxRepository::checkProject
     * @covers \App\BoxRepository::checkType
     * @covers \App\BoxRepository::getFiles
     * @covers \App\BoxRepository::setErrorMessage
     * @covers \App\BoxRepository::sendEmailToSupportTeam
     * @covers \App\BoxClient::makeRawGetProjectCall
     * @covers \App\BoxClient::request
     * @covers \App\BoxClient::downloadFile
     * @covers \App\BoxClient::destroyFile
     * @covers \App\BoxClient::getItemsInFolder
     * @covers \App\SalesForceClient::uploadFile
     */
    public function testProcessFiles()
    {

        SalesForceClient::shouldReceive("uploadFile")->times(3);

        $data = \File::get(base_path("tests/fixtures/box_files.json"));
        $data = json_decode($data, 128);
        $boxClient = Mockery::mock(BoxClient::class);
        $boxClient->shouldReceive('downloadFile')->times(3);
        $boxClient->shouldReceive('destroyFile')->times(3);
        $boxClient->shouldReceive('getItemsInFolder')->once()->andReturn($data);
        $raw_file = \File::get(base_path("tests/fixtures/make_raw_get_project_call.json"));
        $raw_file = json_decode($raw_file, 128);
        $boxClient->shouldReceive('makeRawGetProjectCall')->andReturn($raw_file);

        \App::instance(BoxClient::class, $boxClient);

        Mail::fake();

        $repo = \App::make(BoxRepository::class);

        $repo->processFiles();

        Mail::assertSent(SupportEmailForInvalidBoxFiles::class, 4);
    }
}

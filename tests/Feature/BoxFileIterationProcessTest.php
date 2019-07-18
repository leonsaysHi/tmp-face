<?php

namespace Tests\Feature;

use App\BoxClient;
use App\Mail\SupportEmailForInvalidBoxFiles;
use App\SalesForceClient;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Mail;

class BoxFileIterationProcessTest extends TestCase
{
    /**
     * @group ada
     * @covers \App\Console\Commands\BoxFileIterationProcess::__construct
     * @covers \App\Console\Commands\BoxFileIterationProcess::handle
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testBoxFileIterationProcess()
    {
        Mail::fake();
        Storage::fake();

        $data = \File::get(base_path("tests/fixtures/box_file.json"));
        $raw_files = \File::get(base_path("tests/fixtures/make_raw_get_project_call.json"));

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"foo_bar\"}"),
            new Response(200, [], $data),
            new Response(200, [], "{\"access_token\":\"foo_bar\"}"),
            new Response(200, [], json_encode([
                    'entries' => [
                        [
                            'type' => "file",
                            "name" => "foo"
                        ]
                    ]
                ])
            ),
            new Response(200, [], "{\"access_token\":\"foo_bar\"}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);
        Cache::forget('access_token');
        $client = app()->make(SalesForceClient::class);

        $client->generateToken();

        Cache::put('box_access_token', 'foo', 100);
        Storage::shouldReceive("disk")->andReturnSelf();
        Storage::shouldReceive("get")->andReturn("foo");
        Storage::shouldReceive("exists")->andReturnFalse();


        $boxClient = Mockery::mock(BoxClient::class);
        $boxClient->shouldReceive('getItemsInFolder')->andReturn($data);
        $boxClient->shouldReceive('makeRawGetProjectCall')->andReturn($raw_files);

        $resultAsText = Artisan::call('box:iterate');
        Mail::assertSent(SupportEmailForInvalidBoxFiles::class, 2);

        $this->assertEquals($resultAsText, 0);
    }
}

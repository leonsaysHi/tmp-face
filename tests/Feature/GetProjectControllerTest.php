<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class GetProjectControllerTest extends TestCase
{
    /**
     * @covers App\Http\Controllers\GetProjectController::__invoke
     * @covers \App\ProjectRepository::get
     * @covers \App\ProjectRepository::filterThroughConditions
     * @covers \App\ProjectRepository::draft
     * @covers \App\ProjectRepository::isConfidential
     * @covers \App\ProjectRepository::isReadOnlyUser
     * @covers \App\ProjectRepository::returnProject
     * @covers \App\ProjectRepository::returnUnauthorisedError
     *
     */
    public function testGetDraftProject()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"message\":\"test\",\"errorCode\":\"202\",\"projects\": [{\"isEditable\": true, \"isOwner\": true, \"status\": \"Draft\", \"isEditableStatusOnly\": true, \"isConfidential\": true}]}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->get('/api/get-project/a4M5C000000Cxo8UAC')
            ->assertStatus(200)
            ->assertJsonFragment([
                "projects" =>  [
                    [
                        "isEditable" => true,
                        "isOwner" => true,
                        "status" => 'Draft',
                        "isEditableStatusOnly" => true,
                        "isConfidential" => true
                    ]
                ]
            ]);
    }

    /**
     * @covers \App\ProjectRepository::pending
     */
    public function testGetPendingProject()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"message\":\"test\",\"errorCode\":\"202\",\"projects\": [{\"isEditable\": true, \"isOwner\": true, \"status\": \"Pending\", \"isEditableStatusOnly\": true, \"isConfidential\": true}]}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->get('/api/get-project/a4M5C000000Cxo8UAC')
            ->assertStatus(200)
            ->assertJsonFragment([
                "projects" =>  [
                    [
                        "isEditable" => true,
                        "isOwner" => true,
                        "status" => 'Pending',
                        "isEditableStatusOnly" => true,
                        "isConfidential" => true
                    ]
                ]
            ]);
    }

    /**
     * @covers \App\ProjectRepository::active
     */
    public function testGetActiveProject()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"message\":\"test\",\"errorCode\":\"202\",\"projects\": [{\"isEditable\": true, \"isOwner\": true, \"status\": \"Active\", \"isEditableStatusOnly\": true, \"isConfidential\": true}]}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->get('/api/get-project/a4M5C000000Cxo8UAC')
            ->assertStatus(200)
            ->assertJsonFragment([
                "projects" =>  [
                    [
                        "isEditable" => true,
                        "isOwner" => true,
                        "status" => 'Active',
                        "isEditableStatusOnly" => true,
                        "isConfidential" => true
                    ]
                ]
            ]);
    }

    /**
     * @covers \App\ProjectRepository::completed
     */
    public function testGetCompletedProject()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"message\":\"test\",\"errorCode\":\"202\",\"projects\": [{\"isEditable\": true, \"isOwner\": true, \"status\": \"Completed\", \"isEditableStatusOnly\": true, \"isConfidential\": true}]}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->get('/api/get-project/a4M5C000000Cxo8UAC')
            ->assertStatus(200)
            ->assertJsonFragment([
                "projects" =>  [
                    [
                        "isEditable" => true,
                        "isOwner" => true,
                        "status" => 'Completed',
                        "isEditableStatusOnly" => true,
                        "isConfidential" => true
                    ]
                ]
            ]);
    }

    /**
     * @covers \App\ProjectRepository::canceled
     */
    public function testGetCancelledProject()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"message\":\"test\",\"errorCode\":\"202\",\"projects\": [{\"isEditable\": true, \"isOwner\": true, \"status\": \"Cancelled\", \"isEditableStatusOnly\": true, \"isConfidential\": true}]}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->get('/api/get-project/a4M5C000000Cxo8UAC')
            ->assertStatus(200)
            ->assertJsonFragment([
                "projects" =>  [
                    [
                        "isEditable" => true,
                        "isOwner" => true,
                        "status" => 'Cancelled',
                        "isEditableStatusOnly" => true,
                        "isConfidential" => true
                    ]
                ]
            ]);
    }
}

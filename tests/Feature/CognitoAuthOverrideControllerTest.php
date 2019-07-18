<?php

namespace Tests\Feature;

use App\CognitoUserService;
use App\Http\Controllers\CognitoAuthOverrideController;
use App\User;
use Mockery;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Pfizer\CognitoAuthLaravel\GuzzleCognitoClient;
use Pfizer\CognitoAuthLaravel\Services\JWTService;
use Pfizer\CognitoAuthLaravel\Interfaces\CognitoClientInterface;
use Pfizer\CognitoAuthLaravel\Interfaces\CognitoUserServiceLaravelInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CognitoAuthControllerTest extends TestCase
{

    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @covers \App\Http\Controllers\CognitoAuthOverrideController::__construct
     * @covers \App\Http\Controllers\CognitoAuthOverrideController::login
     */
    public function testLogin()
    {
        $token = $this->getMockToken();
        $user_model = new User();
        $user = $user_model->create([
            'email' => $token->email,
            'name' => $this->faker->name,
            'password' => $this->faker->password
        ]);
        $mock_token_response = new \stdClass();
        $mock_token_response->id_token = $token;
        $this->instance(CognitoUserServiceLaravelInterface::class, Mockery::mock(CognitoUserService::class, function ($mock) use ($user) {
            $mock->shouldReceive('mapToUser')->once()->andReturn($user);
        }));
        $this->instance(Client::class, Mockery::mock(Client::class, function ($mock) use ($mock_token_response) {
        }));
        $this->instance(JWTService::class, Mockery::mock(JWTService::class, function ($mock) use ($token) {
            $mock->shouldReceive('decodeToken')->once()->andReturn($token);
        }));
        $this->instance(CognitoClientInterface::class, Mockery::mock(GuzzleCognitoClient::class, function ($mock) use ($mock_token_response) {
            $mock->shouldReceive('getTokenWithAuthCode')->once()->andReturn($mock_token_response);
        }));
        $request       = Mockery::mock(Request::class);
        $request->code = 'mock_auth_code';
        $class = App::make(CognitoAuthOverrideController::class);
        $class->login($request);
        $this->assertAuthenticatedAs($user);
        $user->delete();
    }

    protected function getMockToken()
    {
        $token = '{
	        "at_hash": "mock-hash",
	        "sub": "mock_sub",
	        "cognito:groups": ["eu-west-1_mock"],
	        "email_verified": false,
	        "iss": "https:\/\/cognito-idp.eu-west-1.amazonaws.com\/mock",
	        "cognito:username": "pfizer-saml_mock",
	        "custom:ntid": "cavana01",
	        "custom:guid": "20942296",
	        "aud": "2nrml26fccamsjrm9j7558ni5h",
	        "identities": [{
	        	"userId": "20942296",
	        	"providerName": "pfizer-saml",
	        	"providerType": "SAML",
	        	"issuer": "http:\/\/dev.pfizer.com\/SAML20",
	        	"primary": "true",
	        	"dateCreated": "1549484167325"
	        }],
	        "token_use": "id",
	        "auth_time": 1553702921,
	        "name": "Test",
	        "exp": 1553706521,
	        "iat": 1553702921,
	        "family_name": "User",
	        "email": "test.user@pfizer.com"
        }';

        $token              = json_decode($token);
        $token->email       = $this->faker->email;
        $token->family_name = $this->faker->lastName;
        $token->name        = $this->faker->firstName;

        return $token;
    }

}

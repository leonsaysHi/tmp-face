<?php

namespace Tests\Feature;

use App\CognitoUserService;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CognitoUserServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user_model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user_model = new User();
    }

    /**
     * @covers \App\CognitoUserService::parseIncomingUser
     * @throws \Exception
     */
    public function testCanMapExistingUser()
    {
        $token     = $this->getMockToken();
        $orig_user = User::create([
            'email'    => $token->email,
            'password' => "foo",
            'name'     => trim(sprintf("%s %s", $token->{"custom:name"}, $token->{"custom:family_name"}))
        ]);
        $class     = new CognitoUserService($this->user_model);
        $user      = $class->mapToUser($token);
        $this->assertEquals($orig_user->email, $user->email);
        $this->assertEquals($orig_user->name, $user->name);
    }

    public function testFirstAndLast()
    {
        $token      = $this->getMockToken();
        $class      = new CognitoUserService($this->user_model);
        $class->mapToUser($token);
        $this->assertNotNull($class->getFirstName());
        $this->assertEquals("Bar", $class->getFirstName());
        $this->assertNotNull($class->getFamilyName());
        $this->assertEquals("Foo", $class->getFamilyName());
    }

    /**
     * @covers \App\CognitoUserService::mapToUser
     * @throws \Exception
     */
    public function testCanGetItentityAndCustomFields()
    {
        $token      = $this->getMockToken();
        $class      = new CognitoUserService($this->user_model);
        $class->mapToUser($token);
        $this->assertNotNull($class->getUserId());
        $this->assertEquals("20942296", $class->getUserId());
        $this->assertNotNull($class->getNtId());
        $this->assertEquals("cavana01", $class->getNtId());
        $this->assertNotEmpty($class->getGroupList());
        $this->assertIsArray($class->getGroupList());
        $this->assertCount(5, $class->getGroupList());
        $this->assertTrue(in_array("DL-BT-CloudAppsTeam", $class->getGroupList()));
    }

    /**
     * @covers \App\CognitoUserService::mapToUser
     * @throws \Exception
     */
    public function testCanMapNewUser()
    {
        $token      = $this->getMockToken();
        $class      = new CognitoUserService($this->user_model);
        $user       = $class->mapToUser($token);
        $token = (array)$token;
        $token_name = trim(sprintf("%s %s", $token['custom:name'], $token['custom:family_name']));
        $this->assertNotNull($token_name);
        $this->assertEquals($token['email'], $user->email);
        $this->assertEquals($token_name, $user->name);
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
          "custom:name": "Bar",
          "custom:family_name": "Foo",
          "custom:grouplist": "[DL-BT-CloudAppsTeam, GBL-PFE-NON-COLLEAGUES-U, AMR-Mobile-Dev-App-DASH, AMR-Mobile-Dev-App-Moon, AMR-Mobile-Dev-BetaApps-MobileCOE]",
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
	        "exp": 1553706521,
	        "iat": 1553702921,
	        "email": "test.user@pfizer.com"
        }';

        $token              = json_decode($token);
        $token->email       = $this->faker->email;

        return $token;
    }
}

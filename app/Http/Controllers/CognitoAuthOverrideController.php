<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Pfizer\CognitoAuthLaravel\Http\Controllers\CognitoAuthController;
use Pfizer\CognitoAuthLaravel\Interfaces\CognitoClientInterface;
use Pfizer\CognitoAuthLaravel\Interfaces\CognitoUserServiceLaravelInterface;
use Pfizer\CognitoAuthLaravel\Services\JWTService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CognitoAuthOverrideController extends CognitoAuthController
{
    private $jwt_service;
    private $client;
    private $userService;
    private $repository;

    /**
     * CognitoAuthOverrideController constructor.
     * @param JWTService $JWTService
     * @param CognitoUserServiceLaravelInterface $userService
     * @param CognitoClientInterface $client
     * @param UserRepository $repository
     */
    public function __construct(
        JWTService $JWTService,
        CognitoUserServiceLaravelInterface $userService,
        CognitoClientInterface $client,
        UserRepository $repository
    ) {
        $this->jwt_service = $JWTService;
        $this->userService = $userService;
        $this->client = $client;
        $this->repository = $repository;

        parent::__construct(
            $this->jwt_service,
            $this->userService,
            $this->client
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(Request $request)
    {
        try {
            $auth_code = $request->code;
            $token_response = $this->client->getTokenWithAuthCode($auth_code);
            $id_token = $this->jwt_service->decodeToken($token_response->id_token);
            Auth::login($this->userService->mapToUser($id_token));
            $response = $this->repository->get();
            $user = User::find(Auth::id());

            if ((is_array($response) && isset($response['errorCode']) && $response['errorCode'] == "200") &&
            isset($response['users']) && !is_null($response['users'])) {
                $user->user_type = $response['users'][0]['userType'];
                $user->update();

                return Redirect::intended();
            }

            if ($user->role === 'tester') {
                return Redirect::intended();
            }

            Auth::logout();

            return redirect('/login')->withErrors(
                sprintf(
                    "Account with email: %s and with GUID: %s does not exist",
                    $user->email,
                    $user->guid
                )
            );
        } catch (\Exception $e) {
            Log::error(sprintf(
                "Failed to log in with SAML with error %s on line %s in file %s",
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            ));
            return redirect('/login')->withErrors(
                [
                    'Error authenticating. Error logged and admin notified'
                ]
            );
        }
    }
}

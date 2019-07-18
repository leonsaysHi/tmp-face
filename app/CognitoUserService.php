<?php

namespace App;

use Pfizer\CognitoAuthLaravel\Interfaces\CognitoUserServiceLaravelInterface;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Pfizer\CognitoAuthLaravel\Services\CognitoUserServiceLaravel;
use Illuminate\Support\Facades\Log;

class CognitoUserService extends CognitoUserServiceLaravel
{
    /**
     * @NOTE
     * This is the one method you need to overwrite to save
     * other data as needed. UNLESS you need to pull in more data
     * than this `CognitoUserServiceLaravel` does.
     * Then override all of it using the cognito.php config file
     */
    protected function parseIncomingUser($user)
    {
        /**
         * Get but do not add to model
         */
        $this->formatIncomingIdentity($user);
        $this->formatCustomFields($user);
        return [
            'email'    => $this->formatIncomingEmail($user),
            'name'     => $this->formatIncomingName($user),
            'guid'     => $this->getUserId(),
            'password' => $this->randomString()
        ];
    }
    /**
     * @NOTE The only reason I override this method is cause the guid
     * was not fillable
     */
    public function mapToUser(object $id_token)
    {
        try {
            $parsed = $this->parseIncomingUser($id_token);
            if ($user_found = $this->findUser($parsed['email'])) {
                $user_found->guid = $parsed['guid']; //just to show who to update the user
                $user_found->save();
                return $user_found;
            }
            $user_created = $this->user->create($parsed);
            $user_created->guid = $parsed['guid']; //a not fillable field
            $user_created->save();
            return $user_created;
        } catch (\Exception $e) {
            $message = sprintf("Error mapping user %s", $e->getMessage());
            throw new \Exception($message);
        }
    }
}

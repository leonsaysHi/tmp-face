<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * @param User $user
     * @return boolean
     */
    public function viewIndex(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * @param User $user
     * @return boolean
    */
    public function search(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * @param User $user
     * @return boolean
    */
    public function create(User $user)
    {
        return $user->isSuperAdmin();
    }
}

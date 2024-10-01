<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function registerUser(array $data)
    {
        return User::create(array_merge($data, ['password' => bcrypt($data['password'])]));

    }

}

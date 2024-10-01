<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function registerUser(array $data)
    {
        // Register the user and return the User object
        return $this->authRepository->registerUser($data);
    }

    /**
     * Attempt to login a user.
     *
     * @param array $credentials
     * @return bool|null
     */
    public function loginUser(array $credentials)
    {
        if (!$token = Auth::attempt($credentials)) {
            return null;
        }
        return $token;
    }
}

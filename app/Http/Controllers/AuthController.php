<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user and return JWT token.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Validate request data
        $data = $request->validated();

        $user = $this->authService->registerUser($data);

        // Check if the user was created successfully
        if ($user) {
            // Generate a JWT token for the registered user
            $token = auth()->login($user);

            // Return success response with the user data and JWT token
            return $this->successResponse([
                'user' => new AuthResource($user),
                'token' => $token,
                'token_type' => 'bearer',
            ], 'Registration successful', 201);
        }

        return $this->errorResponse('Registration failed', 400);
    }

    /**
     * Login user and return JWT token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        // Attempt to login and create JWT token
        $token = $this->authService->loginUser($credentials);

        if ($token) {
            return $this->successResponse([
                'token' => $token,
                'token_type' => 'bearer',
            ], 'Login successful', 200);
        }

        return $this->unauthorizedResponse('Unauthorized', 401);
    }
}

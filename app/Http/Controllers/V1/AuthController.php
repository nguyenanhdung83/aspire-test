<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\User\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct(UserService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get access token by email and password
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->responseUnauthorized();
        }

        $token = $this->authService->createToken(Auth::user());

        return $this->responseSuccess(['token' => $token]);

    }

    /**
     * Remove access token
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->responseSuccess();
    }
}

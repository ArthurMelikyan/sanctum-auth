<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }

    /**
     * Create User
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->registerNewUser($request);

        return response()->json([
            'message' => 'Account successfully created',
            'token' => $user->createToken('API Token')->plainTextToken
        ], Response::HTTP_OK);
    }

    /**
     * Login The User
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json(['message' => 'Credentials not match'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ], Response::HTTP_OK);
    }

    /**
     * @return UserResource
     */
    public function user(): UserResource
    {
        return new UserResource(auth()->user());
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        auth()->user()->tokens()->delete();
        return response()->noContent();
    }
}

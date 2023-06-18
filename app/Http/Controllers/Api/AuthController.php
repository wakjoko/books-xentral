<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\TokenStatusEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Models\PersonalAccessToken;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\PersonalAccessTokenResource;

final class AuthController extends Controller
{
    /**
     * Register the user.
     */
    public function register(RegisterRequest $request): UserResource
    {
        $validated = $request->validated();
        $createdUser = User::create($validated);
        $userResource = new UserResource($createdUser);
        $userResource->withResponse($request, new JsonResponse(status: Response::HTTP_CREATED));
        return $userResource;
    }

    /**
     * Log the user in (Create a new personal access token).
     */
    public function login(LoginRequest $request): PersonalAccessTokenResource
    {
        $request->authenticateOrFail();

        $user = $request->user();

        $tokenName = $request->input('token_name');
        $expiresAt = $request->date(key: 'expires_at', format: 'Y-m-d');

        /** @var \Laravel\Sanctum\NewAccessToken $token */
        $token = $user->createToken(name: $tokenName, expiresAt: $expiresAt);

        $tokenResource = new PersonalAccessTokenResource($token->accessToken);
        $tokenResource->withResponse($request, new JsonResponse(status: Response::HTTP_CREATED));
        $tokenResource->additional([
            'token' => [
                'type' => 'Bearer',
                'plain_text' => $token->plainTextToken,
                'status' => TokenStatusEnum::Active,
            ],
        ]);

        return $tokenResource;
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(Request $request): PersonalAccessTokenResource
    {
        $user = $request->user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();

        return (new PersonalAccessTokenResource($token))->additional([
            'token' => [
                'type' => 'Bearer',
                'status' => TokenStatusEnum::Revoked,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\AuthJob;

class TokenAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $user = Auth::user();

        $token = $user->createToken(
            $request->token_name ?? 'auth',
            ['read', 'create'],
            now()->addWeek()
        );

        AuthJob::dispatch($user);

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response('', 204);
    }
}

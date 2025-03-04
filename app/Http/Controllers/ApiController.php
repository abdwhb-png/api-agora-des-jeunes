<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ApiController extends Controller
{
    public function index(Request $request)
    {
        $bearerToken = $request->header('Authorization');
        if (!$bearerToken) {
            return response()->json(['error' => 'No token provided']);
        }

        $tokenValue = str_replace('Bearer ', '', $bearerToken);
        $token = PersonalAccessToken::findToken($tokenValue);

        if (!$token) {
            return response()->json(['error' => 'Invalid token']);
        }

        return response()->json([
            'bearerToken' => $bearerToken,
            'tokenName' => $token->name,
            'canRead' => $token->can('true'),
        ]);
    }
}

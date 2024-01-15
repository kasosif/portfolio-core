<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse {
        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }

        if ($token = auth()->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            return response()->json([
                "code" => 200,
                "message" =>"User logged in successfully",
                "resultType" => "SUCCESS",
                "result" => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 'never'
                ]
            ]);
        }
        return response()->json([
            "code" => 401,
            "message" =>"Unauthorized",
            "resultType" => "ERROR",
            "result" => null
        ], 401);

    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            "code" => 200,
            "message" =>"User logged out successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

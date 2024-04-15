<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\SocialAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialAccountController extends Controller
{
    public function list(): JsonResponse {
        $socialAccounts = SocialAccount::all();
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccounts retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $socialAccounts
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'type' => 'required|unique:social_accounts',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $socialAccount = SocialAccount::create([
            'type' => $request->get('type'),
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccount added successfully",
            "resultType" => "SUCCESS",
            "result" => $socialAccount
        ]);
    }
    public function delete(int $socialAccountId): JsonResponse {
        $socialAccount = SocialAccount::find($socialAccountId);
        if (!$socialAccount) {
            return response()->json([
                "code" => 404,
                "message" =>"SocialAccount not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $socialAccount->delete();
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccount deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

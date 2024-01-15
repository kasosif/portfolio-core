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
        $user = auth('api')->user();
        $socialAccounts = SocialAccount::query();
        $user->hasRole(['admin']) ? $socialAccounts = $socialAccounts->get() : $socialAccounts = $socialAccounts->where('candidate_id', $user->candidate_id)->get();
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
            'type' => 'required',
            'name' => 'required',
            'link' => 'required',
            'candidateId' => $user->hasRole(['admin']) ? 'required' : '',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $candidateId = $user->hasRole(['admin']) ? $request->get('candidateId') : $user->candidate_id;
        $candidate = Candidate::find($candidateId);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $socialAccount = SocialAccount::create([
            'type' => $request->get('type'),
            'name' => $request->get('name'),
            'link' => $request->get('link'),
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccount added successfully",
            "resultType" => "SUCCESS",
            "result" => $socialAccount
        ]);
    }
    public function update(Request $request, int $socialAccountId): JsonResponse {
        $user = auth('api')->user();
        $socialAccount = SocialAccount::find($socialAccountId);
        if (!$socialAccount) {
            return response()->json([
                "code" => 404,
                "message" =>"SocialAccount not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'type' => 'nullable',
            'name' => 'nullable',
            'link' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $socialAccount->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        if ($request->has('type') && $request->get('type') != null) $socialAccount->type = $request->get('type');
        if ($request->has('name') && $request->get('name') != null) $socialAccount->name = $request->get('name');
        if ($request->has('link') && $request->get('link') != null) $socialAccount->link = $request->get('link');
        $socialAccount->save();
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccount updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $socialAccountId): JsonResponse {
        $user = auth('api')->user();
        $socialAccount = SocialAccount::find($socialAccountId);
        if (!$socialAccount) {
            return response()->json([
                "code" => 404,
                "message" =>"SocialAccount not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $socialAccount->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function list($candidateId = null): JsonResponse {
        $user = auth('api')->user();
        if ($candidateId) {
            if (!$user->hasRole(['admin'])) {
                return response()->json([
                    "code" => 401,
                    "message" =>"Unauthorized",
                    "resultType" => "ERROR",
                    "result" => null
                ], 401);
            }
            $contactRequests = ContactRequest::where('candidate_id', $candidateId)->orderBy('created_at', 'desc')->get();
        } else {
            if (!$user->hasRole(['admin'])) {
                $contactRequests = ContactRequest::where('candidate_id', $user->candidate_id)->orderBy('created_at', 'desc')->get();
            } else {
                $contactRequests = ContactRequest::orderBy('created_at', 'desc')->get();
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Contact Requests retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $contactRequests
        ]);
    }
    public function one($requestId): JsonResponse {
        $user = auth('api')->user();
        $contactRequest = ContactRequest::find($requestId);
        if (!$contactRequest) {
            return response()->json([
                "code" => 404,
                "message" =>"Contact Request not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $contactRequest->candidate_id != $user->candidate_id) {
            return response()->json([
                "code" => 404,
                "message" =>"Contact Request not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        return response()->json([
            "code" => 200,
            "message" =>"Contact Request retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $contactRequest
        ]);
    }
}

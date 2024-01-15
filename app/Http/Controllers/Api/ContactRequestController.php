<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function list(int $contactRequestId = null): JsonResponse {
        if ($contactRequestId) {
            $contactRequest = ContactRequest::with(['candidate' => function($q) {
                return $q->select('id','first_name','last_name','email','phone_number');
            }])->where('id', $contactRequestId)->first();
            if (!$contactRequest) {
                return response()->json([
                    "code" => 404,
                    "message" =>"ContactRequest not found",
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
        $contactRequests = ContactRequest::with(['candidate' => function($q) {
            return $q->select('id','first_name','last_name','email','phone_number');
        }])->get();
        return response()->json([
            "code" => 200,
            "message" =>"Contact Requests retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $contactRequests
        ]);
    }
}

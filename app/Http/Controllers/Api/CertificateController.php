<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    public function list(): JsonResponse {
        $user = auth('api')->user();
        $certificates = Certificate::query();
        $user->hasRole(['admin']) ? $certificates = $certificates->get() : $certificates = $certificates->where('candidate_id', $user->candidate_id)->get();
        return response()->json([
            "code" => 200,
            "message" =>"Certificates retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $certificates
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'title' => 'required',
            'number' => 'nullable',
            'issuer' => 'required',
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
        $certificate = Certificate::create([
            'date' => $request->get('date'),
            'title' => $request->get('title'),
            'number' => $request->get('number'),
            'issuer' => $request->get('issuer'),
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Certificate added successfully",
            "resultType" => "SUCCESS",
            "result" => $certificate
        ]);
    }
    public function update(Request $request, int $certificateId): JsonResponse {
        $user = auth('api')->user();
        $certificate = Certificate::find($certificateId);
        if (!$certificate) {
            return response()->json([
                "code" => 404,
                "message" =>"Certificate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'title' => 'nullable',
            'number' => 'nullable',
            'issuer' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $certificate->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $certificate->update($request->only('date', 'title', 'number', 'issuer'));
        return response()->json([
            "code" => 200,
            "message" =>"Certificate updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $certificateId): JsonResponse {
        $user = auth('api')->user();
        $certificate = Certificate::find($certificateId);
        if (!$certificate) {
            return response()->json([
                "code" => 404,
                "message" =>"Certificate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $certificate->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $certificate->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Certificate deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

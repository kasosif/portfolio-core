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
            $certificates = Certificate::where('candidate_id', $candidateId)->get();
        } else {
            if (!$user->hasRole(['admin'])) {
                $certificates = Certificate::where('candidate_id', $user->candidate_id)->get();
            } else {
                $certificates = Certificate::all();
            }
        }
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
            'draft' => 'nullable|boolean',
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
            'draft' => $request->get('draft') ?? false,
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
            'issuer' => 'nullable',
            'draft' => 'nullable|boolean'
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
        if ($request->has('date') && $request->get('date') != null) $certificate->date = $request->get('date');
        if ($request->has('title') && $request->get('title') != null) $certificate->title = $request->get('title');
        if ($request->has('number') && $request->get('number') != null) $certificate->number = $request->get('number');
        if ($request->has('issuer') && $request->get('issuer') != null) $certificate->issuer = $request->get('issuer');
        if ($request->has('draft') && $request->get('draft') !== null) $certificate->draft = $request->get('draft');
        $certificate->save();
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

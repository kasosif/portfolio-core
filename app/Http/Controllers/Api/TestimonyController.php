<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Testimony;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonyController extends Controller
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
            $testimonies = Testimony::where('candidate_id', $candidateId)->get();
        } else {
            if (!$user->hasRole(['admin'])) {
                $testimonies = Testimony::where('candidate_id', $user->candidate_id)->get();
            } else {
                $testimonies = Testimony::all();
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Testimonials retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $testimonies
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'testimony' => 'required',
            'testimony_name' => 'required',
            'testimony_job_description' => 'required',
            'draft' => 'nullable|boolean',
            'testimony_country' => 'required',
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
        $testimony = Testimony::create([
            'testimony' => $request->get('testimony'),
            'testimony_name' => $request->get('testimony_name'),
            'testimony_job_description' => $request->get('testimony_job_description'),
            'testimony_country' => $request->get('testimony_country'),
            'draft' => $request->get('draft') ?? false,
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Testimonial added successfully",
            "resultType" => "SUCCESS",
            "result" => $testimony
        ]);
    }
    public function update(Request $request, int $testimonyId): JsonResponse {
        $user = auth('api')->user();
        $testimony = Testimony::find($testimonyId);
        if (!$testimony) {
            return response()->json([
                "code" => 404,
                "message" =>"Testimonial not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'testimony' => 'nullable',
            'testimony_name' => 'nullable',
            'testimony_job_description' => 'nullable',
            'draft' => 'nullable|boolean',
            'testimony_country' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $testimony->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        if ($request->has('testimony') && $request->get('testimony') != null) $testimony->testimony = $request->get('testimony');
        if ($request->has('testimony_name') && $request->get('testimony_name') != null) $testimony->testimony_name = $request->get('testimony_name');
        if ($request->has('testimony_job_description') && $request->get('testimony_job_description') != null) $testimony->testimony_job_description = $request->get('testimony_job_description');
        if ($request->has('testimony_country') && $request->get('testimony_country') != null) $testimony->testimony_country = $request->get('testimony_country');
        if ($request->has('draft') && $request->get('draft') !== null) $testimony->draft = $request->get('draft');
        $testimony->save();
        return response()->json([
            "code" => 200,
            "message" =>"Testimonial updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $testimonyId): JsonResponse {
        $user = auth('api')->user();
        $testimony = Testimony::find($testimonyId);
        if (!$testimony) {
            return response()->json([
                "code" => 404,
                "message" =>"Testimonial not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $testimony->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $testimony->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Testimonial deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Education;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    public function list(): JsonResponse {
        $user = auth('api')->user();
        $education = Education::query();
        $user->hasRole(['admin']) ? $education = $education->get() : $education = $education->where('candidate_id', $user->candidate_id)->get();
        return response()->json([
            "code" => 200,
            "message" =>"Education retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $education
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' =>  'required_if:current,false',
            'current' =>  'required',
            'degree' =>  'required',
            'acknowledgement' => 'nullable',
            'institute' => 'required',
            'institute_country' => 'required',
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
        $education = Education::create([
            'start_date' => $request->get('start_date'),
            'end_date' =>  $request->get('end_date'),
            'current' =>  $request->get('current'),
            'degree' =>  $request->get('degree'),
            'acknowledgement' => $request->get('acknowledgement'),
            'institute' => $request->get('institute'),
            'institute_country' => $request->get('institute_country'),
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Education added successfully",
            "resultType" => "SUCCESS",
            "result" => $education
        ]);
    }
    public function update(Request $request, int $educationId): JsonResponse {
        $user = auth('api')->user();
        $education = Education::find($educationId);
        if (!$education) {
            return response()->json([
                "code" => 404,
                "message" =>"Education not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $startDateValidation = ['nullable', 'date'];
        if ($request->has('current') && $request->get('current') === false) $startDateValidation[] = 'before:end_date';
        $endDateValidation = ['date'];
        if ($request->has('current') && $request->get('current') === true) $endDateValidation[] = 'nullable'; else $endDateValidation[] = 'required';
        $validator = Validator::make($request->all(), [
            'start_date' => $startDateValidation,
            'end_date' =>  $endDateValidation,
            'current' =>  'nullable',
            'degree' =>  'nullable',
            'acknowledgement' => 'nullable',
            'institute' => 'nullable',
            'institute_country' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $education->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $education->update($request->only('start_date', 'end_date', 'current', 'degree', 'acknowledgement', 'institute', 'institute_country'));
        return response()->json([
            "code" => 200,
            "message" =>"Education updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $educationId): JsonResponse {
        $user = auth('api')->user();
        $education = Education::find($educationId);
        if (!$education) {
            return response()->json([
                "code" => 404,
                "message" =>"Education not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $education->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $education->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Education deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

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
            $education = Education::where('candidate_id', $candidateId)->orderBy('start_date', 'desc')->get();
        } else {
            if (!$user->hasRole(['admin'])) {
                $education = Education::where('candidate_id', $user->candidate_id)->orderBy('start_date', 'desc')->get();
            } else {
                $education = Education::orderBy('start_date', 'desc')->get();
            }
        }
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
        $education = Education::create([
            'start_date' => $request->get('start_date'),
            'end_date' =>  $request->get('end_date'),
            'current' =>  $request->get('current'),
            'degree' =>  $request->get('degree'),
            'acknowledgement' => $request->get('acknowledgement'),
            'institute' => $request->get('institute'),
            'institute_country' => $request->get('institute_country'),
            'draft' => $request->get('draft') ?? false,
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
            'draft' => 'nullable|boolean',
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
        if ($request->has('start_date') && $request->get('start_date') != null) $education->start_date = $request->get('start_date');
        if ($request->has('end_date') && $request->get('end_date') != null) $education->end_date = $request->get('end_date');
        if ($request->has('current') && $request->get('current') != null) $education->current = $request->get('current');
        if ($request->has('degree') && $request->get('degree') != null) $education->degree = $request->get('degree');
        if ($request->has('acknowledgement') && $request->get('acknowledgement') != null) $education->acknowledgement = $request->get('acknowledgement');
        if ($request->has('institute') && $request->get('institute') != null) $education->institute = $request->get('institute');
        if ($request->has('institute_country') && $request->get('institute_country') != null) $education->institute_country = $request->get('institute_country');
        if ($request->has('draft') && $request->get('draft') !== null) $education->draft = $request->get('draft');
        $education->save();
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Experience;
use App\Models\ExperienceTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    public function list(): JsonResponse {
        $user = auth('api')->user();
        $experiences = Experience::with('tasks')->query();
        $user->hasRole(['admin']) ? $experiences = $experiences->get() : $experiences = $experiences->where('candidate_id', $user->candidate_id)->get();
        return response()->json([
            "code" => 200,
            "message" =>"Experiences retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $experiences
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' =>  'required_if:current,false',
            'current' =>  'required',
            'company_name' =>  'required',
            'company_country' => 'required',
            'title' => 'required',
            'description' => 'nullable',
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
        $experience = Experience::create([
            'start_date' => $request->get('start_date'),
            'end_date' =>  $request->get('end_date'),
            'current' =>  $request->get('current'),
            'company_name' =>  $request->get('company_name'),
            'company_country' => $request->get('company_country'),
            'title' => $request->get('title'),
            'description' => $request->get('description') ?? null,
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Experience added successfully",
            "resultType" => "SUCCESS",
            "result" => $experience
        ]);
    }
    public function update(Request $request, int $experienceId): JsonResponse {
        $user = auth('api')->user();
        $experience = Experience::find($experienceId);
        if (!$experience) {
            return response()->json([
                "code" => 404,
                "message" =>"Experience not found",
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
            'company_name' =>  'nullable',
            'company_country' => 'nullable',
            'title' => 'nullable',
            'description' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $experience->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $experience->update($request->only('start_date', 'end_date', 'current', 'company_name', 'company_country', 'title', 'description'));
        return response()->json([
            "code" => 200,
            "message" =>"Experience updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $experienceId): JsonResponse {
        $user = auth('api')->user();
        $experience = Experience::find($experienceId);
        if (!$experience) {
            return response()->json([
                "code" => 404,
                "message" =>"Experience not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $experience->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $experience->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Experience deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function addTask(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'experienceId' => 'required|numeric',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $experience = Experience::find($request->get('experienceId'));
        if (!$experience) {
            return response()->json([
                "code" => 404,
                "message" =>"Experience not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $experience->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $experienceTask = ExperienceTask::create([
            'description' => $request->get('description'),
            'experience_id' => $experience->id
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Experience Task added successfully",
            "resultType" => "SUCCESS",
            "result" => $experienceTask
        ]);
    }
    public function deleteTask(int $taskId): JsonResponse {
        $user = auth('api')->user();
        $experienceTask = ExperienceTask::find($taskId);
        if (!$experienceTask) {
            return response()->json([
                "code" => 404,
                "message" =>"Experience Task not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }

        if (!$user->hasRole(['admin']) && $experienceTask->experience->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $experienceTask->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Experience Task deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

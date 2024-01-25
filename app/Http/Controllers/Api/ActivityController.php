<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function list(): JsonResponse {
        $user = auth('api')->user();
        $activities = Activity::query();
        $user->hasRole(['admin']) ? $activities = $activities->get() : $activities = $activities->where('candidate_id', $user->candidate_id)->get();
        return response()->json([
            "code" => 200,
            "message" =>"Activities retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $activities
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'draft' => 'nullable|boolean',
            'candidateId' => $user->hasRole(['admin']) ? 'required' : ''
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
        $activity = Activity::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'draft' => $request->get('draft') ?? false,
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Activity added successfully",
            "resultType" => "SUCCESS",
            "result" => $activity
        ]);
    }
    public function update(Request $request, int $activityId): JsonResponse {
        $user = auth('api')->user();
        $activity = Activity::find($activityId);
        if (!$activity) {
            return response()->json([
                "code" => 404,
                "message" =>"Activity not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'nullable',
            'draft' => 'nullable|boolean',
            'description' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $activity->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        if ($request->has('title') && $request->get('title') != null) $activity->title = $request->get('title');
        if ($request->has('description') && $request->get('description') != null) $activity->description = $request->get('description');
        if ($request->has('draft') && $request->get('draft') !== null) $activity->draft = $request->get('draft');
        $activity->save();
        return response()->json([
            "code" => 200,
            "message" =>"Activity updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $activityId): JsonResponse {
        $user = auth('api')->user();
        $activity = Activity::find($activityId);
        if (!$activity) {
            return response()->json([
                "code" => 404,
                "message" =>"Activity not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $activity->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $activity->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Activity deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

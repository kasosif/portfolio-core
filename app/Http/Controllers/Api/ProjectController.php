<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Project;
use App\Models\Tache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function list(): JsonResponse {
        $user = auth('api')->user();
        $projects = Project::with('tasks');
        $user->hasRole(['admin']) ? $projects = $projects->get() : $projects = $projects->where('candidate_id', $user->candidate_id)->get();
        return response()->json([
            "code" => 200,
            "message" =>"Projects retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $projects
        ]);
    }
    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'description' =>  'required',
            'title' =>  'required',
            'link' =>  'nullable|url',
            'draft' =>  'nullable|boolean',
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
        $project = Project::create([
            'date' => $request->get('date'),
            'description' =>  $request->get('description'),
            'title' =>  $request->get('title'),
            'link' =>  $request->get('link'),
            'draft' =>  $request->get('draft') ?? false,
            'candidate_id' => $candidateId
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Project added successfully",
            "resultType" => "SUCCESS",
            "result" => $project
        ]);
    }
    public function update(Request $request, int $projectId): JsonResponse {
        $user = auth('api')->user();
        $project = Project::find($projectId);
        if (!$project) {
            return response()->json([
                "code" => 404,
                "message" =>"Project not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'date' => 'nullable',
            'description' =>  'nullable',
            'title' =>  'nullable',
            'link' =>  'nullable',
            'draft' =>  'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $project->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        if ($request->has('date') && $request->get('date') != null) $project->date = $request->get('date');
        if ($request->has('description') && $request->get('description') != null) $project->description = $request->get('description');
        if ($request->has('title') && $request->get('title') != null) $project->title = $request->get('title');
        if ($request->has('link') && $request->get('link') != null) $project->link = $request->get('link');
        if ($request->has('draft') && $request->get('draft') !== null) $project->draft = $request->get('draft');
        $project->save();
        return response()->json([
            "code" => 200,
            "message" =>"Project updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $projectId): JsonResponse {
        $user = auth('api')->user();
        $project = Project::find($projectId);
        if (!$project) {
            return response()->json([
                "code" => 404,
                "message" =>"Project not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $user->candidate_id != $project->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $project->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Project deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function addTask(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'projectId' => 'required|numeric',
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
        $project = Project::find($request->get('projectId'));
        if (!$project) {
            return response()->json([
                "code" => 404,
                "message" =>"Project not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if (!$user->hasRole(['admin']) && $project->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $project->tasks()->create([
            'description' => $request->get('description')
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Project Task added successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function deleteTask(int $taskId): JsonResponse {
        $user = auth('api')->user();
        $projectTask = Tache::find($taskId);
        if (!$projectTask) {
            return response()->json([
                "code" => 404,
                "message" =>"Project Task not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }

        if (!$user->hasRole(['admin']) && $projectTask->taskable->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $projectTask->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Project Task deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

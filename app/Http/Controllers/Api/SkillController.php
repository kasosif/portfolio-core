<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    public function list(): JsonResponse {
        $skills = Skill::all();
        return response()->json([
            "code" => 200,
            "message" =>"Skills retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $skills
        ]);
    }
    public function add(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'type' => ['required','regex:(frontEnd|backEnd|default)'],
            'name' => 'required|unique:skills',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $skill = Skill::create([
            'type' => $request->get('type'),
            'name' => $request->get('name')
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Skill added successfully",
            "resultType" => "SUCCESS",
            "result" => $skill
        ]);

    }
    public function update(Request $request, int $skillId): JsonResponse {
        $skill = Skill::find($skillId);
        if (!$skill) {
            return response()->json([
                "code" => 404,
                "message" =>"Skill not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'type' => ['nullable', 'regex:(frontEnd|backEnd|default)'],
            'name' => ['nullable', Rule::unique('skills')->ignore($skill->name, 'name')],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if ($request->has('type') && $request->get('type') != null) $skill->type = $request->get('type');
        if ($request->has('name') && $request->get('name') != null) $skill->name = $request->get('name');
        $skill->save();
        return response()->json([
            "code" => 200,
            "message" =>"Skill updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $skillId): JsonResponse {
        $skill = Skill::find($skillId);
        if (!$skill) {
            return response()->json([
                "code" => 404,
                "message" =>"Skill not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $skill->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Skill deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

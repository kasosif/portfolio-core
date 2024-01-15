<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    public function list(): JsonResponse {
        $languages = Language::all();
        return response()->json([
            "code" => 200,
            "message" =>"Languages retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $languages
        ]);
    }
    public function add(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:languages',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $language = Language::create([
            'code' => $request->get('code'),
            'name' => $request->get('name')
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Language added successfully",
            "resultType" => "SUCCESS",
            "result" => $language
        ]);

    }
    public function update(Request $request, int $languageId): JsonResponse {
        $language = Language::find($languageId);
        if (!$language) {
            return response()->json([
                "code" => 404,
                "message" =>"Language not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'code' => ['nullable',
                Rule::unique('languages')->ignore($language->code, 'code')],
            'name' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if ($request->has('code') && $request->get('code') != null) $language->code = $request->get('code');
        if ($request->has('name') && $request->get('name') != null) $language->name = $request->get('name');
        $language->save();
        return response()->json([
            "code" => 200,
            "message" =>"Language updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(int $languageId): JsonResponse {
        $language = Language::find($languageId);
        if (!$language) {
            return response()->json([
                "code" => 404,
                "message" =>"Language not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $language->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Language deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

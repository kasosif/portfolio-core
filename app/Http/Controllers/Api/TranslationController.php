<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Language;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    public function translate(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validationArray = [
            'languageCode' => 'required',
            'toTranslate' => ['required', 'regex:(activity|candidate|certificate|education|experience|project|skill|tache|testimony)'],
            'toTranslateId' => ['required'],
            'candidateId' => $user->hasRole(['admin']) ? 'required' : '',
        ];
        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $data = $validator->getData();
        //check if language exists and if candidate has the language
        $language = Language::where('code',$data['languageCode'])->first();
        if (!$language) {
            return response()->json([
                "code" => 404,
                "message" =>"Language not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        $candidateId = $user->hasRole(['admin']) ? $request->get('candidateId') : $user->candidate_id;
        $candidate = Candidate::find($candidateId);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        $languageExists = $candidate->languages()->where('language_id',$language->id)->exists();
        if (!$languageExists) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate does not have this language",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        if (!$user->hasRole(['admin']) && $data['toTranslate'] === 'skill') {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        //check if candidate is the owner of toTranslate
        $model = app('App\\Models\\'.ucfirst($data['toTranslate']));
        $instance = $model::find($data['toTranslateId']);
        if (!$instance) {
            return response()->json([
                "code" => 404,
                "message" => ucfirst($data['toTranslate'])." not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        if (!$user->hasRole(['admin']) && $instance->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $secondValidationArray = [];
        foreach ($instance->getTranslatableKeys() as $key) {
            $secondValidationArray[$key] = 'required';
        }
        $validator = Validator::make($data, $secondValidationArray);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $attributes = Arr::except($data,['languageCode', 'toTranslate', 'toTranslateId', 'candidateId']);
        $instance->addTranslation($attributes, $language->id);
        return response()->json([
            "code" => 200,
            "message" => "Translation added to ".ucfirst($data['toTranslate'])." successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function getTranslatableFields(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'toTranslate' => ['required', 'regex:(activity|candidate|certificate|education|experience|project|skill|tache|testimony)']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $data = $validator->getData();
        $model = app('App\\Models\\'.ucfirst($data['toTranslate']));
        $instance = new $model();
        return response()->json([
            "code" => 200,
            "message" => "Translatable fields for ".ucfirst($data['toTranslate'])." retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $instance->getTranslatableKeys()
        ]);
    }
}

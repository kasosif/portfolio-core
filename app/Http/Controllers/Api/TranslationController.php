<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;

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
            'toTranslate' => ['required', 'regex:(activity|candidate|certificate|education|experience|project|skill|task|testimony)']
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

    public function getTranslatedModels(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'model' => ['required', 'regex:(activity|candidate|certificate|education|experience|project|task|testimony)'],
            'languageCode' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $data = $validator->validated();
        $language = Language::where('code',$data['languageCode'])->first();
        if (!$language || $language->code == 'en') {
            return response()->json([
                "code" => 404,
                "message" =>"Language not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        $candidate = Candidate::find($user->candidate_id);
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
        $model = app('App\\Models\\'.ucfirst($data['model']));
        $container = new $model();
        $translatableFields = $container->getTranslatableKeys();
        if ($data['model'] == 'candidate') {
            $instances = $model::where('id', $user->candidate_id)->get();
        } else if($data['model'] == 'task') {
            $projectIds = Project::where('candidate_id', $candidate->id)->pluck('id');
            $experienceIds = Experience::where('candidate_id', $candidate->id)->pluck('id');
            $instances = Task::where(function ($q) use ($projectIds) {
                return $q->where('taskable_type',Project::class)->whereIn('taskable_id', $projectIds);
            })->orWhere(function ($q) use ($experienceIds) {
                return $q->where('taskable_type',Experience::class)->whereIn('taskable_id', $experienceIds);
            })->get();
        } else {
            $instances = $model::where('candidate_id', $candidate->id)->get();
        }
        $replicatedInstances = [];
        foreach ($instances as $instance) {
            $replicatedInstance = $instance->replicate();
            $replicatedInstance->id = $instance->id;
            $replicatedInstances[] = $replicatedInstance;
        }
        $translatedInstances = [];
        foreach ($instances as $instance) {
            $translatedInstances[] = $instance->translate($language->id);
        }

        return response()->json([
            "code" => 200,
            "message" => "Translated ".ucfirst($data['model'])." retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => [
                'instances'=> $replicatedInstances,
                ...compact( 'translatedInstances', 'translatableFields')
            ]
        ]);

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CurriculumVitae;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CurriculumVitaeController extends Controller
{
    public function list(): JsonResponse {
        $user = auth('api')->user();
        $candidate = Candidate::find($user->candidate_id);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $resumes = $candidate->resumes;
        return response()->json([
            "code" => 200,
            "message" =>"Resumes retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $resumes
        ]);
    }

    public function add(Request $request): JsonResponse {
        $user = auth('api')->user();
        $candidate = Candidate::find($user->candidate_id);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'resume' => [
                'required',
                File::types(['pdf', 'doc', 'docx'])
                    ->max(6 * 1024),
            ],
            'resumeLanguageCode' => 'required|exists:languages,code'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $language = Language::where('code', $request->get('resumeLanguageCode'))->first();
        $existingResume = $candidate->resumes()->where('language_id', $language->id)->first();
        if ($existingResume) {
            Storage::delete($existingResume->path);
            $existingResume->delete();
        }
        $resume = $request->file('resume');
        $resumeName = strtolower($candidate->first_name).'_'.strtolower($candidate->last_name).'_resume.'.$resume->getClientOriginalExtension();
        $path = 'resumes/'.$candidate->id.'/'.$language->code;
        $resume->storeAs($path,$resumeName);
        $cv = CurriculumVitae::create([
            'path' => $path.'/'.$resumeName,
            'name' => $resumeName,
            'language_id' => $language->id,
            'candidate_id' => $candidate->id,
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Resume added successfully",
            "resultType" => "SUCCESS",
            "result" => $cv
        ]);
    }
    public function download(int $resumeId): BinaryFileResponse| JsonResponse {
        $user = auth('api')->user();
        $candidate = Candidate::find($user->candidate_id);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $resume = CurriculumVitae::find($resumeId);
        if (!$resume) {
            return response()->json([
                "code" => 404,
                "message" =>"Resume not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if ($resume->candidate_id !== $user->candidate_id && !$user->hasRole(['admin'])) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        return response()->file(storage_path('app/'.$resume->path));
    }

    public function publicAccess(int $resumeId): BinaryFileResponse | JsonResponse {
        $resume = CurriculumVitae::find($resumeId);
        if ($resume && $resume->public) {
            return response()->file(storage_path('app/'.$resume->path));
        }
        return response()->json([
            "code" => 404,
            "message" =>"Resume not found",
            "resultType" => "ERROR",
            "result" => null
        ], 404);
    }

    public function delete(int $resumeId): JsonResponse {
        $user = auth('api')->user();
        $candidate = Candidate::find($user->candidate_id);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $resume = CurriculumVitae::find($resumeId);
        if (!$resume) {
            return response()->json([
                "code" => 404,
                "message" =>"Resume not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if ($resume->candidate_id !== $user->candidate_id && !$user->hasRole(['admin'])) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        Storage::delete($resume->path);
        $resume->delete();
        return response()->json([
            "code" => 200,
            "message" =>"Resume deleted successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function togglePublic(int $resumeId) {
        $user = auth('api')->user();
        $candidate = Candidate::find($user->candidate_id);
        if (!$candidate && !$user->hasRole(['admin'])) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $resume = CurriculumVitae::find($resumeId);
        if (!$resume) {
            return response()->json([
                "code" => 404,
                "message" => "Resume not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        if (!$user->hasRole(['admin']) && $resume->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $resume->public = !$resume->public;
        $resume->save();
        $message = 'Resume set public successfully';
        if (!$resume->public) {
            $message = 'Resume set private successfully';
        }
        return response()->json([
            "code" => 200,
            "message" =>  $message,
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

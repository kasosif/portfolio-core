<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Language;
use App\Models\Skill;
use App\Models\SocialAccount;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    public function list(): JsonResponse {
        $candidates = Candidate::with(['languages' => function($q) {
            return $q->select('id','name','code');
        }])->get();
        return response()->json([
            "code" => 200,
            "message" =>"Candidates retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $candidates
        ]);
    }

    public function one(int $candidateId): JsonResponse {
        $candidate = Candidate::with(['languages' => function($q) {
            return $q->select('id','name','code');
        }])->where('id', $candidateId)->first();
        if ($candidate) {
            return response()->json([
                "code" => 200,
                "message" =>"Candidate retrieved successfully",
                "resultType" => "SUCCESS",
                "result" => $candidate
            ]);
        }
        return response()->json([
            "code" => 404,
            "message" =>"Candidate not found",
            "resultType" => "ERROR",
            "result" => null
        ]);
    }

    public function add(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:candidates',
            'job_description' => 'required',
            'about' => 'nullable',
            'date_of_birth' => 'date|required',
            'phone_number' => 'nullable',
            'address' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $candidate = Candidate::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'job_description' => $request->job_description,
            'about' => $request->about,
            'date_of_birth' => $request->date_of_birth,
            'phone_number' => $request->phone_number,
            'address' => $request->address
        ]);
        User::create([
            'name' => $candidate->first_name .' '. $candidate->last_name,
            'email' => $candidate->email,
            'email_verified_at' => now(),
            'candidate_id' => $candidate->id,
            'password' => Hash::make('12345')
        ]);
        $language = Language::where('code','=', 'en')->first();
        $candidate->languages()->attach($language->id);
        return response()->json([
            "code" => 200,
            "message" =>"Candidate added successfully",
            "resultType" => "SUCCESS",
            "result" => $candidate
        ]);
    }

    public function delete(int $candidateId): JsonResponse {
        $candidate = Candidate::find($candidateId);
        if ($candidate) {
            $candidate->delete();
            return response()->json([
                "code" => 200,
                "message" =>"Candidate deleted successfully",
                "resultType" => "SUCCESS",
                "result" => null
            ]);
        }
        return response()->json([
            "code" => 404,
            "message" =>"Candidate not found",
            "resultType" => "ERROR",
            "result" => null
        ]);
    }

    public function addLanguage(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'code' => 'required',
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
        $language = Language::where('code','=', $request->get('code'))->first();
        if (!$language) {
            return response()->json([
                "code" => 404,
                "message" =>"Language not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $languageExists = $candidate->languages()->where('language_id', $language->id)->exists();
        if (!$languageExists) $candidate->languages()->attach($language->id);
        return response()->json([
            "code" => 200,
            "message" =>"Language added to candidate successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function deleteLanguage(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'code' => 'required',
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
        $language = Language::where('code','=', $request->get('code'))->first();
        if (!$language || $language->code === 'en') {
            return response()->json([
                "code" => 404,
                "message" =>"Language not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $languageExists = $candidate->languages()->where('language_id','=', $language->id)->first();
        //delete all userTranslations for the language
        if ($languageExists) {
            $languageTranslations = Translation::where('language_id', $language->id)->get();
            foreach ($languageTranslations as $languageTranslation) {
                if ($languageTranslation->translatable->candidate_id === $candidate->id) {
                    $languageTranslation->delete();
                }
            }
            $candidate->languages()->detach($language->id);
        }
        return response()->json([
            "code" => 200,
            "message" =>"Language deleted from candidate successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function listCandidateLanguages(Request $request): JsonResponse {
        $user = auth('api')->user();
        $candidateId = $user->candidate_id;
        $candidate = Candidate::find($candidateId);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $languages = $candidate->languages;
        return response()->json([
            "code" => 200,
            "message" =>"Candidate languages retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $languages
        ]);
    }

    public function addSkill(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'skillId' => 'required',
            'percentage' => 'nullable|integer|min:1|max:100'
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
        $skill = Skill::find($request->get('skillId'));
        if (!$skill) {
            return response()->json([
                "code" => 404,
                "message" =>"Skill not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $skillExists = $candidate->skills()->where('skill_id', $skill->id)->exists();
        if ($skillExists) {
            $candidate->skills()->detach($skill->id);
        }
        DB::table('candidate_skill')->insert([
            'candidate_id' => $candidate->id,
            'skill_id' => $skill->id,
            'percentage' => $request->get('percentage') ?? null,
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"Skill added to candidate successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function deleteSkill(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'skillId' => 'required',
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
        $skill = Skill::find($request->get('skillId'));
        if (!$skill) {
            return response()->json([
                "code" => 404,
                "message" =>"Skill not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $skillExists = $candidate->skills()->where('skill_id', $skill->id)->exists();
        if ($skillExists) $candidate->skills()->detach($skill->id);
        return response()->json([
            "code" => 200,
            "message" =>"Skill deleted from candidate successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function listCandidateSkills(Request $request): JsonResponse {
        $user = auth('api')->user();
        $candidateId = $user->candidate_id;
        $candidate = Candidate::find($candidateId);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $skills = $candidate->skills;
        return response()->json([
            "code" => 200,
            "message" =>"Candidate Skills retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $skills
        ]);
    }

    public function addSocialAccount(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'socialAccountId' => 'required',
            'link' => 'required'
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
        $socialAccount = SocialAccount::find($request->get('socialAccountId'));
        if (!$socialAccount) {
            return response()->json([
                "code" => 404,
                "message" =>"SocialAccount not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        DB::table('candidate_social_account')->insert([
            'candidate_id' => $candidate->id,
            'social_account_id' => $socialAccount->id,
            'link' => $request->get('link')
        ]);
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccount added to candidate successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function bulkCandidateSocials(Request $request): JsonResponse {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'accounts' => 'nullable|array',
            'accounts.*.socialAccountId' => 'required',
            'accounts.*.link' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $candidate = $user->hasRole(['admin']) ? Candidate::find($request->get('candidateId')) : Candidate::find($user->candidate_id);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $candidate->socialAccounts()->detach();
        if ($request->has('accounts') && !empty($request->get('accounts'))) {
            foreach ($request->get('accounts') as $account) {
                DB::table('candidate_social_account')->insert([
                    'candidate_id' => $candidate->id,
                    'social_account_id' => $account['socialAccountId'],
                    'link' => $account['link'],
                ]);
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"candidate SocialAccounts bulk updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);

    }
    public function bulkCandidateSkills(Request $request): JsonResponse {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'skills' => 'nullable|array',
            'skills.*.skillId' => 'required',
            'skills.*.iconOnly' => 'required|boolean',
            'skills.*.percentage' => 'required_if:skills.*.iconOnly,false',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $candidate = $user->hasRole(['admin']) ? Candidate::find($request->get('candidateId')) : Candidate::find($user->candidate_id);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $candidate->skills()->detach();
        if ($request->has('skills') && !empty($request->get('skills'))) {
            foreach ($request->get('skills') as $skill) {
                DB::table('candidate_skill')->insert([
                    'candidate_id' => $candidate->id,
                    'skill_id' => $skill['skillId'],
                    'percentage' => $skill['percentage'],
                    'icon_only' => $skill['iconOnly']
                ]);
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"candidate Skills bulk updated successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);

    }
    public function deleteSocialAccount(Request $request): JsonResponse {
        $user = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'candidateId' => $user->hasRole(['admin']) ? 'required' : 'nullable',
            'socialAccountId' => 'required',
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
        $socialAccount = SocialAccount::find($request->get('socialAccountId'));
        if (!$socialAccount) {
            return response()->json([
                "code" => 404,
                "message" =>"SocialAccount not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $socialAccountExists = $candidate->socialAccounts()->where('social_account_id', $socialAccount->id)->exists();
        if ($socialAccountExists) $candidate->socialAccounts()->detach($socialAccount->id);
        return response()->json([
            "code" => 200,
            "message" =>"SocialAccount deleted from candidate successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function listCandidateSocialAccounts(Request $request): JsonResponse {
        $user = auth('api')->user();
        $candidateId = $user->candidate_id;
        $candidate = Candidate::find($candidateId);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $skills = $candidate->socialAccounts;
        return response()->json([
            "code" => 200,
            "message" =>"Candidate SocialAccounts retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $skills
        ]);
    }

    public function setDefault(int $candidateId) {
        $candidate = Candidate::find($candidateId);
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        Candidate::query()->update(['activated' => false]);
        $candidate->activated = true;
        $candidate->save();
        return response()->json([
            "code" => 200,
            "message" =>"Candidate set as default successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Activity;
use App\Models\Candidate;
use App\Models\Certificate;
use App\Models\ContactRequest;
use App\Models\CurriculumVitae;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Project;
use App\Models\Testimony;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MainController extends Controller
{
    private Candidate $candidate;

    public function __construct()
    {
        $this->candidate = Candidate::where('activated', true)->first();
    }

    public function metadata(): JsonResponse {
        $translatedCandidate = $this->candidate;

        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $translatedCandidate = $this->candidate->translate($candidateLanguage->id);
            }
        }

        return response()->json([
            "code" => 200,
            "message" =>"Metadata retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => [
                'title' => $translatedCandidate->first_name . ' ' . $translatedCandidate->last_name . ' | ' .$translatedCandidate->job_description,
                'image' => $translatedCandidate->picture_url,
                'description' => $translatedCandidate->about,
                'languages' => $translatedCandidate->languages
            ]
        ]);
    }

    public function profile(): JsonResponse {
        $translatedCandidate = Candidate::with([
            'activities' => function($q) {
                return $q->where('draft', false)->get();
            },
            'testimonies' => function($q) {
                return $q->where('draft', false)->get();
            },
            'educations' => function($q) {
                return $q->where('draft', false)->get();
            },
            'experiences' => function($q) {
                return $q->where('draft', false)->get();
            },
            'certificates' => function($q) {
                return $q->where('draft', false)->get();
            },
            'projects' => function($q) {
                return $q->where('draft', false)->get();
            },
            'skills',
            'languages'])->where('activated', true)->first();
        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $translatedCandidate = $translatedCandidate->translate($candidateLanguage->id);
                $translatedCandidate->activities->map(function ($activity) use ($candidateLanguage) {
                    return $activity->translate($candidateLanguage->id);
                });
                $translatedCandidate->testimonies->map(function ($testimony) use ($candidateLanguage) {
                    return $testimony->translate($candidateLanguage->id);
                });
                $translatedCandidate->educations->map(function ($education) use ($candidateLanguage) {
                    return $education->translate($candidateLanguage->id);
                });
                $translatedCandidate->experiences->map(function ($experience) use ($candidateLanguage) {
                    return $experience->translate($candidateLanguage->id);
                });
                $translatedCandidate->certificates->map(function ($certificate) use ($candidateLanguage) {
                    return $certificate->translate($candidateLanguage->id);
                });
                $translatedCandidate->skills->map(function ($skill) use ($candidateLanguage) {
                    return $skill->translate($candidateLanguage->id);
                });
                $translatedCandidate->projects->map(function ($project) use ($candidateLanguage) {
                    return $project->translate($candidateLanguage->id);
                });
            }
        }

        return response()->json([
            "code" => 200,
            "message" =>"Profile retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $translatedCandidate
        ]);
    }


    public function miniProfile(): JsonResponse {
        $translatedCandidate = $this->candidate;

        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $translatedCandidate = $this->candidate->translate($candidateLanguage->id);
            }
        }

        return response()->json([
            "code" => 200,
            "message" =>"Mini profile retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $translatedCandidate
        ]);
    }

    public function activities(): JsonResponse {
        $activities = Activity::published()->where('candidate_id','=',$this->candidate->id)->get();
        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $activities->map(function ($activity) use ($candidateLanguage) {
                    return $activity->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Activities retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $activities
        ]);
    }
    public function testimonies(): JsonResponse {
        $testimonies = Testimony::published()->where('candidate_id','=',$this->candidate->id)->get();
        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $testimonies->map(function ($testimony) use ($candidateLanguage) {
                    return $testimony->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Testimonies retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $testimonies
        ]);
    }
    public function education(): JsonResponse {
        $studies = Education::published()->where('candidate_id','=',$this->candidate->id)->get();
        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $studies->map(function ($education) use ($candidateLanguage) {
                    return $education->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Education retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $studies
        ]);
    }
    public function experiences(): JsonResponse {
        $experiences = Experience::published()->where('candidate_id','=',$this->candidate->id)->get();
        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $experiences->map(function ($experience) use ($candidateLanguage) {
                    return $experience->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Experiences retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $experiences
        ]);
    }
    public function certificates(): JsonResponse {
        $certificates = Certificate::published()->where('candidate_id','=',$this->candidate->id)->get();
        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $certificates->map(function ($certificate) use ($candidateLanguage) {
                    return $certificate->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Certificates retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $certificates
        ]);
    }
    public function skills(): JsonResponse {
        $skills = $this->candidate->skills;

        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $skills->map(function ($skill) use ($candidateLanguage) {
                    return $skill->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Skills retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $skills->groupBy('type')
        ]);
    }
    public function projects(): JsonResponse {
        $projects = Project::published()->where('candidate_id','=',$this->candidate->id)->get();

        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $projects->map(function ($project) use ($candidateLanguage) {
                    return $project->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Projects retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $projects
        ]);
    }
    public function project(int $projectId): JsonResponse {
        $project = Project::with('pictures')->where('id',$projectId)->first();
        if (!$project || $project->candidate_id !== $this->candidate->id) {
            return response()->json([
                "code" => 404,
                "message" =>"Project not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }

        if (request()->has('lang') && request()->get('lang') != null && request()->get('lang') !== 'en') {
            $candidateLanguage = $this->candidate->languages()->where('code','=',request()->get('lang'))->first();
            if ($candidateLanguage) {
                $project = $project->translate($candidateLanguage->id);
                $project->tasks->each(function ($task) use ($candidateLanguage) {
                    return $task->translate($candidateLanguage->id);
                });
            }
        }
        return response()->json([
            "code" => 200,
            "message" =>"Project retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $project
        ]);
    }
    public function resumes(): JsonResponse {
        $resumes = CurriculumVitae::where('candidate_id','=',$this->candidate->id)->where('public', true)->get();
        return response()->json([
            "code" => 200,
            "message" =>"Resumes retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $resumes
        ]);
    }
    public function contactRequest(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
            'captcha_token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        if (!check_captcha($request->get('captcha_token'))) {
            return response()->json([
                "code" => 415,
                "message" =>"Captcha Not Valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $data = $validator->getData();
        $contactRequest = new ContactRequest([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'candidate_id' => $this->candidate->id
        ]);
        $contactRequest->save();
        try {
            Mail::to($this->candidate->email)->send(new ContactMail($contactRequest));
        } catch (\Exception $e) {

        }
        return response()->json([
            "code" => 200,
            "message" =>"Contact Request sent successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

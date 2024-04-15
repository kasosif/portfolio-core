<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse {
        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }

        if ($token = auth()->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            return response()->json([
                "code" => 200,
                "message" =>"User logged in successfully",
                "resultType" => "SUCCESS",
                "result" => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 'never'
                ]
            ]);
        }
        return response()->json([
            "code" => 401,
            "message" =>"Unauthorized",
            "resultType" => "ERROR",
            "result" => null
        ], 401);

    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            "code" => 200,
            "message" =>"User logged out successfully",
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function me(): JsonResponse {
        $user = auth('api')->user();
        if ($user->candidate_id) {
            $user = Candidate::with(['languages' => function($q) {
                return $q->select('id','name','code');
            }])->where('id', $user->candidate_id)->first();
        }
        return response()->json([
            "code" => 200,
            "message" =>"User retrieved successfully",
            "resultType" => "SUCCESS",
            "result" => $user
        ]);
    }

    public function updateProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'jobDescription' => 'nullable',
            'about' => 'nullable',
            'dateOfBirth' => 'date|nullable',
            'phoneNumber' => 'nullable',
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
        $loggedIn = auth('api')->user();
        if ($loggedIn->candidate_id) {
            $candidate = Candidate::find($loggedIn->candidate_id);
            $candidate->first_name = $request->get('firstName');
            $candidate->last_name = $request->get('lastName');
            $candidate->job_description = $request->get('jobDescription');
            $candidate->about = $request->get('about');
            $candidate->date_of_birth = $request->get('dateOfBirth');
            $candidate->phone_number = $request->get('phoneNumber');
            $candidate->address = $request->get('address');
            $candidate->save();
            return response()->json([
                "code" => 200,
                "message" =>"Profile updated successfully",
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
}

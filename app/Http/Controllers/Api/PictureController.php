<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Picture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PictureController extends Controller
{
    public function upload(Request $request): JsonResponse {
        $user = auth('api')->user();
        $candidate = $user->hasRole(['admin']) ? $request->get('candidateId') : $user->candidate_id;
        $uploadMode = 'singleUpload';
        if (!$candidate) {
            return response()->json([
                "code" => 404,
                "message" =>"Candidate not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        $validationArray = [
            'addPictureTo' => ['required', 'regex:(activity|candidate|certificate|project|skill|testimony|language|social_account)'],
            'addPictureToId' => ['required','numeric']
        ];
        if (!$request->has('picture') && !$request->has('pictures')) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => ['picture' => ['picture field or pictures field can not be empty']]
            ], 415);
        }
        if ($request->has('picture')) {
            $validationArray['isMain'] = ['required','regex:(true|false)'];
            $validationArray['picture'] = [
                'required',
                File::types(['svg', 'png', 'jpg', 'webp'])
                    ->max(5 * 1024),
            ];
        } else {
            $uploadMode = 'multipleUpload';
            $validationArray['pictures.0'] = [
                'required',
                File::types(['svg', 'png', 'jpg', 'webp'])
                    ->max(5 * 1024),
            ];
            $validationArray['pictures.*'] = [
                File::types(['svg', 'png', 'jpg', 'webp'])
                    ->max(5 * 1024),
            ];
        }
        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $model = app('App\\Models\\'.ucfirst(Str::camel($request->get('addPictureTo'))));
        $instance = $model::find($request->get('addPictureToId'));
        if (!$instance) {
            return response()->json([
                "code" => 404,
                "message" => ucfirst(Str::camel($request->get('addPictureTo')))." not found",
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
        if ($uploadMode === 'singleUpload') {
            $instance->addPicture($request->file('picture'), $request->get('isMain') === 'true');
        } else {
            $instance->addPictures($request->file('pictures'));
        }
        return response()->json([
            "code" => 200,
            "message" => $uploadMode === 'singleUpload' ?  'picture uploaded to '.ucfirst($request->get('addPictureTo')). ' successfully' : 'pictures uploaded to '.ucfirst($request->get('addPictureTo')). ' successfully',
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function delete(Request $request): JsonResponse {
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
        $validator = Validator::make($request->all(), [
            'deletePictureFrom' => ['required', 'regex:(activity|candidate|certificate|project|skill|testimony|language|social_account)'],
            'deleteAll' => ['required', 'boolean'],
            'deletePictureFromId' => ['required','numeric'],
            'pictureId' => ['required_if:deleteAll,false']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $model = app('App\\Models\\'.ucfirst(Str::camel($request->get('deletePictureFrom'))));
        $instance = $model::find($request->get('deletePictureFromId'));
        if (!$instance) {
            return response()->json([
                "code" => 404,
                "message" => ucfirst(Str::camel($request->get('deletePictureFrom')))." not found",
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
        if ($request->get('deleteAll') === true) {
            $instance->deleteAllPictures();
        } else {
            $instance->deletePicture($request->get('pictureId'));
        }
        return response()->json([
            "code" => 200,
            "message" => $request->get('deleteAll') ?  'pictures removed from '.ucfirst($request->get('deletePictureFrom')). ' successfully' : 'picture removed from '.ucfirst($request->get('deletePictureFrom')). ' successfully',
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }

    public function download(int $pictureId): JsonResponse|BinaryFileResponse {
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
        $picture = Picture::find($pictureId);
        if (!$picture) {
            return response()->json([
                "code" => 404,
                "message" =>"Picture not found",
                "resultType" => "ERROR",
                "result" => null
            ], 404);
        }
        if ($picture->galleriable->candidate_id !== $user->candidate_id && !$user->hasRole(['admin'])) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        return response()->file(storage_path('app/'.$picture->path));
    }
    public function publicAccess(int $pictureId): BinaryFileResponse | JsonResponse {
        $picture = Picture::find($pictureId);
        if ($picture && $picture->public) {
            return response()->file(storage_path('app/'.$picture->path));
        }
        return response()->json([
            "code" => 404,
            "message" =>"Picture not found",
            "resultType" => "ERROR",
            "result" => null
        ], 404);
    }

    public function list(Request $request) {
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
        $validator = Validator::make($request->all(), [
            'getPicturesOf' => ['required', 'regex:(activity|candidate|certificate|project|skill|testimony|language)'],
            'getPicturesOfId' => ['required','numeric']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "code" => 415,
                "message" =>"Request not valid",
                "resultType" => "ERROR",
                "result" => $validator->errors()
            ], 415);
        }
        $model = app('App\\Models\\'.ucfirst($request->get('getPicturesOf')));
        $instance = $model::find($request->get('getPicturesOfId'));
        if (!$instance) {
            return response()->json([
                "code" => 404,
                "message" => "the ".ucfirst($request->get('getPicturesOf'))." to get pictures from is not found",
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
        return response()->json([
            "code" => 200,
            "message" => ucfirst($request->get('getPicturesOf')). ' pictures retrieved successfully',
            "resultType" => "SUCCESS",
            "result" => $instance->pictures
        ]);
    }
    public function setMain(int $pictureId) {
        $user = auth('api')->user();
        $picture = Picture::find($pictureId);
        if (!$picture) {
            return response()->json([
                "code" => 404,
                "message" => "Picture not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        if (!$picture->public) {
            return response()->json([
                "code" => 500,
                "message" => "Picture can not be main because it's not public",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        $instance = $picture->galleriable;
        if (!$user->hasRole(['admin']) && $instance->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        $instance->setMainPicture($picture->id);
        return response()->json([
            "code" => 200,
            "message" =>  ucfirst(Str::singular($instance->getTable())).' picture set as main successfully',
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
    public function togglePublic(int $pictureId) {
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
        $picture = Picture::find($pictureId);
        if (!$picture) {
            return response()->json([
                "code" => 404,
                "message" => "Picture not found",
                "resultType" => "ERROR",
                "result" => null
            ]);
        }
        $instance = $picture->galleriable;
        if (!$user->hasRole(['admin']) && $instance->candidate_id !== $user->candidate_id) {
            return response()->json([
                "code" => 401,
                "message" =>"Unauthorized",
                "resultType" => "ERROR",
                "result" => null
            ], 401);
        }
        if ($picture->public) {
            if ($picture->main) {
               return response()->json([
                    "code" => 500,
                    "message" =>  ucfirst(Str::singular($instance->getTable())).' picture can not be private , it\'s the main picture',
                    "resultType" => "ERROR",
                    "result" => null
                ], 500);
            }
        }
        $picture->public = !$picture->public;
        $picture->save();
        $message = ucfirst(Str::singular($instance->getTable())).' picture set public successfully';
        if (!$picture->public) {
            $message = ucfirst(Str::singular($instance->getTable())).' picture set private successfully';
        }
        return response()->json([
            "code" => 200,
            "message" =>  $message,
            "resultType" => "SUCCESS",
            "result" => null
        ]);
    }
}

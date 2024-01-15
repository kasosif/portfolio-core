<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api' ], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login','AuthController@login')->name('login');
        Route::group(['middleware' => 'jwt'], function () {
            Route::get('logout','AuthController@logout')->name('logout');
        });
    });
    Route::group(['prefix' => 'candidates', 'middleware' => 'jwt'], function () {
        Route::post('add-language','CandidateController@addLanguage')->name('addLanguageCandidate');
        Route::delete('delete-language','CandidateController@deleteLanguage')->name('deleteLanguageCandidate');
        Route::get('get-languages','CandidateController@listCandidateLanguages')->name('getCandidateLanguages');
        Route::post('add-skill','CandidateController@addSkill')->name('addSkillCandidate');
        Route::delete('delete-skill','CandidateController@deleteSkill')->name('deleteSkillCandidate');
        Route::get('get-skills','CandidateController@listCandidateSkills')->name('getCandidateSkills');
        Route::group(['middleware' => 'role_check:admin'], function () {
            Route::get('','CandidateController@list')->name('listCandidates');
            Route::get('{candidateId}','CandidateController@one')->name('getCandidate');
            Route::post('','CandidateController@add')->name('addCandidate');
            Route::delete('{candidateId}','CandidateController@delete')->name('deleteCandidate');
        });
    });
    Route::group(['prefix' => 'activities', 'middleware' => 'jwt'], function () {
        Route::get('','ActivityController@list')->name('listActivities');
        Route::post('','ActivityController@add')->name('addActivity');
        Route::put('{activityId}','ActivityController@update')->name('updateActivity');
        Route::delete('{activityId}','ActivityController@delete')->name('deleteActivity');
    });
    Route::group(['prefix' => 'certificates', 'middleware' => 'jwt'], function () {
        Route::get('','CertificateController@list')->name('listCertificates');
        Route::post('','CertificateController@add')->name('addCertificate');
        Route::put('{certificateId}','CertificateController@update')->name('updateCertificate');
        Route::delete('{certificateId}','CertificateController@delete')->name('deleteCertificate');
    });
    Route::group(['prefix' => 'education', 'middleware' => 'jwt'], function () {
        Route::get('','EducationController@list')->name('listEducation');
        Route::post('','EducationController@add')->name('addEducation');
        Route::put('{educationId}','EducationController@update')->name('updateEducation');
        Route::delete('{educationId}','EducationController@delete')->name('deleteEducation');
    });
    Route::group(['prefix' => 'experiences', 'middleware' => 'jwt'], function () {
        Route::get('','ExperienceController@list')->name('listExperiences');
        Route::post('','ExperienceController@add')->name('addExperience');
        Route::put('{experienceId}','ExperienceController@update')->name('updateExperience');
        Route::delete('{experienceId}','ExperienceController@delete')->name('deleteExperience');
        Route::post('tasks','ExperienceController@addTask')->name('addExperienceTask');
        Route::delete('tasks/delete/{taskId}','ExperienceController@deleteTask')->name('deleteExperienceTask');
    });
    Route::group(['prefix' => 'languages', 'middleware' => ['jwt']], function () {
        Route::get('','LanguageController@list')->name('listLanguages');
        Route::group(['middleware' => 'role_check:admin'], function () {
            Route::post('', 'LanguageController@add')->name('addLanguage');
            Route::put('{languageId}', 'LanguageController@update')->name('updateLanguage');
            Route::delete('{languageId}', 'LanguageController@delete')->name('deleteLanguage');
        });
    });
    Route::group(['prefix' => 'skills', 'middleware' => ['jwt']], function () {
        Route::get('','SkillController@list')->name('listSkills');
        Route::group(['middleware' => 'role_check:admin'], function () {
            Route::post('', 'SkillController@add')->name('addSkill');
            Route::put('{skillId}', 'SkillController@update')->name('updateSkill');
            Route::delete('{skillId}', 'SkillController@delete')->name('deleteSkill');
        });
    });
    Route::group(['prefix' => 'socials', 'middleware' => 'jwt'], function () {
        Route::get('','SocialAccountController@list')->name('listSocialAccounts');
        Route::post('','SocialAccountController@add')->name('addSocialAccount');
        Route::put('{socialAccountId}','SocialAccountController@update')->name('updateSocialAccount');
        Route::delete('{socialAccountId}','SocialAccountController@delete')->name('deleteSocialAccount');
    });
    Route::group(['prefix' => 'testimonies', 'middleware' => 'jwt'], function () {
        Route::get('','TestimonyController@list')->name('listTestimonies');
        Route::post('','TestimonyController@add')->name('addTestimony');
        Route::put('{testimonyId}','TestimonyController@update')->name('updateTestimony');
        Route::delete('{testimonyId}','TestimonyController@delete')->name('deleteTestimony');
    });
    Route::group(['prefix' => 'contact-requests', 'middleware' => ['jwt','role_check:admin']], function () {
        Route::get('{contactRequestId?}','ContactRequestController@list')->name('listContactRequests');
    });
});

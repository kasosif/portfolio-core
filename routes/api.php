<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\MainController;

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
    Route::controller(MainController::class)->as('main.')->prefix('main')->group(function () {
        Route::get('profile','profile')->name('profile');
        Route::get('languages','spokenLanguages')->name('spokenLanguages');
        Route::get('miniProfile','miniProfile')->name('miniProfile');
        Route::get('activities','activities')->name('activities');
        Route::get('testimonies','testimonies')->name('testimonies');
        Route::get('education','education')->name('education');
        Route::get('experiences','experiences')->name('experiences');
        Route::get('certificates','certificates')->name('certificates');
        Route::get('resumes','resumes')->name('resumes');
        Route::get('skills','skills')->name('skills');
        Route::get('projects','projects')->name('projects');
        Route::get('project/{projectId}','project')->name('project');
        Route::post('contact-request','contactRequest')->name('contactRequest');
    });
    Route::group(['prefix' => 'cdn'], function () {
        Route::group(['prefix' => 'pictures'], function () {
            Route::get('{pictureId}','PictureController@publicAccess')->name('getPublicPicture');
        });
        Route::group(['prefix' => 'resumes'], function () {
            Route::get('{resumeId}','CurriculumVitaeController@publicAccess')->name('getPublicResume');
        });
    });
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login','AuthController@login')->name('login');
        Route::group(['middleware' => 'jwt'], function () {
            Route::get('logout','AuthController@logout')->name('logout');
        });
    });
    Route::group(['prefix' => 'candidates', 'as' => 'backoffice.candidates.', 'middleware' => 'jwt'], function () {
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
            Route::put('set-default/{candidateId}','CandidateController@setDefault')->name('setDefaultCandidate');
        });
    });
    Route::group(['prefix' => 'activities', 'as' => 'backoffice.activities.', 'middleware' => 'jwt'], function () {
        Route::get('','ActivityController@list')->name('listActivities');
        Route::post('','ActivityController@add')->name('addActivity');
        Route::put('{activityId}','ActivityController@update')->name('updateActivity');
        Route::delete('{activityId}','ActivityController@delete')->name('deleteActivity');
    });
    Route::group(['prefix' => 'certificates', 'as' => 'backoffice.certificates.', 'middleware' => 'jwt'], function () {
        Route::get('','CertificateController@list')->name('listCertificates');
        Route::post('','CertificateController@add')->name('addCertificate');
        Route::put('{certificateId}','CertificateController@update')->name('updateCertificate');
        Route::delete('{certificateId}','CertificateController@delete')->name('deleteCertificate');
    });
    Route::group(['prefix' => 'education', 'as' => 'backoffice.education.', 'middleware' => 'jwt'], function () {
        Route::get('','EducationController@list')->name('listEducation');
        Route::post('','EducationController@add')->name('addEducation');
        Route::put('{educationId}','EducationController@update')->name('updateEducation');
        Route::delete('{educationId}','EducationController@delete')->name('deleteEducation');
    });
    Route::group(['prefix' => 'experiences', 'as' => 'backoffice.experiences.', 'middleware' => 'jwt'], function () {
        Route::get('','ExperienceController@list')->name('listExperiences');
        Route::post('','ExperienceController@add')->name('addExperience');
        Route::put('{experienceId}','ExperienceController@update')->name('updateExperience');
        Route::delete('{experienceId}','ExperienceController@delete')->name('deleteExperience');
        Route::post('tasks','ExperienceController@addTask')->name('addExperienceTask');
        Route::delete('tasks/delete/{taskId}','ExperienceController@deleteTask')->name('deleteExperienceTask');
    });
    Route::group(['prefix' => 'projects', 'as' => 'backoffice.projects.', 'middleware' => 'jwt'], function () {
        Route::get('','ProjectController@list')->name('listProjects');
        Route::post('','ProjectController@add')->name('addProject');
        Route::put('{projectId}','ProjectController@update')->name('updateProject');
        Route::delete('{projectId}','ProjectController@delete')->name('deleteProject');
        Route::post('tasks','ProjectController@addTask')->name('addProjectTask');
        Route::delete('tasks/delete/{taskId}','ProjectController@deleteTask')->name('deleteProjectTask');
    });
    Route::group(['prefix' => 'languages', 'as' => 'backoffice.languages.', 'middleware' => ['jwt']], function () {
        Route::get('','LanguageController@list')->name('listLanguages');
        Route::group(['middleware' => 'role_check:admin'], function () {
            Route::post('', 'LanguageController@add')->name('addLanguage');
            Route::put('{languageId}', 'LanguageController@update')->name('updateLanguage');
            Route::delete('{languageId}', 'LanguageController@delete')->name('deleteLanguage');
        });
    });
    Route::group(['prefix' => 'skills', 'as' => 'backoffice.skills.', 'middleware' => ['jwt']], function () {
        Route::get('','SkillController@list')->name('listSkills');
        Route::group(['middleware' => 'role_check:admin'], function () {
            Route::post('', 'SkillController@add')->name('addSkill');
            Route::put('{skillId}', 'SkillController@update')->name('updateSkill');
            Route::delete('{skillId}', 'SkillController@delete')->name('deleteSkill');
        });
    });
    Route::group(['prefix' => 'socials', 'as' => 'backoffice.socials.', 'middleware' => 'jwt'], function () {
        Route::get('','SocialAccountController@list')->name('listSocialAccounts');
        Route::post('','SocialAccountController@add')->name('addSocialAccount');
        Route::put('{socialAccountId}','SocialAccountController@update')->name('updateSocialAccount');
        Route::delete('{socialAccountId}','SocialAccountController@delete')->name('deleteSocialAccount');
    });
    Route::group(['prefix' => 'testimonies', 'as' => 'backoffice.testimonies.', 'middleware' => 'jwt'], function () {
        Route::get('','TestimonyController@list')->name('listTestimonies');
        Route::post('','TestimonyController@add')->name('addTestimony');
        Route::put('{testimonyId}','TestimonyController@update')->name('updateTestimony');
        Route::delete('{testimonyId}','TestimonyController@delete')->name('deleteTestimony');
    });
    Route::group(['prefix' => 'translations', 'as' => 'backoffice.translations.', 'middleware' => 'jwt'], function () {
        Route::post('translate', 'TranslationController@translate')->name('addTranslation');
        Route::post('get-translatable-fields', 'TranslationController@getTranslatableFields')->name('getTranslatableFields');
    });
    Route::group(['prefix' => 'resumes', 'as' => 'backoffice.resumes.', 'middleware' => 'jwt'], function () {
        Route::get('', 'CurriculumVitaeController@list')->name('listResumes');
        Route::post('', 'CurriculumVitaeController@add')->name('addResume');
        Route::get('download/{resumeId}', 'CurriculumVitaeController@download')->name('downloadResume');
        Route::delete('{resumeId}', 'CurriculumVitaeController@delete')->name('deleteResume');
        Route::put('toggle-public/{resumeId}', 'CurriculumVitaeController@togglePublic')->name('togglePublicResume');
    });
    Route::group(['prefix' => 'pictures', 'as' => 'backoffice.pictures.', 'middleware' => 'jwt'], function () {
        Route::post('', 'PictureController@upload')->name('uploadPictures');
        Route::get('download/{pictureId}', 'PictureController@download')->name('downloadPicture');
        Route::delete('', 'PictureController@delete')->name('deletePictures');
        Route::post('list-pictures', 'PictureController@list')->name('listPictures');
        Route::put('set-main/{pictureId}', 'PictureController@setMain')->name('setPictureMain');
        Route::put('toggle-public/{pictureId}', 'PictureController@togglePublic')->name('togglePublicPicture');
    });
    Route::group(['prefix' => 'contact-requests', 'as' => 'backoffice.contact-requests.', 'middleware' => ['jwt','role_check:admin']], function () {
        Route::get('{contactRequestId?}','ContactRequestController@list')->name('listContactRequests');
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OauthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group([
//     'middleware' => 'cors'
// ], function () {
//     Route::post('/register', 'UserController@register');
//     Route::post('/login', 'UserController@login');
//     Route::post('/auth/refresh', 'UserController@refresh');
//     Route::get('/check-alive', 'APIController@check_alive');
// });

Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::post('/auth/refresh', 'UserController@refresh');
Route::post('/resend-confirm', 'UserController@resendConfirm');
Route::post('/auth/reset-password', 'UserController@requestChangePassword');
Route::post('/auth/verify-reset-password', 'UserController@verifyReset');
Route::get('/check-alive', 'APIController@check_alive');
Route::post('/check-login-available', 'UserController@checkLoginAvailable');

Route::get('/auth/{social}/url', 'Auth\OauthController@loginUrl');
Route::get('/auth/{social}/callback', 'Auth\OauthController@loginCallback');
Route::post('/auth/create-social', 'Auth\OauthController@createAccountWithSocialProvider');
Route::post('/auth/attach-social', 'Auth\OauthController@attachUserWithSocialProvider');
// Route::get('/auth/{social}/url', [OauthController::class, 'loginUrl']);

Route::apiResource('users', 'User\UserResourceController')->only([
    'index', 'show'
]);
Route::apiResource('topics', 'Topic\TopicController')->only([
    'index', 'show'
]);
// All logged in user
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/upload', 'AssetController\FileUploadController');
    Route::get('/upload/presigned', 'AssetController\GetPresignedController');
    Route::get('/check-passport', 'UserController@check_passport');
    Route::post('/logout', 'UserController@logout');
    Route::get('/courses/fetch/{id}', 'CourseController\CourseFetchController');
    Route::post('chats/private', 'Chat\SendPrivateChatController');
    Route::get('/chat-room/{id}', 'Chat\GetRoomController');
    Route::apiResource('chats', 'Chat\ChatController');
    Route::apiResource('users', 'User\UserResourceController')->only([
        'update'
    ]);
    Route::get('/fetch-my-data', 'User\FetchDataController');
    Route::get('/user/{social}/attach-social', 'User\AttachSocialAccount');
    Route::post('/user/detach-social', 'User\DetachSocialAccount');

    //Notification
    Route::apiResource('notifications', 'Notification\NotificationController');
});

// Admin user
Route::group(['middleware' => ['auth:api', 'checkAdmin']], function () {
});

// Instructor user
Route::group(['middleware' => ['auth:api', 'checkInstructor']], function () {
    Route::apiResource('courses', 'CourseController\CourseResourceController')->only([
        'store', 'update', 'destroy'
    ]);
    Route::post('/attach-topic', 'Course\AttachTopicController');
    Route::post('/detach-topic', 'Course\DetachTopicController');
});
// Student user
Route::group(['middleware' => ['auth:api', 'checkStudent']], function () {
    Route::get('/buy-course/{id}', 'CourseController\BuyCourseController');
    Route::apiResource('lessons', 'Lesson\LessonController')->only([
        'show'
    ]);
    Route::apiResource('live-lessons', 'LiveLesson\LiveLessonController')->only([
        'show'
    ]);
    Route::get('/sections/{section_id}/questions', 'Question\QuestionInSectionController');
    Route::post('/calculate-point', 'Question\CalculatePointController');
    Route::post('/rate-course', 'Student\RateCourseController');
});

Route::group(['middleware' => ['injectAuth:api']], function () {
    Route::apiResource('courses', 'CourseController\CourseResourceController')->only([
        'index', 'show'
    ]);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\UserController;
use App\Models\Message;
use Illuminate\Testing\TestComponent;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

#Route::get('api/getParamTest/{id}', [TestController::class, 'test']);
# все методы api будут тут
Route::group(['middleware' => 'api'], function () {
    # то, что не требует аутентификации
    Route::get('getParamTest', [TestController::class, 'test']);
    Route::get("course/getCourses", [CourseController::class, 'getCourses']);
    Route::post('login', [AuthController::class, 'login']); 
    Route::post('register', [AuthController::class, 'register']);
    Route::get('user/get/{id}', [UserController::class, 'get']);
    Route::get('user/getUsers/{ids}', [UserController::class, 'getByIds']);
    Route::get('tracker/teacherCourseStats', [TrackerController::class, 'teacherCourseStats']);
    # То, что требует аутентификацию
    Route::group(['middleware' => 'jwt.auth'], function () {

        // Доступно только teacher
        Route::group(['middleware' => 'checkRole:teacher'], function () { 
            #Route::get('getParamTest', [TestController::class, 'test']);
            
            // РАБОТА НАД КУРСОМ
            Route::post('course/create', [CourseController::class, 'create']);
            Route::get('course/get/{id}', [CourseController::class, 'get']);
            Route::get('course/getInvite/', [CourseController::class, 'getInvite']);

            // РАБОТА НАД УРОКОМ
            Route::post('lesson/create', [LessonController::class, 'create']);
            Route::get('lesson/get/{id}', [LessonController::class, 'get']);
            Route::get('lesson/getScript/{id}', [LessonController::class, 'getScript']);
            Route::get('lesson/delete/{id}', [LessonController::class, 'delete']);
            Route::post('homework/add', [HomeworkController::class, 'add']);
            
        });

        Route::group(['middleware' => 'checkRole:teacher,admin'], function()
        {
           Route::delete('course/delete', [CourseController::class, 'delete']);
           Route::get('course/getAll', [CourseController::class, 'getAll']);
        });

        Route::get('course/getAll', [CourseController::class, 'getAll']);

        #Route::get("course/getCourses", [CourseController::class, 'getCourses']);
        Route::get('course/getUserCourses', [CourseController::class, 'getUserCourses']);
        Route::post('user/setPhoto', [UserController::class, 'setPhoto']);
        Route::post('tracker/newTrack', [TrackerController::class, 'newTrack']);
        Route::get('course/invite/{guid}', [CourseController::class, 'connectByInvite']);
        Route::get('content/getById', [ContentController::class, 'get']);
        Route::get('content/getCourse', [ContentController::class, 'getCourse']);
        Route::get('tracker/courseProgress', [TrackerController::class, 'courseProgress']);
        Route::post('homework/suggest', [HomeworkController::class, 'suggest']);
        Route::post('homework/check', [HomeworkController::class, 'check']);



    });
});
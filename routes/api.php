<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//Result routes
Route::get('/results/student/{regNumber}/{year}/{semester}', [ResultController::class, 'getStudentTotalResult']);
Route::get('/results/student/{regNumber}/overall', [ResultController::class, 'getStudentOverallGPA']);

//Lecturer
Route::post('/lecturer/create', [LecturerController::class, 'store']);

Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);

    //Lecturer
    Route::post('/lecturer/create', [LecturerController::class, 'store']);
    Route::put('/lecturers/{id}/basic-info', [LecturerController::class, 'editBasicInfo']);
    Route::put('/lecturers/{id}/password', [LecturerController::class, 'editPassword']);
    Route::get('/lecturer', [LecturerController::class, 'index']);
    Route::get('/lecturers/{id}', [LecturerController::class, 'getById']);

    //Result routes
    Route::post('/results', [ResultController::class, 'store']);
    Route::put('/results/{regNumber}/{courseId}/{year}', [ResultController::class, 'update']);

    //Student routes
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);

    //courses
    Route::post('/courses', [CourseController::class, 'storeCourse']);
});

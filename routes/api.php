<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ImageController;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('survey', SurveyController::class);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('cards', CardController::class);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
Route::get('/survey/get-by-slug/{survey:slug}', [SurveyController::class, 'getBySlug']);
Route::post('/survey/{survey}/answer', [SurveyController::class, 'storeAnswer']);


Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/setUserOrder', [AuthController::class, 'setUserOrder']);
Route::get('/getUserOrder', [AuthController::class, 'getUserOrder']);


Route::post('/crOrder', [OrderController::class, 'create']);


Route::get('/getImages', [ImageController::class, 'getImages']);
Route::post('/addImage', [ImageController::class, 'addImage']);
Route::post('/destroyImage', [ImageController::class, 'destroy']);
Route::get('/getAllImages', [ImageController::class, 'getAllImages']);

Route::get('/allImages', [ImageController::class, 'getAllImagesNames']);


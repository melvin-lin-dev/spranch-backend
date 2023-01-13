<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\AuthController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\RelationController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
//    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);

        Route::resource('presentations', PresentationController::class);
        Route::get('favorited-presentations', [PresentationController::class, 'getFavoritedPresentations']);

        Route::prefix('presentations')->group(function () {
            Route::prefix('{presentation}')->group(function () {
                Route::patch('favorite', [PresentationController::class, 'manageFavorite']);
                Route::patch('images', [PresentationController::class, 'updateImages']);
                Route::patch('style', [PresentationController::class, 'updateStyle']);

                Route::resource('slides', SlideController::class);

                Route::prefix('slides')->group(function(){
                    Route::prefix('{slide}')->group(function(){
                        Route::patch('position', [SlideController::class, 'updatePosition']);
                        Route::patch('style', [SlideController::class, 'updateStyle']);
                        Route::patch('z-index', [SlideController::class, 'updateZIndex']);
                    });
                });

                Route::resource('relations', RelationController::class);

                Route::prefix('relations')->group(function(){
                    Route::prefix('{relation}')->group(function(){
                        Route::patch('style', [RelationController::class, 'updateStyle']);
                    });
                });
            });
        });
//    });
});

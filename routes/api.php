<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomPromptController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MainCategories;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\SelfVideoController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubCategories;
use App\Http\Controllers\TimelineController;
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

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
//    Route::post('/refresh', [AuthController::class, 'refresh']);
});


Route::group([
    'middleware' => 'jwt.verify',
], function ($router) {

    Route::get('/classes', [AuthController::class, 'complete_profile']);
});

Route::get('/php-info', function (){
    phpinfo();
});


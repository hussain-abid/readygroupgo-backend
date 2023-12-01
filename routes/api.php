<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\CustomPromptController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\InviteController;
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

    Route::get('/classes', [ClassesController::class, 'get_classes']);
    Route::get('/class/{class_id}', [ClassesController::class, 'get_single_class']);
    Route::get('/class-details/{class_id}', [ClassesController::class, 'get_students']);

    Route::put('/add-class', [ClassesController::class, 'add_class']);
    Route::put('/add-student/{class_id}', [ClassesController::class, 'add_student_details']);

    Route::post('/update-class/{class_id}', [ClassesController::class, 'update_class']);
    Route::post('/update-student/{student_id}', [ClassesController::class, 'update_class_student']);


    Route::delete('/delete-class/{class_id}', [ClassesController::class, 'delete_class']);

    Route::post('/delete-students', [ClassesController::class, 'delete_students']); //its post as the delete method dont have access to the body


    Route::post('/update-sharable-group/{shareable_id}', [ClassesController::class, 'save_sharable_group']);

    Route::put('/create-invite-class', [InviteController::class, 'create_invite_class']);
    Route::get('/joined-class-students/{join_id}', [InviteController::class, 'get_invite_class_students']);

});

Route::get('/sharable-group/{shareable_id}', [ClassesController::class, 'get_sharable_group']);
Route::put('/join-invite/{join_id}', [InviteController::class, 'join_invite']);


Route::get('/php-info', function (){
    phpinfo();
});


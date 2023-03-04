<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;

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

Route::post('registration', [Login::class, 'registration']);
Route::post('login', [Login::class, 'login']);

Route::controller(AuthController::class)->middleware("auth:api")->group(function () {
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

    Route::post('saveTask', [TaskController::class, 'saveTask']);
    Route::post('manageTask', [TaskController::class, 'manageTask']);
    Route::post('selectTask/{id}', [TaskController::class, 'selectTask']);
    Route::post('updateTask', [TaskController::class, 'updateTask']);
    Route::post('deleteTask/{id}', [TaskController::class, 'deleteTask']);

    Route::post('saveTodo', [TodoController::class, 'saveTodo']);
    Route::post('manageTodo', [TodoController::class, 'manageTodo']);
    Route::post('selectTodo/{id}', [TodoController::class, 'selectTodo']);
    Route::post('updateTodo', [TodoController::class, 'updateTodo']);
    Route::post('deleteTodo/{id}', [TodoController::class, 'deleteTodo']);

    Route::post('saveUser', [UserController::class, 'saveUser']);
    Route::post('manageUser', [UserController::class, 'manageUser']);
    Route::post('selectUser/{id}', [UserController::class, 'selectUser']);
    Route::post('updateUser', [UserController::class, 'updateUser']);
    Route::post('deleteUser/{id}', [UserController::class, 'deleteUser']);
});
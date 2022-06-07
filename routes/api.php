<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\admin\UsersController;
use App\Http\Controllers\API\admin\RoleController;
use App\Http\Controllers\API\admin\PermissionController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        //usuarios
        // Route::get('/getUsers', [UsersController::class, 'usersList']);
        //Roles
        // Route::get('/getRoles', [RoleController::class, 'getList']);
        // Route::get('detail/{role}', [RoleController::class, 'getDetail']);
        // Route::get('/getRoles', [RoleController::class, 'getList']);
        //permisos
        Route::get('permissions', [PermissionController::class, 'getList']);
        //test composition api
        // Route::get('/create', [UsersController::class, 'invoices']);
        //users
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UsersController::class, 'usersList']);
            Route::post('create', [UsersController::class, 'create']);
            Route::get('detail/{user}', [UsersController::class, 'getDetail']);
            Route::post('update/{user}', [UsersController::class, 'update']);
            Route::post('delete/{user}', [UsersController::class, 'delete']);
        });
        //roles
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [RoleController::class, 'getList']);
            Route::get('/{role}', [RoleController::class, 'getOne']);
            Route::post('create', [RoleController::class, 'create']);
            Route::get('detail/{role}', [RoleController::class, 'getDetail']);
            Route::post('update/{role}', [RoleController::class, 'update']);
            Route::post('delete/{role}', [RoleController::class, 'delete']);
        });
    });
});

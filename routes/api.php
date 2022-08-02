<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
//admin cntrollers
use App\Http\Controllers\API\admin\UsersController;
use App\Http\Controllers\API\admin\RoleController;
use App\Http\Controllers\API\admin\PermissionController;
//employee controllers
use App\Http\Controllers\API\employee\DirectorioReclamoController;
use App\Http\Controllers\API\admin\SuperstructuraController;
use App\Http\Controllers\API\admin\Permission;
use App\Http\Controllers\API\admin\PapeletaController;
use App\Http\Controllers\API\ParameterController;
use App\Http\Controllers\API\admin\VacacionesDocumentoController;
use App\Http\Controllers\API\admin\IncorporacionesController;
use App\Http\Controllers\API\admin\CambioTurnoController;
use App\Http\Controllers\API\admin\PersonaControllers;
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
    Route::get('/soporte', [DirectorioReclamoController::class, 'getList']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

        Route::get('/superstructura', [SuperstructuraController::class, 'getList']);
        Route::get('/parameter/{varia}', [ParameterController::class, 'parameter_pluck']);
        Route::get('/parameter_turno/{varia}', [ParameterController::class, 'parameter_turno']);
        Route::get('/parameter_check/{varia}', [ParameterController::class, 'parameter_check']);
        //usuarios
        // Route::get('/getUsers', [UsersController::class, 'usersList']);
        //Roles
        // Route::get('/getRoles', [RoleController::class, 'getList']);
        // Route::get('detail/{role}', [RoleController::class, 'getDetail']);
        // Route::get('/getRoles', [RoleController::class, 'getList']);
        //permisos
        Route::get('getpersonas', [PersonaControllers::class, 'personList']);
        Route::get('permissions', [PermissionController::class, 'getList']);
        //test composition api
        // Route::get('/create', [UsersController::class, 'invoices']);
        //users
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UsersController::class, 'usersList']);
            Route::post('create', [UsersController::class, 'create']);
            Route::get('pluck', [UsersController::class, 'getPluck']);
            Route::get('tipo_permisos', [UsersController::class, 'getTipoPermiso']);
            Route::get('detail/{user}', [UsersController::class, 'getDetail']);
            Route::post('update/{user}', [UsersController::class, 'update']);
            Route::post('delete/{user}', [UsersController::class, 'delete']);
        });
        //roles
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [RoleController::class, 'getList']);
            Route::get('pluck', [RoleController::class, 'getPluck']);
            Route::get('/{role}', [RoleController::class, 'getOne']);
            Route::post('create', [RoleController::class, 'create']);
            Route::get('detail/{role}', [RoleController::class, 'getDetail']);
            Route::post('update/{role}', [RoleController::class, 'update']);
            Route::post('delete/{role}', [RoleController::class, 'delete']);
        });
        //permisos
        Route::group(['prefix' => 'permission'], function () {
            Route::get('/', [PermissionController::class, 'list']);
            Route::post('create', [PermissionController::class, 'create']);
            Route::get('detail/{permission}', [PermissionController::class, 'getDetail']);
            Route::post('update/{permission}', [PermissionController::class, 'update']);
            Route::post('delete/{permission}', [PermissionController::class, 'delete']);
        });
        //papeletas
        Route::group(['prefix' => 'papeleta'], function () {
            Route::get('/', [PapeletaController::class, 'getList']);
            Route::post('create', [PapeletaController::class, 'create']);
            Route::get('detail/{papeleta}', [PapeletaController::class, 'getDetail']);
            Route::post('update/{papeleta}', [PapeletaController::class, 'update']);
            Route::post('observar', [PapeletaController::class, 'observar']);
            Route::post('updateStatus/{papeleta}', [PapeletaController::class, 'update_estado']);
            Route::post('delete/{papeleta}', [PapeletaController::class, 'delete']);
            Route::post('/asignar', [PapeletaController::class, 'asignar']);
        });
        //vacaciones
        Route::group(['prefix' => 'vacaciones'], function () {
            Route::get('/report/{dni}', [VacacionesDocumentoController::class, 'report']);
            Route::get('/report/vacaciones/{dni}', [VacacionesDocumentoController::class, 'vacaciones']);
            Route::get('/report/documentos/{dni}', [VacacionesDocumentoController::class, 'documentos']);
            Route::get('/report/papeletas/{dni}', [VacacionesDocumentoController::class, 'papeletas']);
            Route::get('/report/periodo/{dni}', [VacacionesDocumentoController::class, 'periodo_pluck']);
            Route::get('/report/getpersona/{dni}', [VacacionesDocumentoController::class, 'getPersona']);
        });
        //vacaciones
        Route::group(['prefix' => 'incorporaciones'], function () {
            Route::get('/', [IncorporacionesController::class, 'getList']);
            Route::post('/create', [IncorporacionesController::class, 'store']);
            Route::post('/status', [IncorporacionesController::class, 'status']);
            Route::get('detail/{incorporaciones}', [IncorporacionesController::class, 'getDetail']);
            Route::post('update/{incorporaciones}', [IncorporacionesController::class, 'update']);
            Route::get('members/{incorporaciones}', [IncorporacionesController::class, 'getMembers']);
            Route::post('/validation', [IncorporacionesController::class, 'validacion']);
            Route::post('/personas', [IncorporacionesController::class, 'personas']);
        });
        //cambios de turno
        Route::group(['prefix' => 'cambios_turno'], function () {
            Route::get('/', [CambioTurnoController::class, 'getList']);
            Route::post('/create', [CambioTurnoController::class, 'store']);
            Route::get('/verify_solicitante/{dni}', [CambioTurnoController::class, 'verificar_solicitante']);
            Route::get('/verify_aceptante/{dni}', [CambioTurnoController::class, 'verificar_aceptante']);
            Route::get('detail/{id}', [CambioTurnoController::class, 'getDetail']);
            Route::post('update/{id}', [CambioTurnoController::class, 'update']);
            Route::post('obs/{id}', [CambioTurnoController::class, 'observar']);
            Route::post('updateStatus/{id}', [CambioTurnoController::class, 'update_estado']);
            Route::post('delete/{id}', [CambioTurnoController::class, 'delete']);
        });
    });
});

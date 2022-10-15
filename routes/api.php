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
use App\Http\Controllers\API\admin\PersonalBajaController;
use App\Http\Controllers\API\admin\PersonalInformesCeseController;
use App\Http\Controllers\API\admin\LegajosInformesCeseController;
use App\Http\Controllers\API\admin\ContratosController;
use App\Http\Controllers\API\admin\PersonalAreaController;
use App\Http\Controllers\API\ArchivoController;
use App\Http\Controllers\NotificationController;
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
    Route::post('sendfile', [PersonalBajaController::class, 'file']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::get('/notifications', [NotificationController::class, 'notifications']);
        Route::get('/superstructura', [SuperstructuraController::class, 'getList']);
        Route::get('/parameter/{varia}', [ParameterController::class, 'parameter_pluck']);
        Route::get('/parameter_turno/{varia}', [ParameterController::class, 'parameter_turno']);
        Route::get('/parameter_check/{varia}', [ParameterController::class, 'parameter_check']);
        Route::get('/archivos/{id}', [ArchivoController::class, 'file']);
        //permisos
        Route::get('getpersonas', [PersonaControllers::class, 'personList']);
        Route::get('permissions', [PermissionController::class, 'getList']);

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
        //personal bajas
        Route::group(['prefix' => 'personal_bajas'], function () {
            Route::get('/', [PersonalBajaController::class, 'index']);
            Route::get('select/', [PersonalBajaController::class, 'select']);
            Route::post('/create', [PersonalBajaController::class, 'create']);
            Route::post('/create', [PersonalBajaController::class, 'create']);
            Route::get('file/{name}', [PersonalBajaController::class, 'getfile']);
            Route::get('detail/{id}', [PersonalBajaController::class, 'getDetail']);
            Route::post('update/{id}', [PersonalBajaController::class, 'update']);
            Route::post('modal', [PersonalBajaController::class, 'modal']);
            Route::post('obs/{id}', [PersonalBajaController::class, 'observar']);
            Route::post('updateStatus/{id}', [PersonalBajaController::class, 'update_estado']);
            Route::post('delete/{id}', [PersonalBajaController::class, 'delete']);
            Route::post('anular', [PersonalBajaController::class, 'anular']);
        });
        //control informe cese
        Route::group(['prefix' => 'informe_cese'], function () {
            Route::get('/', [PersonalInformesCeseController::class, 'index']);
            Route::post('/generar_informe', [PersonalInformesCeseController::class, 'generarInforme']);
            Route::get('file/{name}', [PersonalInformesCeseController::class, 'getfile']);
            Route::get('/getuser', [PersonalInformesCeseController::class, 'getusers']);
            Route::post('/create', [PersonalInformesCeseController::class, 'create']);
            Route::get('/detalle/{id}', [PersonalInformesCeseController::class, 'detalle']);
            // Route::get('file/{name}', [PersonalBajaController::class, 'getfile']);
            Route::get('detail/{id}', [PersonalInformesCeseController::class, 'getDetail']);
            Route::post('update/{id}', [PersonalInformesCeseController::class, 'update']);
            // Route::post('modal', [PersonalBajaController::class, 'modal']);
            // Route::post('obs/{id}', [PersonalBajaController::class, 'observar']);
            // Route::post('updateStatus/{id}', [PersonalBajaController::class, 'update_estado']);
            Route::post('delete/{id}', [PersonalInformesCeseController::class, 'delete']);
            //legajos informe cese
        });
        Route::group(['prefix' => 'legajo_cese'], function () {
            Route::get('/', [LegajosInformesCeseController::class, 'index']);
            Route::post('/generar_informe', [LegajosInformesCeseController::class, 'generarInforme']);
            Route::get('file/{name}', [LegajosInformesCeseController::class, 'getfile']);
            Route::get('/getuser', [LegajosInformesCeseController::class, 'getusers']);
            Route::post('/create', [LegajosInformesCeseController::class, 'create']);
            Route::get('select/', [LegajosInformesCeseController::class, 'select']);
            Route::post('modal', [LegajosInformesCeseController::class, 'modal']);
            Route::post('modalName', [LegajosInformesCeseController::class, 'modalName']);
            // Route::get('parameterName/{id}', [LegajosInformesCeseController::class, 'parameterName']);
            // Route::post('/create', [LegajosBajaController::class, 'create']);
            // Route::get('file/{name}', [LegajosBajaController::class, 'getfile']);
            Route::get('detail/{id}', [LegajosInformesCeseController::class, 'getDetail']);
            Route::post('update/{id}', [LegajosInformesCeseController::class, 'update']);
            // Route::post('modal', [LegajosBajaController::class, 'modal']);
            // Route::post('obs/{id}', [LegajosBajaController::class, 'observar']);
            // Route::post('updateStatus/{id}', [LegajosBajaController::class, 'update_estado']);
            Route::post('delete/{id}', [LegajosInformesCeseController::class, 'delete']);
            Route::post('licencia/delete/{id}', [LegajosInformesCeseController::class, 'delete_licencia']);
        });
        Route::group(['prefix' => 'persona'], function () {
            Route::get('/', [PersonaControllers::class, 'index']);
            Route::get('estructura', [PersonaControllers::class, 'estructura']);
            Route::post('/create', [PersonaControllers::class, 'create']);
            Route::get('detail/{id}', [PersonaControllers::class, 'getDetail']);
            Route::post('update/{id}', [PersonaControllers::class, 'update']);
            Route::post('updateStatus/{id}', [PersonaControllers::class, 'update_estado']);
            Route::post('delete/{id}', [PersonaControllers::class, 'delete']);
        });
        Route::group(['prefix' => 'contrato'], function () {
            Route::get('/', [ContratosController::class, 'index']);
            Route::post('/create', [ContratosController::class, 'create']);
            Route::get('detail/{id}', [ContratosController::class, 'getDetail']);
            Route::post('update/{id}', [ContratosController::class, 'update']);
            Route::post('statusfirma/{id}', [ContratosController::class, 'status_firma']);
            Route::post('statuscontrato/{id}', [ContratosController::class, 'status_contrato']);
            Route::post('delete/{id}', [ContratosController::class, 'delete']);
            Route::get('getpersona/{id}', [ContratosController::class, 'getPersona']);
        });
        Route::group(['prefix' => 'personal_area'], function () {
            Route::get('/', [PersonalAreaController::class, 'index']);
            Route::post('/getList', [PersonalAreaController::class, 'getList']);
            Route::post('/create', [PersonalAreaController::class, 'create']);
            Route::post('delete/{id}', [PersonalAreaController::class, 'delete']);
        });
    });
});

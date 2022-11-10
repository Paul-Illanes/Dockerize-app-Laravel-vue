<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalAreasVacaciones;
use App\Models\PersonalArea;

class PersonalAreasVacacionesController extends Controller
{
    public function index()
    {
        $personalAreasVacaciones = PersonalArea::select('personal_areas_vacaciones.id', 'personal_areas.dependencia_id', 'personal_areas.supestructura_id', 'personal_areas.area', 'dependencia.alias AS dependencia', 'supestructura.alias AS supestructura', 'personal_areas_vacaciones.periodo_vacaciones', 'personal_areas_vacaciones.status')
            ->leftJoin('personal_areas_vacaciones', 'personal_areas_vacaciones.id', '=', 'personal_areas.id')
            ->join('cms_parameters AS supestructura', 'supestructura.value', 'personal_areas.supestructura_id')
            ->join('cms_parameters AS dependencia', 'dependencia.value', 'personal_areas.dependencia_id')
            ->get();
        // $personalAreasVacaciones = PersonalAreasVacaciones::select(
        //     'personal_areas_vacaciones.id',
        //     'personal_areas_vacaciones.periodo_vacaciones',
        //     'personal_areas_vacaciones.status',
        //     'personal_areas.supestructura_id',
        //     'personal_areas.dependencia_id',
        //     'personal_areas.area'
        //     // 'personal_areas_vacaciones.status'
        // )
        //     ->rightJoin('personal_areas', 'personal_areas_vacaciones.personal_area_id', '=', 'personal_areas.id')
        //     ->get();
        return response()->json($personalAreasVacaciones);
    }
    public function status(Request $request, $id)
    {
        $baja = PersonalAreasVacaciones::Find($id);
        $baja->status = $request->status;
        $baja->save();
    }
}

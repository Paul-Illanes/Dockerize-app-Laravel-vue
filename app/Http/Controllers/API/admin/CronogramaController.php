<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CronogramaController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $permisos_subestructura = DB::table('user_has_supestructura')
            //->select('supestructura_id')
            ->where('user_id', $user_id)
            ->pluck('supestructura_id');

        $user_dependencias = DB::table('user_has_dependencia')
            ->where('user_id', $user_id)
            ->pluck('dependencia_id');
        // ->toArray();
        //dd($user_dependencias);
        $personalAreas = PersonalArea::whereIn('supestructura_id', $permisos_subestructura)
            ->whereIn('dependencia_id', $user_dependencias)
            ->get();

        return view('personal-area.index', compact('personalAreas'))
            ->with('i');
    }
}

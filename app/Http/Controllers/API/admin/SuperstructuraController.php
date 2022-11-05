<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supestructura;
use App\Models\CmsParameter;
use App\Models\CmsParameterGroup;

class SuperstructuraController extends Controller
{
    function getList()
    {
        $superstructuras = Supestructura::select('id AS value', 'descripcion AS name')->get();

        return response()->json($superstructuras);
    }
    function getDependencia()
    {
        $groupId = "dependencia";  // 'dependencia'
        $parentId = 12;
        $dependencias = CmsParameter::select('id', 'alias AS name', 'value')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $groupId)->pluck('id'))
            ->where('parent_id', $parentId)
            ->where('active', true)
            ->orderBy('name')->get();

        // $cod = $request->cod;
        // $dependencias = CmsParameter::select('alias as name', 'value')->where('metadata->cod_supestructura', $cod)->get();
        return response()->json($dependencias);
    }
}

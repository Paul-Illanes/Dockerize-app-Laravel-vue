<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsParameter;
use App\Models\CmsParameterGroup;

class ParameterController extends Controller
{
    public function parameter_pluck($group_alias, $parent_id = null)
    {

        $datos = CmsParameter::select('alias AS name', 'id')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        return response()->json($datos);
    }
    public function parameter_pluck_incorporaciones($group_alias, $parent_id = null)
    {

        $datos = CmsParameter::select('alias AS name', 'alias AS id')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        return response()->json($datos);
    }
    public function parameter_check($group_alias, $parent_id = null)
    {

        $datos = CmsParameter::select('alias AS name', 'id', 'metadata->permission AS permiso')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        return response()->json($datos);
    }
    public function parameter_turno($group_alias, $parent_id = null)
    {

        $datos = CmsParameter::select('alias AS name', 'value')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        return response()->json($datos);
    }
}

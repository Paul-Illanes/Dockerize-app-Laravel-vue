<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsParameter;
use App\mODELS\CmsParameterGroup;

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
}

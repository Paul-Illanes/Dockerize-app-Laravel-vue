<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
// use App\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    /**
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $permissions = Permission::all();

        return PermissionResource::collection($permissions);
    }
    public function list(Request $request)
    {
        $permissions = Permission::all();

        return response()->json($permissions);
    }
    public function getDetail(Request $request, Permission $permission)
    {
        $permissions = Permission::find($permission)->first();
        $data = [
            'status' => 200,
            'data' => $permissions
        ];
        return response()->json($permission);
    }
    public function Create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:permissions',
            'description' => 'required|string|max:191',
        ]);

        $result = Permission::create([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => 'web'
        ]);

        return response()->json($result);
    }
    public function Update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:permissions,name,' . $permission->id,
            'description' => 'required|string|max:191'
        ]);

        $permission->update([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => 'web'
        ]);

        return response()->json($permission);
    }
}

<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Persona;
use App\Models\Supestructura;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Models\Pin;
use App\Models\UserHasDependencia;

class UsersController extends Controller
{
    public function usersList(Request $request)
    {
        // $users = User::all();
        $users = User::with('roles')->get();
        // return response()->json($users);
        $data = [
            'status' => 200,
            'data' => $users
        ];
        return response()->json($data);
        // return UserResource::collection($users);
        // $roles = Role::all();

        // return RoleResource::collection($roles);
    }
    public function getPluck(Request $request)
    {
        // $users = User::all()->pluck('name' . 'last_name', 'id');
        // $users = DB::table('CONCAT(nombres, apellido_paterno, apellido_materno)', 'dni')->where('status', '!=', 2)->get();
        $users = DB::select('SELECT CONCAT(nombres, " ",apellido_paterno," ", apellido_materno) as name, dni as id FROM personas');
        return response()->json($users);
        // return RoleResource::collection($roles);
    }
    public function getTipoPermiso(Request $request)
    {
        // $users = User::all()->pluck('name' . 'last_name', 'id');
        // $users = DB::table('CONCAT(nombres, apellido_paterno, apellido_materno)', 'dni')->where('status', '!=', 2)->get();
        $permisos = DB::select('SELECT (tipo_permiso) as name, id FROM tipo_permisos');
        return response()->json($permisos);
        // return RoleResource::collection($roles);
    }
    public function getDetail(Request $request, User $user)
    {
        // $user = $request->user();
        $permissions = $user->getDirectPermissions()->pluck('name');
        // $permissions = $user->getDirectPermissions();
        $role = $user->getRoleNames();
        //jalas todas
        $supestructura = DB::table('user_has_supestructura')->where('user_id', $user->id)->pluck('supestructura_id');
        $dependencia = DB::table('user_has_dependencia')->where('user_id', $user->id)->pluck('dependencia_id');
        // $ability = new \stdClass();
        // $total = count($permissions);
        // $i = 0;
        // foreach ($permissions as $item) {
        //     $ability->ability[$i] = ['action' => $item, 'subject' => 'ACL'];
        //     $i++;
        // }
        // $homeAbility = $total + 1;
        // $ability->ability[$total] = ['action' => 'read', 'subject' => 'Auth'];
        // $ability->ability[$homeAbility] = ['action' => 'read', 'subject' => 'Home'];
        // $user->ability = $ability->ability;
        // $user->role = 'admin';
        $data = [
            'roles' => $role,
            'permissions' => $permissions,
            'user' => $user,
            'supestructura' => $supestructura,
            'dependencia' => $dependencia
        ];
        return response()->json($data);
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'mother_lastname' => 'required|string|max:191',
            'username' => 'required|int|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'roles' => 'required'
        ]);
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'mother_lastname' => $request->mother_lastname,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'celular' => $request->celular,
            'pin' => $request->pin,
            'verified' => $request->verified ? 1 : 0,
            'active' => $request->activo ? 1 : 0,
        ]);
        if ($user->id) {
            $pin_code = Pin::create([
                'user_id' => $user->id
            ]);
            // gerando el codigo de validacion de 5 digitos.
            $pin_code->sendCode();
            $user->assignRole($request->roles);
            $user->supestructuras()->sync($request->structuras);
            $user->syncPermissions($request->permisos);
            $data = [
                'status' => 200,
                'data' => $user,
                'message' => 'Registrado correctamente'
            ];
            return response()->json($data);
        }
    }
    public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'email' => 'required|email:filter|max:255|unique:users,email,' . $user->id,
            'name' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'mother_lastname' => 'required|string|max:191',
            'username' => 'required|int|unique:users,username,' . $user->id,
            // 'password' => 'required',
            // 'c_password' => 'required|same:password',
            'roles' => 'required'
        ]);
        if ($request->password) {
            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'mother_lastname' => $request->mother_lastname,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'celular' => $request->celular,
                'pin' => $request->pin,
                'verified' => $request->verified ? 1 : 0,
                'active' => $request->active ? 1 : 0,
            ]);
        }
        if (!$request->password) {
            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'mother_lastname' => $request->mother_lastname,
                'username' => $request->username,
                // 'password' => bcrypt($request->password),
                'celular' => $request->celular,
                'pin' => $request->pin,
                'verified' => $request->verified ? 1 : 0,
                'active' => $request->active ? 1 : 0,
            ]);
        }

        // $user->syncPermissions($request->permissions ?? []);
        $user->assignRole($request->roles);
        $user->supestructuras()->sync($request->structuras);
        $user->syncPermissions($request->permisos);
        $data = [
            'status' => 200,
            'data' => $user,
            'message' => 'Actualizado correctamente'
        ];
        return response()->json($data);
    }

    public function invoices(Request $request)
    {
        $invoice = [];
        $clientData = [
            'addres' => '7777 Mendez Plains',
            'company' => 'Hall-Robbins PLC',
            'companyEmail' => 'don85@johnson.com',
            'country' => 'USA',
            'contact' => '(616) 865-4180',
            'name' => 'Jordan Stevenson',
        ];
        $invoice[0]['id'] = 4987;
        $invoice[0]['issuedDate'] = '13 dec 2019';
        $invoice[0]['client'] = $clientData;
        $invoice[0]['service'] = 'Software Development';
        $invoice[0]['total'] = 3428;
        $invoice[0]['avatar'] = '';
        $invoice[0]['invoiceStatus'] = 'paid';
        $invoice[0]['balance'] = '$987';
        $invoice[0]['dueDate'] = '23 apr 2019';

        $invoice[1]['id'] = 4982;
        $invoice[1]['issuedDate'] = '13 dec 2119';
        $invoice[1]['client'] = $clientData;
        $invoice[1]['service'] = 'Software Engineer';
        $invoice[1]['total'] = 3428;
        $invoice[1]['avatar'] = '';
        $invoice[1]['invoiceStatus'] = 'sale';
        $invoice[1]['balance'] = '$987';
        $invoice[1]['dueDate'] = '23 apr 2019';
        $data = [
            'status' => 200,
            'data' => 'fer'
        ];

        return response()->json($request);
    }
    public function dependencias(Request $request)
    {
        $dependencias = $request->dependencias;
        $id = $request->id;
        $user = $request->user()->id;
        //insert dependency array
        foreach ($dependencias as $value) {
            $dep = new UserHasDependencia();
            $dep->user_id = $id;
            $dep->dependencia_id = $value;
            $dep->created_by = $user;
            $dep->save();
        }
    }
    public function dependencias_update(Request $request)
    {
        $dependencias = $request->dependencias;
        $id = $request->id;
        $user = $request->user()->id;

        $delete = UserHasDependencia::where('user_id', $id)->delete();
        // $personal->delete();
        //insert dependency array
        foreach ($dependencias as $value) {
            $dep = new UserHasDependencia();
            $dep->user_id = $id;
            $dep->dependencia_id = $value;
            $dep->created_by = $user;
            $dep->save();
        }
    }
}

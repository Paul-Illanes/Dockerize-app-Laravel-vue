<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'mother_lastname' => 'required|string',
            'email' => 'required|string|unique:users',
            'username' => 'required|int|unique:users',
            'pin' => 'required|int|unique:users',
            'password' => 'required|string',
            'c_password' => 'required|same:password'
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'username' => 'abel',
            'active' => 1,
            'verified' => 1,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user!',
                'accessToken' => $token,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);


        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => $request->all()
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function user(Request $request)
    {
        // return new UserResource($request->user());
        $user = $request->user();
        // $permissions = $user->getAllPermissions()->pluck('name');
        $permissions = $user->getAllPermissions()->pluck('name');
        $role = $user->getRoleNames();
        $ability = new \stdClass();
        $total = count($permissions);
        $i = 0;
        foreach ($permissions as $item) {
            $ability->ability[$i] = ['action' => $item, 'subject' => 'ACL'];
            $i++;
        }
        $homeAbility = $total + 1;
        $ability->ability[$total] = ['action' => 'read', 'subject' => 'Auth'];
        $ability->ability[$homeAbility] = ['action' => 'read', 'subject' => 'Home'];
        $user->ability = $ability->ability;
        $user->role = 'admin';
        return response()->json($user);
        // $object = new \stdClass();
        // // $object->ability[0] = ['action' => 'manage', 'subject' => 'all'];
        // $object->ability[0] = ['action' => 'read-user', 'subject' => 'ACL'];
        // $object->ability[1] = ['action' => 'create-user', 'subject' => 'ACL'];
        // $object->ability[2] = ['action' => 'edit-user', 'subject' => 'ACL'];
        // $object->ability[3] = ['action' => 'delete-user', 'subject' => 'ACL'];
        // // $object->ability[4] = ['action' => 'read', 'subject' => 'Auth'];
        // $user = [
        //     "id" => 1,
        //     "fullName" => "John Doe",
        //     "username" => "johndoe",
        //     "avatar" => "/img/13-small.d796bffd.png",
        //     "email" => "admin@demo.com",
        //     "role" => "admin",
        //     "ability" => $object->ability
        //     // more...

        // ];
        // return response()->json($user);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}

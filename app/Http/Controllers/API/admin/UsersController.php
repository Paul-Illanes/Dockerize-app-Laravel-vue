<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Users;

class UsersController extends Controller
{
    public function usersList(Request $request)
    {
        $users = Users::all();
        return response()->json($users);
    }
}

<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UsersController extends Controller
{
    public function usersList(Request $request)
    {
        $users = User::all();
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
}

<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Papeleta;

class PapeletaController extends Controller
{
    public function getList(Request $request)
    {
        $papeletas = Papeleta::all();
        $data = [
            'status' => 200,
            'data' => $papeletas
        ];
        return response()->json($data);
    }
}

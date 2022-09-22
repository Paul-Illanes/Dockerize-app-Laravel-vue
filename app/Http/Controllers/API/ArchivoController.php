<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archivos;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    public function file($id)
    {
        $archivo = Archivos::find($id);
        $ruta = $archivo->ruta;
        $name = $archivo->nombre;
        $path = $ruta . '/' . $name;
        $file = Storage::path($path);
        return response()->file($file);
    }
}

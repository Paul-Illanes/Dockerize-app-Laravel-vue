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
    public function all(Request $request)
    {
        $id = $request->id;
        $ruta = 'archivos';
        $modulo = $request->modulo;
        $archivo = Archivos::where('documento_id', $id)->where('ruta', $ruta)->where('modulo', $modulo)->get();
        return response()->json($archivo);
    }
    public function save(Request $request)
    {
        if ($request->file('file')) {
            //get name of file
            // $file = $request->file('file');
            // $filename = $file->getClientOriginalName();
            // //strip out all spaces
            // $filename = str_replace(' ', '', $filename);

            // $path = $file->storeAs('uploads', $filename);
            // // $path = $request->file('file')->store('uploads');
            // if($path){
            //     return response()->json(['message'=>'file uploaded'], 200);
            // }


            /* Multiple file upload */
            $files = $request->file('file');
            if (!is_array($files)) {
                $files = [$files];
            }
            $data = $request->all();
            //loop throu the array 
            for ($i = 0; $i < count($files); $i++) {
                $file = $files[$i];
                $filename = time() . $file->getClientOriginalName();
                $filename = str_replace(' ', '', $filename);
                $file->storeAs('archivos', $filename);
                $archivo = new Archivos();
                $archivo->nombre = $filename;
                $archivo->documento_id = $data['id'];
                $archivo->ruta = 'archivos';
                $archivo->detalle = $data['detalle'];
                $archivo->modulo = $data['modulo'];
                $archivo->status = $data['status'];
                $archivo->save();
            }
            return response()->json(['message' => 'file uploaded', 'data' => $filename], 200);
        } else {
            return response()->json(['message' => 'error uploading file'], 503);
        }
    }
    public function archivo($data, $name)
    {
        $archivo = new Archivos();
        $archivo->nombre = $name;
        $archivo->documento_id = $data['documento'];
        $archivo->detalle = $data['detalle'];
        $archivo->save();
    }
    public function delete_archivo(Request $request, $id)
    {
        $archivo = Archivos::find($id);
        $name = 'archivos/' . $archivo->nombre;
        if (Storage::exists($name)) {
            Storage::delete($name);
            $archivo->delete();
            return response()->json(200);
        }
        return response()->json(['msg' => 'archivo no encontrado'], 503);
    }
}

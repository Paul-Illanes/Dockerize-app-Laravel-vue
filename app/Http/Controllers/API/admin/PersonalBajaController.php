<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalBaja;
use App\Models\Periodo;
use App\Models\MotivoBaja;
use App\Models\TipoDocumentoBaja;
use App\Models\DependenciaDocumento;
use App\Models\Persona;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PersonalBajaController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $personalBajas = PersonalBaja::select('personal_bajas.*', 'personas.nombres', 'personas.cod_planilla', 'personas.c_l', 'motivo_bajas.baja')
            ->latest('personal_bajas.created_at')
            ->supestructura($user_id)
            ->motivobaja('personal.motivo_baja_id')
            ->get();

        return response()->json($personalBajas);
    }
    public function select(Request $request)
    {
        $periodos = Periodo::select('id as name', 'id')->where('status', true)->get();
        $periodo_default = date('Ym', strtotime(now()));

        $motivo_baja = MotivoBaja::select('baja as name', 'id')->where('active', 1)->get();
        $tipo_documento = TipoDocumentoBaja::select('tipo_documento as name', 'id')->where('active', true)->get();
        $dependencia_origen = DependenciaDocumento::select('denominacion as name', 'id')->where('active', true)->get();
        $data = [
            'periodos' => $periodos,
            'periodo_default' => $periodo_default,
            'motivo_baja' => $motivo_baja,
            'tipo_documento' => $tipo_documento,
            'dependencia_origen' => $dependencia_origen
        ];
        return response()->json($data);
    }

    public function file(Request $request)
    {
        if ($request->file('file')) {
            $data = $request->all();
            $id = $data['id'];
            $baja = PersonalBaja::FindOrFail($id);
            $files = $request->file('file');
            if (!is_array($files)) {
                $files = [$files];
            }
            $file = $files[0];
            $filename = 'baja_' . $baja->dni . '_' . $baja->motivo_baja_id . '_' . $baja->periodo . '.pdf';
            $path = $file->storeAs('bajas', $filename);
            if ($path) {
                $baja->path_documento = $filename;
                $baja->save();
                return response()->json(['message' => 'file uploaded', 'filename' => $filename], 200);
            }
        } else {
            return response()->json(['message' => 'error uploading file'], 503);
        }
    }
    public function getfile($name)
    {
        $path = 'bajas/' . $name;
        $file = Storage::path($path);
        return response()->file($file);
    }
    public function create(Request $request)
    {
        $user_id = $request->user()->username;
        $user = User::where('username', $request->dni)->first();
        $persona = Persona::find($request->dni);

        //concatenar documento sustento
        $sustento = new PersonalBaja();
        $documento_sustento = $sustento->DocumentoSustento($request);

        $dni = $request->dni;
        $vinculo_laboral = get_vinculo_laboral($dni);
        // record baja
        $personalBaja = PersonalBaja::create([
            'regimen_laboral' => $persona->c_l,
            'cargo' => $persona->cargo,
            'nivel' => $persona->nivel,
            'grupo' => $persona->grupo,
            'dependencia' => $persona->dependencia,
            'fecha_ingreso' => $persona->fecha_ingreso,
            'documento_sustento' => $documento_sustento,
            'fecha_registro' => now(),
            'status' => 0,
            'created_by' => $user_id,
            'updated_by' => $user_id,
            'vinculo_laboral' => $vinculo_laboral

        ] + $request->all());
        $baja_id = $personalBaja->id; // id del registro personal baja
        if ($request->fecha_ultimo_dia <= now()) {
            status_vinculo_laboral($dni);
            $persona->changeStatus(2); //2=baja
            // usuario -- disable user
            if ($user) {
                $user->changeStatus($user, false);
            }
        }
        if ($baja_id) {
            return response()->json(['id' => $baja_id], 200);
        }
        $user = $request->user();
        notificarAdd($user, 'Baja de personal', $personalBaja->id);
    }
    public function getDetail($id)
    {
        $bajas = PersonalBaja::FindOrFail($id);
        $persona = Persona::select('nombres')->where('dni', '=', $bajas->dni)->get();
        $bajas->nombres = $persona[0]->nombres;
        return response()->json($bajas);
    }
    public function update(Request $request, $id)
    {
        request()->validate(PersonalBaja::$rules);

        $user_id = $request->user()->id;
        $user = User::where('username', $request->dni)->first();

        //concatenar documento sustento
        $sustento = new PersonalBaja();
        $documento_sustento = $sustento->DocumentoSustento($request);

        $personalBaja = new PersonalBaja();

        $personalBaja->documento_sustento = $documento_sustento;
        $personalBaja->fecha_registro = now();
        $personalBaja->dni = $request->dni;
        $personalBaja->periodo = $request->periodo;
        $personalBaja->motivo_baja_id = $request->motivo_baja_id;
        $personalBaja->nit = $request->nit;
        $personalBaja->fecha_ultimo_dia = $request->fecha_ultimo_dia;
        $personalBaja->fecha_cese = $request->fecha_cese;
        $personalBaja->tipo_documento_id = $request->tipo_documento_id;
        $personalBaja->origen_dependencia_id = $request->origen_dependencia_id;
        $personalBaja->numero_documento = $request->numero_documento;
        $personalBaja->fecha_documento_sustento = $request->fecha_documento_sustento;
        $personalBaja->posicion = $request->posicion;
        $personalBaja->plaza = $request->plaza;
        $personalBaja->observacion = $request->observacion;
        $personalBaja->updated_by = $user_id;
        $personalBaja->status = 0;
        $personalBaja->save();
        // update person to set baja
        $persona = Persona::find($request->dni);
        $persona->update([
            'status' => 2,
            'forma_registro' => 'CRUD',
            'updated_by' => $user_id,
        ]);

        if ($request->fecha_ultimo_dia <= now()) {
            $persona->changeStatus(2); //2=baja
            if ($user) {
                $user->changeStatus($user, false);
            }
        }
        $user = $request->user();
        notificarEdit($user, 'Baja de personal', $personalBaja->id);
    }
    public function modal(Request $request)
    {
        $created_by = Persona::where('dni', '=', $request->created_by)->pluck('nombres');
        $updated_by = Persona::where('dni', '=', $request->updated_by)->pluck('nombres');
        $fecha_nacimiento = Persona::where('dni', '=', $request->fecha_nacimiento)->pluck('fecha_nacimiento');
        // $motivo_baja = MotivoBaja::select('baja as name')->where('id', $request->motivo)->get();
        $tipo_documento = TipoDocumentoBaja::where('id', '=', $request->tipo_documento_id)->pluck('tipo_documento');
        $dependencia_origen = DependenciaDocumento::where('id', '=', $request->origen_dependencia_id)->pluck('denominacion');
        $data = [
            'fecha_nacimiento' => $fecha_nacimiento,
            'created_by' => $created_by,
            'updated_by' => $updated_by,
            'tipo_documento' => $tipo_documento,
            'dependencia_origen' => $dependencia_origen
        ];
        return response()->json($data);
    }
    public function observar(Request $request, $id)
    {
        $baja = PersonalBaja::FindOrFail($id);
        $baja->status = 2;
        $baja->obs = $request->observacion_id;
        $baja->updated_by = $request->user()->id;
        $baja->save();
    }
    public function update_estado(Request $request, $id)
    {
        $baja = PersonalBaja::Find($id);
        $baja->status_baja = $request->status;
        $baja->save();
    }
    public function anular(Request $request)
    {
        if ($request->file('file')) {
            $data = $request->all();
            $id = $data['id'];
            $baja = PersonalBaja::FindOrFail($id);
            $files = $request->file('file');
            if (!is_array($files)) {
                $files = [$files];
            }
            $file = $files[0];
            $filename = 'baja_' . $baja->dni . '_' . $baja->motivo_baja_id . '_' . $baja->periodo . '.pdf';

            $archivo_id = upload_archive($file, $filename, 'bajas', 'baja de personal');
            $baja->archivo_id = $archivo_id;
            $baja->status_baja = 3;
            $baja->save();
        } else {
            $data = $request->all();
            $id = $data['id'];
            $baja = PersonalBaja::FindOrFail($id);
            $baja->motivo_anulacion_id = $data['motivo'];
            $baja->status_baja = 3;
            $baja->save();
        }
    }
    public function delete($id)
    {
        $baja = PersonalBaja::FindOrFail($id);
        $baja->delete();
    }
}

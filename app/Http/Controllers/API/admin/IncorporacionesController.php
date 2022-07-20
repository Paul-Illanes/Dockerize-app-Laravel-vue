<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incorporaciones;
use App\Models\IncorporacionesValidate;

class IncorporacionesController extends Controller
{
    public function getList()
    {
        $validar = Incorporaciones::all()->load('validation');
        // dd($validar);
        $incorporaciones = Incorporaciones::latest()
            ->orderBy('updated_at', 'DESC')
            ->get();
        $data = [
            'status' => 200,
            'incorporaciones' => $incorporaciones,
            'validacion' => $validar
        ];
        return response()->json($data);
    }
    public function status(Request $request)
    {

        $id = $request->id;
        $validate = IncorporacionesValidate::find($id)->get();
        $incorporacionValidate = $validate[0];
        if ($request->status == 1) {
            $incorporacionValidate->status = 0;
        } else {
            $incorporacionValidate->status = 1;
        }

        $incorporacionValidate->save();
        return response()->json(['msg' => 'actualizado correctamente']);
    }
    public function store(Request $request)
    {
        $incorporaciones = Incorporaciones::create($request->all());
        return response()->json(['msg' => $request->all()]);
    }
    public function getDetail(Request $request, $id)
    {

        $data = Incorporaciones::find($id);
        return response()->json($data);
    }
    public function update(Request $request, $id)
    {

        $data = Incorporaciones::FindOrFail($id);
        // $data->update($request->all());
        $data->organo = $request->organo;
        $data->centro = $request->centro;
        $data->area = $request->area;
        $data->cod_area = $request->cod_area;
        $data->plaza = $request->plaza;
        $data->apellido_paterno = $request->apellido_paterno;
        $data->apellido_materno = $request->apellido_materno;
        $data->nombres = $request->nombres;
        $data->sexo = $request->sexo;
        $data->estado_civil = $request->estado_civil;
        $data->domicilio = $request->domicilio;
        $data->dni = $request->dni;
        $data->fecha_nacimiento = $request->fecha_nacimiento;
        $data->nivel_educativo = $request->nivel_educativo;
        $data->sistema_pension = $request->sistema_pension;
        $data->nombre_afp = $request->nombre_afp;
        $data->cod_afp = $request->cod_afp;
        $data->afp_cuz = $request->afp_cuz;
        $data->fecha_afp = $request->fecha_afp;
        $data->cod_cargo = $request->cod_Cargo;
        $data->cargo = $request->cargo;
        $data->especialidad = $request->especialidad;
        $data->nivel = $request->nivel;
        $data->fecha_inicio = $request->fecha_inicio;
        $data->fecha_termino = $request->fecha_termino;
        $data->bonificacion = $request->bonificacion;
        $data->remuneracion = $request->remuneracion;
        $data->ingreso_mensual = $request->ingreso_mensual;
        $data->banco = $request->banco;
        $data->numero_cuenta_banco = $request->numero_cuenta_banco;
        $data->motivo_contrato = $request->motivo_contrato;
        $data->modalidad_contrato = $request->modalidad_contrato;
        $data->beneficiario_ley30555 = $request->beneficiario_ley30555;
        $data->ruc = $request->ruc;
        $data->numero_proceso_seleccion = $request->numero_proceso_seleccion;
        $data->fecha_proceso = $request->fecha_proceso;
        $data->situacion_proceso = $request->situacion_proceso;
        $data->personal_deja_cargo = $request->personal_deja_Cargo;
        $data->tipo_ausencia = $request->tipo_ausencia;
        $data->fecha_inicio_ausencia = $request->fecha_inicio_ausencia;
        $data->fecha_fin_ausencia = $request->fecha_fin_ausencia;
        $data->alta_baja = $request->alta_baja;
        $data->mes_alta_planilla = $request->mes_alta_planilla;
        $data->suspension_cuarta = $request->suspension_cuarta;
        $data->celular = $request->celular;
        $data->correo = $request->correo;
        $data->status = $request->status;
        $data->cerrado = $request->cerrado;
        $data->periodo = $request->periodo;
        $data->save();
    }
}

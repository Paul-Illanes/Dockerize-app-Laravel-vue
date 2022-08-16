<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalInformesCese;
use App\Models\PersonalBaja;
use App\Models\Persona;
use Illuminate\Support\Facades\Storage;

class PersonalInformesCeseController extends Controller
{
    public function index()
    {
        $data = PersonalInformesCese::latest('created_at')->get();
        return response()->json($data);
    }

    public function getusers()
    {
        $personalInformesCese = new PersonalInformesCese();

        // array with DNI for PersonalInformesCeses
        $array_dni = PersonalInformesCese::pluck('dni');

        // $empleados_query =Persona::where('status',2)->get(); // empleados dados de baja
        $empleados_query = PersonalBaja::where('status', 1)
            ->whereNotIn('dni', $array_dni)
            ->get(); // empleados dados de baja

        $empleados = [];
        foreach ($empleados_query as $empleado) {
            $empleados['dni'] = $empleado->persona->username;
            $empleados['name'] = $empleado->persona->fullname;
        }

        return response()->json($empleados);
    }
    public function create(Request $request)
    {
        $user_id = $request->user()->id;

        $persona = Persona::find($request->dni);
        $baja = PersonalBaja::where('dni', $request->dni)->first();

        $personalInformesCese = PersonalInformesCese::create(
            [
                'nombre' => $persona->fullname,
                'fecha_ingreso' => $persona->fecha_ingreso,
                'fecha_cese' => $baja->fecha_cese,
                'motivo_cese' => $baja->motivoBaja->baja,
                'created_by' => $user_id,
                'updated_by' => $user_id,
            ] + $request->all()
        );
        return response()->json(['msg' => 'registrado correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personalInformesCese = PersonalInformesCese::find($id);

        return view('personal-informes-cese.show', compact('personalInformesCese'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getDetail($id)
    {
        $informe = PersonalInformesCese::FindOrFail($id);
        // $persona = Persona::select('nombres')->where('dni', '=', $informe->dni)->get();
        // $informe->nombres = $persona[0]->nombres;
        return response()->json($informe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PersonalInformesCese $personalInformesCese
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = $request->user()->id;

        $persona = Persona::find($request->dni);
        $baja = PersonalBaja::where('dni', $request->dni)->first();
        $personalInformesCese = PersonalInformesCese::findOrFail($id);
        $personalInformesCese->nombre = $request->nombre;
        $personalInformesCese->dni = $request->dni;
        $personalInformesCese->nit = $request->nit;
        $personalInformesCese->numero_informe = $request->numero_informe;
        $personalInformesCese->fecha_informe = $request->fecha_informe;
        $personalInformesCese->faltas = $request->faltas;
        $personalInformesCese->tardanzas = $request->tardanzas;
        $personalInformesCese->licencias = $request->licencias;
        $personalInformesCese->permisos_particulares = $request->permisos_particulares;
        $personalInformesCese->sancion_disciplinaria = $request->sancion_disciplinaria;
        $personalInformesCese->huelga = $request->huelga;
        $personalInformesCese->licencia_covid = $request->licencia_covid;
        $personalInformesCese->vacaciones_no_gozadas = $request->vacaciones_no_gozadas;
        $personalInformesCese->bono_pago = $request->bono_pago;
        $personalInformesCese->descuento_bono_pago = $request->descuento_bono_pago;
        $personalInformesCese->guardias = $request->guardias;
        $personalInformesCese->horas_extras = $request->horas_extras;
        $personalInformesCese->horas_rpct = $request->horas_rpct;
        $personalInformesCese->pct = $request->pct;
        $personalInformesCese->notas_adicionales = $request->notas_adicionales;
        $personalInformesCese->zona_menor_desarrollo = $request->zona_menor_desarrollo;
        $personalInformesCese->fecha_ingreso = $persona->fecha_ingreso;
        $personalInformesCese->fecha_cese = $baja->fecha_cese;
        $personalInformesCese->motivo_cese = $baja->motivo_cese;
        $personalInformesCese->updated_by = $user_id;
        $personalInformesCese->path_informe = "";
        $personalInformesCese->save();
        // $personalInformesCese->update(
        //     [
        //         'nombre' => $persona->fullname,
        //         'fecha_ingreso' => $persona->fecha_ingreso,
        //         'fecha_cese' => $baja->fecha_cese,
        //         'motivo_cese' => $baja->motivoBaja->baja,
        //         'updated_by' => $user_id,
        //         'path_informe' => '',
        //     ] + $request->all()
        // );

        return response()->json(['msg' => 'actualizado correctamente']);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $personalInformesCese = PersonalInformesCese::find($id)->delete();

        return redirect()->route('personal-informes-ceses.index')
            ->with('status', 'PersonalInformesCese eliminado con éxito');
    }

    /**
     * return data personal
     */
    public function getPersona(Request $request)
    {
        $persona = Persona::find($request->dni);
        $baja = PersonalBaja::where('dni', $request->dni)->first();
        // dd($baja);
        // $motivo_baja = $baja->motivoBaja->baja;
        $data = [
            'fecha_ingreso' => $persona->fecha_ingreso,
            'fecha_cese' => $baja->fecha_cese,
            'motivo_cese' => $baja->motivoBaja->baja,

        ];
        return response()->json($data);
    }

    public function generarInforme(Request $request)
    {
        // dd($request->all());
        set_time_limit(300);
        $informe_id = $request->informe_id;

        $informe = PersonalInformesCese::find($informe_id);
        $dni = $informe->dni;


        $fecha_generacion = date('d-m-Y');
        $file_name = strtoupper("informe-cese-control-personal-$dni-$fecha_generacion") . '.pdf';

        $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('personal-informes-cese.informe-pdf', compact('informe'))
            ->save(\storage_path('app/public/personal-informes-cese/') . $file_name);

        // update status
        PersonalInformesCese::where('id', $informe_id)->update([
            'path_informe' => $file_name,
        ]);

        return response()->json('hola', 200);
    }
    public function getfile($name)
    {
        $path = 'public/personal-informes-cese/' . $name;
        $file = Storage::path($path);
        return response()->file($file);
    }
    /**
     * preview informe pdf file
     */
    public function previewInforme($id)
    {
        $informe = PersonalInformesCese::find($id);
        $file = url('storage/' . $informe->path_informe);
        return view('personal-informes-cese.preview-pdf', compact('file'));
    }
    /**
     * download informe pdf file
     */
    public function descargarInforme($id)
    {
        $informe = PersonalInformesCese::find($id);
        $file = public_path('storage/' . $informe->path_informe);
        return response()->download($file);
    }

    public function import()
    {

        return view('personal-informes-cese.import');
    }
    public function import_send(Request $request)
    {

        $import = new InformeCeseImport();

        $data = Excel::import($import, request()->file('file'));
        // dd($import->getInvalidData());
        return view('personal-informes-cese.import', ['numRows' => $import->getRowCount(), 'invalidData' => $import->getInvalidData(), 'invalidRows' => $import->getInvalidRows()]);
    }
    public function export(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->verify);
        // dd($data);
        // $personas =  PersonalInformesCese::where('dni', '48611353')->get();
        // $user_id = auth()->user()->id;
        // $papeletas = Papeleta::select(
        //     'papeletas.dni',
        //     'tipo_permiso_id',
        //     DB::raw("sum(tdd) as total_tdd"),
        //     DB::raw("sum(tdm) as total_tdm")
        // )
        //     ->supestructura($user_id)
        //     ->where('papeletas.status', 1) // status = 1, activo
        //     ->orWhere('papeletas.status', 2) // status = 2, observado
        //     ->groupBy('papeletas.dni', 'tipo_permiso_id')
        //     ->get();
        // // dd($papeletas);   
        return Excel::download(new BladeExport($data, 'personal-informes-cese.import'), 'invalidos' . '.xlsx');
    }
}

<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VacacionesDocumento;
use App\Models\Papeleta;

class VacacionesDocumentoController extends Controller
{
    public function report($dni)
    {
        $persona_dni = $dni;
        $sql = "SELECT 
        vac.anio,
        vac.periodo,
        vac.dias_pendientes,
        IFNULL(tdocumentos.dias_usados, 0) as dias_usados,
        (vac.dias_pendientes - IFNULL(tdocumentos.dias_usados, 0) ) pend
        FROM (
            SELECT 
            d.persona_dni AS dni,	
            d.periodo,
            SUM(nro_dias) AS dias_usados
            FROM vacaciones_documentos d
            INNER JOIN vacaciones_tipo_documentos td ON td.id = d.tipo_documento_id
            WHERE estado_valido=1
            AND persona_dni = $persona_dni
            GROUP BY d.persona_dni,d.periodo
            ) tdocumentos
        RIGHT JOIN vacaciones vac ON vac.dni = tdocumentos.dni AND vac.`anio`=tdocumentos.periodo
        INNER JOIN personas per ON per.dni = vac.dni
        WHERE vac.dni = $persona_dni
        AND tdocumentos.dias_usados < 30
        AND vac.status = 1";
        $vac = DB::select($sql);
        return response()->json($vac);
    }
    public function vacaciones($dni)
    {
        $persona_dni = $dni;
        $sql = "SELECT 
        vac.anio,
        vac.periodo,
        vac.dias_pendientes,
        IFNULL(tdocumentos.dias_usados, 0) as dias_usados,
        (vac.dias_pendientes - IFNULL(tdocumentos.dias_usados, 0) ) pend
        FROM (
            SELECT 
            d.persona_dni AS dni,	
            d.periodo,
            SUM(nro_dias) AS dias_usados
            FROM vacaciones_documentos d
            INNER JOIN vacaciones_tipo_documentos td ON td.id = d.tipo_documento_id
            WHERE estado_valido=1
            AND persona_dni = $persona_dni
            GROUP BY d.persona_dni,d.periodo
            ) tdocumentos
        RIGHT JOIN vacaciones vac ON vac.dni = tdocumentos.dni AND vac.`anio`=tdocumentos.periodo
        INNER JOIN personas per ON per.dni = vac.dni
        WHERE vac.dni = $persona_dni
        AND vac.status = 1";
        $vac = DB::select($sql);
        // $vacaciones = (object) $vac;
        // var_dump(gettype($vacaciones));
        return response()->json($vac);
    }
    public function documentos($dni)
    {
        $documentos = VacacionesDocumento::select('vacaciones_documentos.id', 'tipo_documento_id', 'persona_dni', 'periodo', 'fecha_inicio', 'fecha_final', 'nro_dias', 'vacaciones_tipo_documentos.tipo', 'es_suspension', 'nit', 'estado_valido')
            ->join('vacaciones_tipo_documentos', 'vacaciones_tipo_documentos.id', 'vacaciones_documentos.tipo_documento_id')
            ->where('persona_dni', $dni)
            ->where('estado_valido', 1)
            ->orderBy('vacaciones_documentos.id', 'DESC')
            ->get();

        return response()->json($documentos);
    }
    public function papeletas($dni)
    {
        $papeletas = Papeleta::where('dni', $dni)
            ->where('tipo_permiso_id', 4)
            ->where('status', 1)
            ->where('vacaciones_status', '!=', 1)
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json($papeletas);
    }
    public function periodo_pluck($dni)
    {
        $persona_dni = $dni;
        $sql = "SELECT 
        vac.anio,
        vac.periodo,
        vac.dias_pendientes,
        IFNULL(tdocumentos.dias_usados, 0) as dias_usados,
        (vac.dias_pendientes - IFNULL(tdocumentos.dias_usados, 0) ) pend
        FROM (
            SELECT 
            d.persona_dni AS dni,	
            d.periodo,
            SUM(nro_dias) AS dias_usados
            FROM vacaciones_documentos d
            INNER JOIN vacaciones_tipo_documentos td ON td.id = d.tipo_documento_id
            WHERE estado_valido=1
            AND persona_dni = $persona_dni
            GROUP BY d.persona_dni,d.periodo
            ) tdocumentos
        RIGHT JOIN vacaciones vac ON vac.dni = tdocumentos.dni AND vac.`anio`=tdocumentos.periodo
        INNER JOIN personas per ON per.dni = vac.dni
        WHERE vac.dni = $persona_dni
        AND vac.status = 1";
        $vac = DB::select($sql);
        return response()->json($vac);
    }
}

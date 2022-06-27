<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        AND vac.status = 1";
        $vac = DB::select($sql);
        $vacaciones = (object) $vac;
        // var_dump(gettype($vacaciones));
        $data = [
            'status' => 200,
            'data' => $vacaciones
        ];
        return response()->json($vac);
    }
}

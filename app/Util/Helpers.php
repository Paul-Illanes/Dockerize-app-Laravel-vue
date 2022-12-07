<?php

use App\Notifications\RealTimeMessage;
use Illuminate\Support\Facades\Notification;
use App\Events\RealTime;
use App\Models\User;

if (!function_exists('get_months')) {
    /**
     * return months in array to use at dropdown
     */
    function get_months()
    {
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        return $meses;
    }
}

if (!function_exists('get_empleados')) {
    /**
     * return an array[key=DNI, full_name] for all employes
     * 
     */
    function get_empleados()
    {
        $empleados_query = App\Persona::get();
        $empleados = [];
        foreach ($empleados_query as $empleado) {
            $empleados[$empleado->dni] = $empleado->fullname;
        }
        return $empleados;
    }
}

if (!function_exists('get_empleados_activos')) {
    /**
     * return an array[key=DNI, full_name] with active employes status !=2
     * 
     */
    function get_empleados_activos()
    {
        $empleados_query = App\Persona::where('status', '!=', 2)->get();
        $empleados = [];
        foreach ($empleados_query as $empleado) {
            $empleados[$empleado->dni] = $empleado->fullname;
        }
        return $empleados;
    }
}


if (!function_exists('convert_month_to_number')) {
    /**
     * return convert month name to number equivalent value
     */
    function convert_month_to_number($month_name)
    {
        $name = strtolower($month_name);
        $valor = NULL;
        switch ($name) {
            case 'enero':
                $valor = 1;
                break;
            case 'febrero':
                $valor = 2;
                break;
            case 'marzo':
                $valor = 3;
                break;
            case 'abril':
                $valor = 4;
                break;
            case 'mayo':
                $valor = 5;
                break;
            case 'junio':
                $valor = 6;
                break;
            case 'julio':
                $valor = 7;
                break;
            case 'agosto':
                $valor = 8;
                break;
            case 'setiembre':
                $valor = 9;
                break;
            case 'septiembre':
                $valor = 9;
                break;
            case 'octubre':
                $valor = 10;
                break;
            case 'noviembre':
                $valor = 11;
                break;
            case 'diciembre':
                $valor = 12;
                break;
            default:
                break;
        }
        return $valor;
    }
}

if (!function_exists('convert_month_to_name')) {
    /**
     * return convert month name to number equivalent value
     */
    function convert_month_to_name($month)
    {
        $value = NULL;
        switch ($month) {
            case 1:
                $value = 'enero';
                break;
            case 2:
                $value = 'febrero';
                break;
            case 3:
                $value = 'marzo';
                break;
            case 4:
                $value = 'abril';
                break;
            case 5:
                $value = 'mayo';
                break;
            case 6:
                $value = 'junio';
                break;
            case 7:
                $value = 'julio';
                break;
            case 8:
                $value = 'agosto';
                break;
            case 9:
                $value = 'setiembre';
                break;
            case 10:
                $value = 'octubre';
                break;
            case 11:
                $value = 'noviembre';
                break;
            case 12:
                $value = 'diciembre';
                break;
            default:
                break;
        }
        return strtoupper($value);
    }
}


if (!function_exists('get_ley_regimen_laboral')) {
    /**
     * return regimen laboral ley from data
     */
    function get_ley_regimen_laboral($data)
    {
        $name = strtolower($data);
        $valor = '';
        switch ($name) {
            case 'cas':
                $valor = 1057;
                break;
            case '276':
                $valor = 276;
                break;
            case '728':
                $valor = 728;
                break;
            case 'cas.r':
                $valor = 1057;
                break;
            case 'cas_c':
                $valor = 1057;
                break;
            case 'nom.':
                $valor = 276;
                break;
            default:
                break;
        }
        return $valor;
    }
}
if (!function_exists('get_field')) {
    /**
     * Returns a value from xml content field
     * */
    function get_field($arr, $field)
    {
        if (is_array($arr) && isset($arr[$field]))
            return $arr[$field];
        else
            return null;
    }
}
if (!function_exists('parameter_pluck')) {
    /**
     * Returns a list of parameters by lang
     * */
    function parameter_pluck($group_alias, $iso = 'es', $parent_id = null)
    {
        return App\Models\CmsParameter::select('metadata->name_es AS name', 'id')
            ->whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->pluck('name', 'id');
    }
}
//para validacion de permisos
if (!function_exists('parameter_get')) {
    /**
     * Returns a list of parameters by lang
     * */
    function parameter_get($group_alias, $iso = 'es', $parent_id = null)
    {
        return App\Models\CmsParameter::select('metadata->name_es AS name', 'id', 'metadata->permission AS permisos')
            ->whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        // ->pluck('name', 'id', 'permisos');
    }
}
//cambiar fecha de excel a foramto date php
if (!function_exists('excel_to_date')) {
    /**
     * Returns date
     * */
    function excel_to_date($date)
    {
        if (is_numeric($date)) {
            $excel_date = $date; //excel date
            $unix_date = ($excel_date - 25569) * 86400;
            $excel_date = 25569 + ($unix_date / 86400);
            $unix_date = ($excel_date - 25569) * 86400;
            return gmdate("Y-m-d", $unix_date);
        } else {
            return null;
        }
        // try {
        //     if ($date) {
        //         $excel_date = $date; //excel date
        //         $unix_date = ($excel_date - 25569) * 86400;
        //         $excel_date = 25569 + ($unix_date / 86400);
        //         $unix_date = ($excel_date - 25569) * 86400;
        //         return gmdate("Y-m-d", $unix_date);
        //     } else {
        //         return null;
        //     }
        // } catch (Exception $e) {
        //     return null;
        // }
    }
}
//para validacion de permisos
if (!function_exists('parameter_value')) {
    /**
     * Returns a list of parameters by lang
     * */
    function parameter_value($group_alias, $iso = 'es', $parent_id = null)
    {
        return App\Models\CmsParameter::select('metadata->name_es AS name', 'value')
            ->whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->pluck('value', 'name');
    }
}

if (!function_exists('parameter_single_value')) {
    /**
     * Retun a single value from parameter by ID
     */
    function parameter_single_value($parameter_id)
    {
        $data = App\Models\CmsParameter::find($parameter_id);
        return $data;
    }
}
if (!function_exists('get_vinculo_laboral')) {
    /**
     * Retun a single value from parameter by ID
     */
    function get_vinculo_laboral($dni)
    {
        // $contrato = App\Models\Contratos::select('id')->where('empleado_dni', '=', $dni);
        $contrato = App\Models\Contratos::select('id')->where('empleado_dni', '=', $dni)->get();
        if (isset($contrato[0]->id)) {
            $id = $contrato[0]->id;
            $vinculo_laboral = App\Models\VinculoLaboral::where('contrato_id', '=', $id)->get();
            if (isset($vinculo_laboral[0]->id)) {
                return $vinculo_laboral[0]->id;
            }
        } else {
            return null;
        }
    }
}
if (!function_exists('status_vinculo_laboral')) {
    /**
     * Retun a single value from parameter by ID
     */
    function status_vinculo_laboral($dni)
    {
        // $contrato = App\Models\Contratos::select('id')->where('empleado_dni', '=', $dni);
        $contrato = App\Models\Contratos::select('id')->where('empleado_dni', '=', $dni)->get();
        if (isset($contrato[0]->id)) {
            $id = $contrato[0]->id;
            $cont = App\Models\Contratos::find($id);
            $cont->status_contrato = 0;
            $cont->save();
            $vinculo_laboral = App\Models\VinculoLaboral::where('contrato_id', '=', $id)->get();
            if (isset($vinculo_laboral[0]->id)) {
                $idvl = $vinculo_laboral[0]->id;
                $vl = App\Models\VinculoLaboral::find($idvl);
                $vl->status = 0;
                $vl->save();
                return $idvl;
            }
        } else {
            return null;
        }
    }
}
if (!function_exists('upload_archive')) {
    /**
     * Retun a single value from parameter by ID
     */
    function upload_archive($file, $filename, $ruta, $modulo)
    {
        $path = $file->storeAs($ruta, $filename);
        if ($path) {
            $archivo = App\Models\Archivos::create(
                [
                    'ruta' => $ruta,
                    'nombre' => $filename,
                    'modulo' => $modulo,
                ]
            );
            $archivo->save();
            return $archivo->id;
        }
    }
}
if (!function_exists('notificarEdit')) {
    /**
     * Retun a single value from parameter by ID
     */
    function notificarEdit($user, $modulo, $id)
    {
        $users = User::whereHas("roles", function ($q) {
            $q->where("name", "ADMIN");
        })->get();
        $userName = $user->name . ' ' . $user->last_name . ' ' . $user->mother_lastname;
        $text = $userName . ' modifico una ' . $modulo . ' con el id: ' . $id;
        Notification::send($users, new RealTimeMessage($user, $text));
        event(new \App\Events\RealTime('Hello World'));
    }
}
if (!function_exists('notificarAdd')) {
    /**
     * Retun a single value from parameter by ID
     */
    function notificarAdd($user, $modulo, $id)
    {
        $users = User::whereHas("roles", function ($q) {
            $q->where("name", "ADMIN");
        })->get();
        $userName = $user->name . ' ' . $user->last_name . ' ' . $user->mother_lastname;
        $text = $userName . ' agrego una ' . $modulo . ' nueva con id: ' . $id;
        Notification::send($users, new RealTimeMessage($user, $text));
        event(new \App\Events\RealTime('Hello World'));
    }
}
//para validacion de permisos
if (!function_exists('parameter_get_alias')) {
    /**
     * Returns a list of parameters by lang
     * */
    function parameter_get_alias($group_alias, $iso = 'es', $parent_id = null)
    {
        return App\Models\CmsParameter::select('alias AS name', 'id', 'metadata->permission AS permisos')
            ->whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        // ->pluck('name', 'id', 'permisos');
    }
    if (!function_exists('parameter_alias_value')) {
        /**
         * Returns a list of parameters by lang
         * */
        function parameter_alias_value($group_alias, $iso = 'es', $parent_id = null)
        {
            return App\Models\CmsParameter::select('alias AS name', 'value')
                ->whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
                ->where('parent_id', $parent_id)
                ->where('active', true)
                ->orderBy('value')
                ->get();
        }
    }
    if (!function_exists('cl_to_regimen_laboral')) {
        /**
         * return regimen laboral ley from data
         */
        function cl_to_regimen_laboral($data)
        {
            //$cod_cl = strtolower($data);
            $valor = '';
            switch ($data) {
                case 1:
                    $valor = 276;
                    break;
                case 4:
                    $valor = 728;
                    break;
                case 5:
                    $valor = 728;
                    break;
                case 6:
                    $valor = 728;
                    break;
                case 9:
                    $valor = 1057;
                    break;
                case 10:
                    $valor = 1057;
                    break;
                default:
                    break;
            }
            return $valor;
        }
    }

    if (!function_exists('get_vacation_months')) {
        /**
         * return regimen laboral ley from data
         */
        function get_vacation_months($fechaIngreso, $periodo, $reg_laboral)
        {
            $fecha_ini = ($periodo) . substr($fechaIngreso, 4, 4) . '01';
            //dd($fecha_ini);
            $dia_mes = date('d', strtotime($fechaIngreso));

            $mes_inicio = $dia_mes == "01" ? 0 : 1;
            $meses_para_vacaciones = $dia_mes == "01" ? 10 : 11;
            switch ($reg_laboral) {
                case '276':
                    $fecha_ini = "$periodo-01-01";
                    $mes_inicio = 0;
                    $meses_para_vacaciones = 11;
                    break;
                default:
                    break;
            }

            $vacationsMonths = [];
            $u = 0;
            for ($i = $mes_inicio; $i <= $meses_para_vacaciones; $i++) {
                $yearMonthValue = date('Y-m', strtotime($fecha_ini . " + $i months"));
                $yearMonthText = convert_month_to_name(date('m', strtotime($yearMonthValue))) . '-' . date('Y', strtotime($yearMonthValue));
                $vacationsMonths[$u]['id'] = $yearMonthValue;
                $vacationsMonths[$u]['name'] = $yearMonthText;
                $u++;
                // $vacationsMonths[$yearMonthValue] = $yearMonthText;
            }
            return $vacationsMonths;
        }
    }
    //para obtener el alias desde value
    if (!function_exists('parameter_get_value')) {
        /**
         * Returns a list of parameters by lang
         * */
        function parameter_get_value($group_alias, $value)
        {
            return App\Models\CmsParameter::whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
                ->where('value', $value)
                ->where('active', true)
                ->get()
                ->first();
        }
    }
    //registrar actividad de vinculo laboral
    if (!function_exists('store_actividad_vinculo')) {
        /**
         * Returns a list of parameters by lang
         * */
        function store_actividad_vinculo($group_alias, $value)
        {
            return App\Models\CmsParameter::whereIn('group_id', App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
                ->where('value', $value)
                ->where('active', true)
                ->get()
                ->first();
        }
    }
}

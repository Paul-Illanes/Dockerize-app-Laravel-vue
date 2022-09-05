<?php

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
<?php

namespace App\Services;

use App\Persona;
use App\Models\PersonalRole;
use App\Models\PersonalRolDetalle;
use App\Models\Subactividade;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Arr;

/**
 * Class PersonalRolService
 * @package App\Service
 */
class PersonalRolService
{
    /**
     * @return mixed
     */
    public function getRolDetalle($rol)
    {
        return PersonalRolDetalle::select(['persona_dni', 'fecha_turno', 'actividad_id'])
            ->where('personal_rol_id', $rol)
            ->where('estado_trabajo', true)
            ->get()
            ->groupBy('persona_dni');
    }

    /**
     * @param int $rol_id
     * @param string $dni
     * @param int $year
     * @param int $month
     * @return mixed
     */
    public function getRolDetalleActividades($rol_id, $dni, int $year, int $month)
    {
        return PersonalRolDetalle::select(['actividad_id', 'fecha_turno'])
            ->where('personal_rol_id', $rol_id)
            ->where('persona_dni', $dni)
            ->where('estado_trabajo', true) // status de trabajo 
            ->whereYear('fecha_turno', $year)
            ->whereMonth('fecha_turno', $month)
            ->get()
            ->groupBy('actividad_id')
            ->map(function ($items) {
                return $items->pluck('actividad_id', 'fecha_turno');
            });
    }

    /**
     * @param int $rol_id
     * @param string $dni
     * @param int $year
     * @param int $month
     * @param array $actividades
     */
    public function storeActividades($rol_id, $dni, int $year, int $month, array $actividades)
    {
        $actividades_old = PersonalRolDetalle::select(['actividad_id', 'fecha_turno'])
            ->where('personal_rol_id', $rol_id) // id del rol
            ->where('persona_dni', $dni)
            ->get()
            ->groupBy('actividad_id')
            ->map(function ($items) {
                return $items->pluck('fecha_turno');
            })
            ->toArray();

        $actividades_inactivas = [];
        $actividades_keys = array_keys($actividades_old);
        // dd($actividades);
        foreach ($actividades_keys as $id) {
            if (isset($actividades['actividad_' . $id])) {
                $resultado = array_diff($actividades_old[$id], $actividades['actividad_' . $id]);
            } else {
                $resultado = array_diff($actividades_old[$id], []);
            }

            if ($resultado)
                $actividades_inactivas['actividad_' . $id] = $resultado;
        }

        // dd($actividades_inactivas);

        // actulizando las actividades que fueron modificadas
        foreach ($actividades_inactivas as $key => $dates) {
            list(, $actividad_id) = explode('_', $key);
            // $actividad = Actividade::find($actividad_id);
            foreach ($dates as $date) {

                PersonalRolDetalle::where('personal_rol_id', $rol_id)
                    ->where('persona_dni', $dni)
                    ->where('actividad_id', $actividad_id)
                    ->where('fecha_turno', $date)
                    ->update([
                        'personal_rol_id' => $rol_id,
                        'persona_dni' => $dni,
                        'actividad_id' => $actividad_id,
                        'fecha_turno' => $date,
                        'estado_trabajo' => false,
                    ]);
            }
        }

        foreach ($actividades as $key => $dates) {
            list(, $actividad_id) = explode('_', $key);
            // $actividad = Actividade::find($actividad_id);

            foreach ($dates as $date) {

                PersonalRolDetalle::updateOrCreate(
                    [
                        'personal_rol_id' => $rol_id,
                        'persona_dni' => $dni,
                        'actividad_id' => $actividad_id,
                        'fecha_turno' => $date,
                    ],
                    [
                        'personal_rol_id' => $rol_id,
                        'persona_dni' => $dni,
                        'actividad_id' => $actividad_id,
                        'fecha_turno' => $date,
                        'estado_trabajo' => true,
                    ]
                );
            }
        }
    }


    public function storeActividades_old($rol_id, $dni, int $year, int $month, array $actividades)
    {
        PersonalRolDetalle::query()
            ->where('personal_rol_id', $rol_id) // id del rol
            ->where('persona_dni', $dni)
            ->whereYear('fecha_turno', $year)
            ->whereMonth('fecha_turno', $month)
            ->delete();

        foreach ($actividades as $key => $dates) {
            list(, $actividad_id) = explode('_', $key);
            $actividad = Subactividade::find($actividad_id);

            $rolDetalle = [];

            foreach ($dates as $date) {

                $rolDetalle[] = new PersonalRolDetalle(
                    [
                        'personal_rol_id' => $rol_id,
                        'persona_dni' => $dni,
                        'fecha_turno' => $date,
                    ]
                );
            }
            // dd($rolDetalle);

            $actividad->personalRolDetalles()->saveMany($rolDetalle);
        }
    }

    /**
     * @param int $year
     * @param int $month
     * @return int
     */
    public function daysInMonth(int $year, int $month)
    {
        return now()->setYear($year)
            ->setMonth($month)
            ->daysInMonth;
    }

    /**
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getAttendancePaginationLinks(int $year, int $month)
    {
        $currentDate       = now()->setYear($year)->setMonth($month)->toImmutable();
        $currentDateYear   = $currentDate->year;
        $currentDateMonth  = $currentDate->format('m');
        $previousDate      = $currentDate->subMonth();
        $previousDateYear  = $previousDate->year;
        $previousDateMonth = $previousDate->format('m');
        $nextDate          = $currentDate->addMonth();
        $nextDateYear      = $nextDate->year;
        $nextDateMonth     = $nextDate->format('m');

        $dates = [
            [
                'year'     => $previousDateYear,
                'month'    => $previousDateMonth,
                'fullName' => $this->getYearAndFullMonthName($previousDateYear, $previousDateMonth),
            ],
            [
                'year'     => $currentDateYear,
                'month'    => $currentDateMonth,
                'fullName' => $this->getYearAndFullMonthName($currentDateYear, $currentDateMonth)
            ],
        ];

        if ($year < now()->year | ($year == now()->year && $month < now()->month)) {
            $dates[] = [
                'year'     => $nextDateYear,
                'month'    => $nextDateMonth,
                'fullName' => $this->getYearAndFullMonthName($nextDateYear, $nextDateMonth)
            ];
        }

        return $dates;
    }

    /**
     * @param int $year
     * @param int $month
     * @return bool
     */
    public function isProvidedMonthGreaterThanCurrentMonth(int $year, int $month)
    {
        if ($year > now()->year) {
            return true;
        }

        if ($year == now()->year && $month > now()->month) {
            return true;
        }

        return false;
    }

    /**
     * @param int $year
     * @param int $month
     * @return string
     */
    public function getYearAndFullMonthName(int $year, int $month)
    {
        return now()->setYear($year)->setMonth($month)->format('F Y');
    }
}

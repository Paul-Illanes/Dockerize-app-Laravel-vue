<?php
use Carbon\Carbon;

?>
@extends('layouts.mail-reclamos')
<p>
</p>

@section('message')
<p>
Estimado(a) {{$persona->full_name}}, su trámite fue registrado satisfactoriamente.
<br>
el código es: {{$reclamo->codigo}}
<br>
a continuacion los detalles:
</p>


@endsection

@section('detalle')

<table  width="708" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">

    <tr>
        <td style="font-size:22px; line-height:23px; font-family:arial; color:#E5243B; padding-bottom:20px; font-weight:bold; vertical-align:top;">
        </td>
    </tr>
    
</table>
<table >
    <thead>
        <tr>
            <th width="8px">#</th> 
            <th width="100px">Tipo</th>
            <th width="600px">Detalle</th>
        </tr>

    </thead> 
    <tbody>
        @foreach ( $reclamo->reclamoDetalle as $key =>  $item)
            
        <tr>
            <td>
                {{ ++$key }}
            </td>

            <td style="padding: 0 5px; font-size:12px; font-family:arial; color:#707070; ">
                {{$item->tipo_reclamo}}
            </td>
            <td style="padding: 0 5px; font-size:12px; font-family:arial; color:#707070; ">
                @if($item->tipo_guardia)
                    {{$item->tipoGuardia->guardia}}
                @endif
                @if($item->tipo_reten)
                    
                    {{$item->tipoReten->reten}}
                @endif
                @if($item->fecha)
                    {{ date('d-m-Y', strtotime($item->fecha)) }} 
                @endif

                {{$item->mes}}
                {{$item->anio}}

                @if($item->cantidad_horas)
                    - {{$item->cantidad_horas}} horas
                @endif


                @if ($item->motivo_descuento_id)
                    {{$item->motivoDescuento->descuento}}
                    - S/. {{$item->monto_descuento}} soles
                @endif

                @if ($item->sustento)
                    <p >
                        <strong>  sustento: </strong> 
                        <span > {{$item->sustento}} </span>
                    </p>
                @endif

            </td>
            
           
        </tr>
        @endforeach
        <tr>
            <td>
                Se enviará una copia de este email al encargad de Control de personal.
            </td>
        </tr>
    </tbody>
</table>

@endsection

<?php
use Carbon\Carbon;
?>
@extends('layouts.mail-reclamos')
<p>
</p>

@section('message')
<p>
Estimado(a) {{$persona->full_name}}, en respuesta a su trámite de código {{$reclamo->codigo}}
<br>
Su tramite es: 
@if ($reclamo->status === 2 )
    <strong >Fundado</strong>
@endif
@if ($reclamo->status === 3 )
    <strong >Parcialmente fundado</strong>
@endif
@if ($reclamo->status === 4 )
    <strong >Observado</strong>
@endif
<br>
a continuacion los detalles:
</p>


@endsection

@section('detalle')

<table  width="708" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">

    <tr>
        <td style="width:40px;"></td>
        <td style="font-size:22px; line-height:23px; font-family:arial; color:#E5243B; padding-bottom:20px; font-weight:bold; vertical-align:top;">
        </td>
    </tr>
    
</table>
<table >
    <thead>
        <tr>
            <th width="8px">#</th> 
            <th width="100px">Tipo</th>
            <th width="500px">Detalle</th>
            <th width="100px">Fundado</th>
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
                    <p class="m-0 ">
                        <strong>  sustento: </strong> 
                        <span >  {{$item->sustento}} </span>
                    </p>
                @endif

            </td>
            <td style="padding: 0 5px; font-size:12px; font-family:arial; color:#707070; text-align: center;">
                @if ($item->fundado == 0 )
                <span class="badge bg-warning">Evaluando</span>
                @endif
                @if ($item->fundado == 1 )
                <span class="badge bg-success">SI</span>
                @endif
                @if ($item->fundado == 2 )
                <span class="badge bg-danger">NO</span>
                @endif
            </td>
           
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

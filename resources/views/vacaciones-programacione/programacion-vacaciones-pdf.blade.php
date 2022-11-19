<?php
use Carbon\Carbon;

// $user = $member->user;
// $member_name= ucwords(strtolower($user->name.' '.$user->lastname));
// $conference_name = $conference->subject;
// $conference_date = Carbon::parse($conference->date)->format("d / M / Y");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Programación vacacional</title>
	<style>

	@page {
		margin: 0cm 0cm;
		font-family: Arial;
	}


	html, body {
		overflow-x: hidden;
	}

	body {
		margin: 1cm 3cm 1cm;
		min-height: 100%;
		overflow: visible;
		background-color: #ffffff;
		font-family: Arial, sans-serif;
		font-size: 12px;
		font-weight: 400;
		
		/* text-align: justify; */
	}

	footer {
		position: fixed;
		bottom: 0cm;
		left: 0cm;
		right: 0cm;
		height: 2cm;
		color: white;
		/* text-align: center; */
		line-height: 35px;
	}
	p{
		line-height: 1.8;
	}

	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}

	th, td {
		padding: 1px 3px;
  		text-align: left;
	}
	.table-name{
		min-width: 200px;
	}




	.text-bolder {
		color: #000;
		font-weight: bolder;
	}
	.text-right{
		float: right;
	}

	.text-lower {
		text-transform: lowercase;
	}

	.text-upper {
		text-transform: uppercase;
	}

	.text-capitalize {
		text-transform: capitalize;
	}

	.center-img, .center {
		display: flex;
		justify-content: center;
	}
	.text-justify
	{
		text-align: justify;
	}
	.text-small{
		font-size: 10px;
	}


	</style>
</head>


<body >
	{{-- cabecera --}}
	<header>
		<div class="text-right text-small">
			CIERRE DE PROCESO: {{$fecha_generacion}} / <span class="text-upper">{{$user}}</span>
			<br>
			{{$text_oficina}}
		</div>
		<br>
		<br>
		<div class="center-img">
          {{-- {{$_SERVER['SERVER_NAME']}} --}}
			{{-- <img src="{{ asset('logo-essalud-3.png') }}">  --}}
			{{-- <img src="{{$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/kerp-spa/public/logo-essalud-3.png'}}" alt="Logo" /> --}}
			{{-- {{$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/kerp-spa/public/logo-essalud-3.png'}} --}}
			{{-- <img src="https://oiss.org/wp-content/uploads/2000/01/Logo_EsSalud_2012.jpg" alt="Logo" /> --}}
			{{-- <img link=https://www.google.com/url?sa=i&url=http%3A%2F%2Fportal.essalud.gob.pe%2F&psig=AOvVaw0eSOlQKw5rBzX_3Dyc5TRy&ust=1668789704358000&source=images&cd=vfe&ved=0CBAQjRxqFwoTCOjsoJ7UtfsCFQAAAAAdAAAAABAE">  --}}
		    <img src="data:image/png;base64,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"> 
		</div>
		<br>
		<div>

		</div>
		<div class="text-right">
			<h3 class="text-upper">
				NIT:_____________________________
			</h3>
		</div>		
	</header>
	{{-- end cabecera --}}

	{{-- constancia autorizacion  --}}
	<br>
	<br>
	<br>
	<div class="center">
		<h4 class="text-upper">
		NOTA N°-_________-________________________________-GRACU-ESSALUD-{{now()->year}}
		</h4>
	</div>
	<p>Cusco,</p>
	<span>
		Señor.
	</span>
	<h3 class="text-upper">
			SR VICTORIANO LANADO DALES
		<br>
			JEFE DE LA DIVISION DE RECURSOS HUMANOS
		</h3>
	<p> Presente.-</p>
	<h3 class="text-upper">
	ASUNTO    : PROGRAMACION VACACIONAL PERIODO 2022-2023
	</h3>
	<h3 class="text-upper">
	REF:
	&nbsp;&nbsp;
	MEMORANDO CIRCULAR N°253-GCGP-ESSALUD-2022
	<br>
		&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;
		MEMORANDO CIRCULAR N°028-GRACU-ESSALUD-2022
	</h3>

	<p class="text-justify">
		Previo cordial saludo, en atención a los documentos de referencia se remite el cuadro de programación vacacional correspondiente al 
		<span class="text-bolder"> periodo 2022-2023 y ejecución 2023-2024, </span>, de todo el personal a mi cargo de acuerdo al siguiente detalle:

	</p>
	<br>

	<table style="width: 100%" >
		<thead >
			
			<tr>
				<th >#</th>
				<th  >CODIGO</th>
				<th  class="table-name">APELLIDOS Y NOMBRES</th>
				<th  >FECHA INGRESO</th>
				<th> 2023 </th>
				<th> 2024 </th>
				{{-- <th  >2022</th>
				<th  >2023</th> --}}
				<th  >REG. LAB.</th>
				
			</tr>
		</thead>
		<tbody>
			@foreach ($personal as $key => $persona)
				@php
					$vacacion = App\Models\VacacionesProgramacion::where('dni',$persona->dni)->where('periodo_vacacional', $selectedYear)->get()->first();
				@endphp
				<tr>
					<td >{{ ++$key }}</td>
					<td >{{ $persona->cod_planilla }}</td>
					<td >{{ $persona->full_name }}</td>
					<td >{{ $vacacion->fecha_ingreso ?? $persona->fecha_ingreso }}</td>
					{{-- <td >{{ $vacacion ? convert_month_to_name($vacacion->mes_programacion_solicitado) : '' }} {{ $vacacion ? ($selectedYear > 2022 ? -  $vacacion->anio_programacion_solicitado : '') : '' }}</td>  --}}
					<td >
						@if ($vacacion && $vacacion->anio_programacion_solicitado ==2023)
							{{convert_month_to_name($vacacion->mes_programacion_solicitado)}}
						@endif
					</td>
					<td>
						@if ($vacacion && $vacacion->anio_programacion_solicitado ==2024)
							{{convert_month_to_name($vacacion->mes_programacion_solicitado)}}
						@endif

					</td>
					<td >{{ cl_to_regimen_laboral($persona->condicion_laboral_id) ?? ''  }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>


	<p>
		Sin otro particular me suscribo de usted.
		<br>
		Atentamente.

	</p>	
	
	<br>
	<p>
		<span class="text-bolder">
		__________________________
		<br>
			Firma y Sello
		</span>
	</p>
	<br>
	{{-- end examen medico --}}

	<p class="text-right">
		Cusco, {{date('d')}} de {{convert_month_to_name(date('m'))}} del  {{ date("Y")}}
	</p>
	<br>
	<br>
	<br>
	

	<footer>
		<div class="text-right">
			{{-- {{date('d')}} de {{convert_month_to_name(date('m'))}} del  {{ date("Y")}} --}}
			{{-- <img src="{{ asset('/images/footer-constancia.png') }}">  --}}
		</div>
	</footer>

	<script type="text/php">

		if ( isset($pdf) ) {
		
		  $size = 6;
		  $color = array(0,0,0);
		  if (class_exists('Font_Metrics')) {
			$font = Font_Metrics::get_font("helvetica");
			$text_height = Font_Metrics::get_font_height($font, $size);
			$width = Font_Metrics::get_text_width("Page 1 of 2", $font, $size);
		  } elseif (class_exists('Dompdf\\FontMetrics')) {
			$font = $fontMetrics->getFont("helvetica");
			$text_height = $fontMetrics->getFontHeight($font, $size);
			$width = $fontMetrics->getTextWidth("Page 1 of 2", $font, $size);
		  }
		
		  $foot = $pdf->open_object();
		  
		  $w = $pdf->get_width();
		  $h = $pdf->get_height();
		
		  // Draw a line along the bottom
		   $y = $h - $text_height - 24;
		  // $pdf->line(16, $y, $w - 16, $y, $color, 0.5);
		
		  $pdf->close_object();
		  $pdf->add_object($foot, "all");
		
		  $text = "{PAGE_NUM} / {PAGE_COUNT}";  
		  $text2 = date('d-m-Y');  
		
		  // Center the text
		  $pdf->page_text($w / 2 - $width / 2, $y-10, $text2, $font, $size, $color);
		  $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
		  
		}
		</script>
</body>
</html>
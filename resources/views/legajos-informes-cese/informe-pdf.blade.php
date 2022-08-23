<?php
use Carbon\Carbon;

// $user = $member->user;
// $member_name= ucwords(strtolower($user->name.' '.$user->lastname));
// $conference_name = $conference->subject;
// $conference_date = Carbon::parse($conference->date)->format("d / M / Y");
$regimen_laboral = parameter_single_value($informe->regimen_laboral);
$grupo_ocupacional = parameter_single_value($informe->grupo_ocupacional);
$regimen_pensionario = parameter_single_value($informe->regimen_pensionario);
$linea_carrera = parameter_single_value($informe->linea_carrera);
$condicion_laboral = parameter_single_value($informe->condicion_laboral);
$modalidad_contrato = parameter_single_value($informe->modalidad_contrato);
$dependencia = parameter_single_value($informe->dependencia);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Inorme de cese</title>
	<style>

	@page {
		margin: 0cm 0cm;
		font-family: Arial;
	}


	html, body {
		overflow-x: hidden;
	}

	body {
		margin: 2cm 3cm 1cm;
		min-height: 100%;
		overflow: visible;
		background-color: #ffffff;
		font-family: Arial, sans-serif;
		font-size: 11px;
		font-weight: 400;
		
		/* text-align: justify; */
	}

	footer {
		position: fixed;
		bottom: 0cm;
		left: 0cm;
		right: 0cm;
		height: 3cm;
		color: white;
		/* text-align: right; */
		line-height: 35px;
	}

	tr > td > p{
		line-height: 1;
		margin-top: 0.5em;
		margin-bottom: 0.5em;
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
	.text-center{
		text-align: center;
	}

	.center-img, .center {
		display: flex;
		justify-content: center;
	}
	.text-justify
	{
		text-align: justify;
	}


	</style>
</head>


<body >
	{{-- cabecera --}}
	<header>
		<div class="center-img">

			{{-- <img src="{{ asset('/images/logo-essalud-2.png') }}">  --}}
		</div>
		<div class="text-center">

			<p class="text-bolder text-upper">
				informe N {{$informe->numero_informe}} -ULBP-DRH_OA-GRACU-ESSALUD-{{ date('Y', strtotime(now())) }}
				<br>
				(cese)
			</p>
		</div>
	</header>
	{{-- end cabecera --}}

	{{-- informe --}}

	<table>
		<tr class="text-upper">
			<td width =250px> <p> apellidos y nombres </p></td>
			<td width =400px>: {{$informe->persona->full_name }}</td>
		</tr>

		<tr class="top-border">
			<td class=" text-upper"> documento de identidad</td>
			<td> : {{ $informe->dni }}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> codigo de planilla</td>
			<td> : {{ $informe->codigo_planilla }}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> fecha de nacimiento </p></td>
			<td> : {{ date('d/m/Y', strtotime($informe->fecha_nacimiento)) }}</td>
		</tr>

		<tr class="top-border">
			<td class=" text-upper"> <p> fecha de ingreso </p></td>
			<td> : {{ date('d/m/Y', strtotime($informe->fecha_ingreso)) }}</td>
		</tr>
		
		<tr class="top-border">
			<td class=" text-upper"> <p> ultimo dia de labor </p></td>
			<td> : {{ date('d/m/Y', strtotime($informe->ultimo_dia_labor)) }}</td>
		</tr>

		<tr class="top-border">
			<td class=" text-upper"> <p> fecha de cese </p></td>
			<td> : {{ date('d/m/Y', strtotime($informe->fecha_cese)) }}</td>
		</tr>
		

		<tr class="top-border">
			<td class=" text-upper"> regimen laboral d.l.</td>
			{{-- <td> : {{ $informe->regimen_laboral }}</td> --}}
			@if($regimen_laboral)
			<td> : {{ $regimen_laboral }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> grupo ocupacional</td>
			{{-- <td> : {{ $informe->grupo_ocupacional }}</td> --}}
			@if($grupo_ocupacional)
			<td> : {{ $grupo_ocupacional->value }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> numero de documento con el cual se cesa al trabajador o funcionario</td>
			<td> : {{ $informe->numero_documento_cese }}</td>
		</tr>
		
		<tr class="top-border">
			<td class=" text-upper"> motivo de cese</td>
			<td> : {{ $informe->motivo_cese }}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> regimen pensionario</td>
			{{-- <td> : {{ $informe->regimen_pensionario }}</td> --}}
			
			@if($regimen_pensionario)
			<td> : {{ $regimen_pensionario }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> linea de carrera</td>
			{{-- <td> : {{ $informe->linea_carrera }}</td> --}}
			
			@if($linea_carrera)
			<td> : {{ $linea_carrera }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> condicion laboral</td>
			{{-- <td> : {{ $informe->condicion_laboral }}</td> --}}
			
			@if($condicion_laboral)
			<td> : {{ $condicion_laboral }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> modalidad de contrato</td>
			{{-- <td> : {{ $informe->modalidad_contrato }}</td> --}}
			{{-- <td> : {{ parameter_single_value($informe->modalidad_contrato) }}</td> --}}
			@if($modalidad_contrato)
			<td> : {{ $modalidad_contrato }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> dependencia</td>
			{{-- <td> : {{ $informe->dependencia }}</td> --}}
			{{-- <td> : {{ parameter_single_value($informe->dependencia) }}</td> --}}
			@if($dependencia)
			<td> : {{ $dependencia }}</td>
			@else
			<td> : no registra </td>
			@endif
		</tr>

		<tr class="top-border">
			<td > <p> <span class=" text-upper"> licencia s/g. haber</span> <br> (Indicar pendientes o descontadas) </p> </td>
			<td> : {{$informe->licencia_sg_haber}}</td>
		</tr>
		
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> sancion disciplinaria</span> <br> (Indicar pendientes o descontadas) </p> </td>
			<td> : {{$informe->sancion_disciplinaria}}</td>
		</tr>
		
		
		<tr class="top-border">
			<td class=" text-upper"> <p> licencia c/g/h covid 19</p></td>
			<td> : {{$informe->licencia_covid}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> permiso particular</p></td>
			<td> : {{$informe->permisos_particulares}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> a cuenta de vacaciones</p></td>
			<td> : {{$informe->acuenta_vacaciones}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> tiempo de servicio</p></td>
			<td> : {{$informe->tiempo_servicio}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> Total tpo. deducible </p></td>
			<td> : {{$informe->total_tpo_deducible}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> Total tpo. serv. a ESSALUD</p></td>
			<td> : {{$informe->total_tpo_essalud}}</td>
		</tr>
	</table>
	<p>
		NIT: {{ $informe->nit }}
	</p>
	
	
	
	

	<p >
		{{-- Cusco, 07 de Diciembre de 2021 --}}
		{{-- Cusco, {{ date('l, d M Y') }} --}}
		<?php
		setlocale(LC_ALL,"es_PE.utf8");
		echo 'Cusco, '. strftime(" %d de %B del %Y", strtotime($informe->fecha_informe));
		// echo 'Cusco, '. date(' d B Y', strtotime($informe->fecha_informe));
		
		//Salida: viernes 05 de Septiembre del 2016
		?>
	</p>

	{{-- <footer>
		<div class="text-right">
			<img width="200px" src="{{ asset('/images/footer-informe.png') }}"> 
		</div>
	</footer> --}}
</body>
</html>
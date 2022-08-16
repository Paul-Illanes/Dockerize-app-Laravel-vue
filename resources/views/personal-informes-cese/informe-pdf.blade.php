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
				anexo 2
				<br>
				informe N {{$informe->numero_informe}} control de personal
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
			<td class=" text-upper"> <p> fecha de ingreso </p></td>
			<td> : {{ date('d.m.Y', strtotime($informe->fecha_ingreso)) }}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> fecha de cese </p></td>
			<td> : {{ date('d.m.Y', strtotime($informe->fecha_cese)) }}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> motivo de cese</td>
			<td> : {{ $informe->motivo_cese }}</td>
		</tr>
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> faltas </span> <br> (Indicar pendientes o descontadas)</p></td>
			<td> : {{$informe->faltas}}</td>
		</tr>
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> tardanzas</span> <br> (Pendientes de descuento) </p> </td>
			<td> : {{$informe->tardanzas}}</td>
		</tr>
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> licencia s/g. haber</span> <br> (Indicar pendientes o descontadas) </p> </td>
			<td> : {{$informe->licencias}}</td>
		</tr>
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> permisos particulares</span> <br> (Pendientes de descuento) </p> </td>
			<td> : {{$informe->permisos_particulares}}</td>
		</tr>
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> sancion disciplinaria</span> <br> (Indicar pendientes o descontadas) </p> </td>
			<td> : {{$informe->sancion_disciplinaria}}</td>
		</tr>
		<tr class="top-border">
			<td > <p> <span class=" text-upper"> huelga</span> <br> (Indicar pendientes o descontadas) </p> </td>
			<td> : {{$informe->huelga}}</td>
		</tr>
		
		<tr class="top-border">
			<td class=" text-upper"> <p> licencia c/g/h covid 19</p></td>
			<td> : {{$informe->licencia_covid}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> vacaciones no gozadas</p></td>
			<td> : {{$informe->vacaciones_no_gozadas}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> periodo de pago bono de productividad</p></td>
			<td> : {{$informe->bono_pago}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> descuento de pago bono de productividad por no corresponder su pago</p></td>
			<td> : {{$informe->descuento_bono_pago}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> guardias hospitalarias</p></td>
			<td> : {{$informe->guardias}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> horas extra</p></td>
			<td> : {{$informe->horas_extras}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> horas rpct</p></td>
			<td> : {{$informe->horas_rpct}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> pct</p></td>
			<td> : {{$informe->pct}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> notas adicionales </p></td>
			<td> : {{$informe->notas_adicionales}}</td>
		</tr>
		<tr class="top-border">
			<td class=" text-upper"> <p> zona de menor desarrollo</p></td>
			<td> : {{$informe->zona_menor_desarrollo}}</td>
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
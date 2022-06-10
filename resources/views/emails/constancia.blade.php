<?php
use Carbon\Carbon;
?>
@extends('layouts.mail')
<p>
</p>

@section('message')
<p>
Estimado(a) {{$persona->full_name}}, 
<br>se le remite, como archivo adjunto, su constancia vacacional correspondiente al periodo: {{ $periodo}}.
</p>
{{-- <p>si tiene dudas o comentarios, por favor contacté con el área de Recursos Humanos.</p> --}}

@endsection



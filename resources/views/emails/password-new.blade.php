<?php
use Carbon\Carbon;
$pwd =$code;
?>
@extends('layouts.mail')
<p>
</p>

@section('message')
<p>
Estimado(a) {{$user->name}} {{$user->lastname}} {{$user->mother_lastname}}, su nueva clave temporal es:
</p>


@endsection

@section('code')
{{$pwd}} 
@endsection
<p> Recuerde modificar su clave luego de iniciar sesión.</p>

<?php
use Carbon\Carbon;
$pin_code =$code;
?>
@extends('layouts.mail')
<p>
</p>

@section('message')
<p>
Estimado(a) {{$user->name}} {{$user->lastname}} {{$user->mother_lastname}}, su código de validación es:
</p>


@endsection

@section('code')
{{$pin_code}} 
@endsection


<!-- views/payees/edit.blade.php -->

@extends('master')

@section('content')

	{!! Form::model($payee, ['method' => 'PUT', 'action' => ['PayeesController@update', $payee['id']], 'class' => "form-inline"]) !!}
		@include('payees._form', ['buttonText' => "Update"])
	{!! Form::close() !!}

@stop
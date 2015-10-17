<!-- views/payees/create.blade.php -->

@extends('master')

@section('content')

	{!! Form::open(['action' => "PayeesController@store", 'class' => "form-inline"]) !!}
		@include('payees._form', ['buttonText' => "Add New"])
	{!! Form::close() !!}

@stop
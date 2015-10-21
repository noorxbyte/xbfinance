<!-- views/transfers/create.blade.php -->

@extends('master')

@section('content')
	
	{!! Form::open(['action' => "TransfersController@store", 'class' => "form-horizontal"]) !!}
		@include('transfers._form', ['buttonText' => "Add New"])
	{!! Form::close() !!}

@stop
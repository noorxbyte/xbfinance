<!-- views/accounts/create.blade.php -->

@extends('master')

@section('content')

	{!! Form::open(['action' => "AccountsController@store", 'class' => "form-inline"]) !!}
		@include('accounts._form', ['buttonText' => "Add New"])
	{!! Form::close() !!}

@stop
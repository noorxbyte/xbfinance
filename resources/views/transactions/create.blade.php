<!-- views/transactions/create.blade.php -->

@extends('master')

@section('content')
	
	{!! Form::open(['action' => "TransactionsController@store", 'class' => "form-horizontal"]) !!}
		@include('transactions._form', ['buttonText' => "Add New"])
	{!! Form::close() !!}

@stop
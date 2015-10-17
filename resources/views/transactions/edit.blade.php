<!-- views/transactions/edit.blade.php -->

@extends('master')

@section('content')
	
	{!! Form::model($transaction, ['action' => ["TransactionsController@update", $transaction['id']], 'method' => "PUT", 'class' => "form-horizontal"]) !!}
		@include('transactions._form', ['buttonText' => "Update"])
	{!! Form::close() !!}

@stop
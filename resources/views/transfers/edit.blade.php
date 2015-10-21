<!-- views/transfers/edit.blade.php -->

@extends('master')

@section('content')
	
	{!! Form::model($transfer, ['action' => ["TransfersController@update", $transfer['id']], 'method' => "PUT", 'class' => "form-horizontal"]) !!}
		@include('transfers._form', ['buttonText' => "Update"])
	{!! Form::close() !!}

@stop
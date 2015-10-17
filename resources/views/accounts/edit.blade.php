<!-- views/accounts/edit.blade.php -->

@extends('master')

@section('content')

	{!! Form::model($account, ['method' => 'PUT', 'action' => ['AccountsController@update', $account['id']], 'class' => "form-inline"]) !!}
		@include('accounts._form', ['buttonText' => "Update"])
	{!! Form::close() !!}

@stop
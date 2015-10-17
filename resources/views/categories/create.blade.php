<!-- views/categories/create.blade.php -->

@extends('master')

@section('content')

	{!! Form::open(['action' => "CategoriesController@store", 'class' => "form-inline"]) !!}
		@include('categories._form', ['buttonText' => "Add New"])
	{!! Form::close() !!}

@stop
<!-- views/categories/edit.blade.php -->

@extends('master')

@section('content')

	{!! Form::model($category, ['method' => 'PUT', 'action' => ['CategoriesController@update', $category['id']], 'class' => "form-inline"]) !!}
		@include('categories._form', ['buttonText' => "Update"])
	{!! Form::close() !!}

@stop
<!-- views/error.blade.php -->

@extends('master')

@section('content')

	<div class="alert alert-danger">{{ $errmsg }}</div>
	
	<div class="text-center"><strong><a href="javascript:history.go(-1);">&lt;&lt; Back</a></strong></div>

@stop
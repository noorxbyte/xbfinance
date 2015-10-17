<!-- views/auth/login.blade.php -->

@extends('master')

@section('content')
	
<form class="form-horizontal" action="{{ route('login') }}" method="post">
	<fieldset>

	{!! csrf_field() !!}

	@if (count($errors) > 0)
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif

	<!-- Email-->
	<div class="form-group">
		<label class="col-md-4 control-label" for="email">Email</label>  
		<div class="col-md-4">
			<input autofocus id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Email Address" class="form-control input-md" required>
		</div>
	</div>

	<!-- Password-->
	<div class="form-group">
		<label class="col-md-4 control-label" for="password">Password</label>
		<div class="col-md-4">
			<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required>
		</div>
	</div>

	<!-- Remember -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="checkbox"></label>
		<div class="col-md-4">
			<input type="checkbox" name="remember"> Remember Me
		</div>
	</div>

	<!-- Submit & Register -->
	<div class="form-group">
		<label class="col-md-4 control-label" for=""></label>
		<div class="col-md-4">
			<div class="group">
				<button class="btn btn-primary">Log in</button>&nbsp;&nbsp;&nbsp;
				<span class="pull-right"><a href="{{ route('register') }}"><u>Register for an account</u></a></span>
			</div>
		</div>
	</div>

	<!-- Forgot Password -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="checkbox"></label>
		<div class="col-md-4">
			<a href="{{ route('reset.getEmail') }}">Forgot Password?</a>
		</div>
	</div>

	</fieldset>
</form>

@stop
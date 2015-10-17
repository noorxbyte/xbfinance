<!-- views/auth/register.blade.php -->

@extends('master')

@section('content')
	
<form class="form-horizontal" action="{{ route('register') }}" method="post">
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
		<label class="col-md-4 control-label" for="email">Name</label>  
		<div class="col-md-4">
			<input autofocus id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Name" value="{{ old('name') }}" class="form-control input-md" required>
		</div>
	</div>

	<!-- Email-->
	<div class="form-group">
		<label class="col-md-4 control-label" for="email">Email</label>  
		<div class="col-md-4">
			<input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Email Address" value="{{ old('email') }}" class="form-control input-md" required>
		</div>
	</div>

	<!-- Password-->
	<div class="form-group">
		<label class="col-md-4 control-label" for="password">Password</label>
		<div class="col-md-4">
			<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required>
		</div>
	</div>

	<!-- Password confirmation-->
	<div class="form-group">
		<label class="col-md-4 control-label" for="password">Confirm Password</label>
		<div class="col-md-4">
			<input id="password_confirmation" name="password_confirmation" type="password" placeholder="Password Confirmation" class="form-control input-md" required>
		</div>
	</div>

	<!-- Remember -->
	<div class="form-group">
		<label class="col-md-4 control-label" for=""></label>
		<div class="col-md-4">
			<div class="text-right">Already have an account? <a href="{{ route('login') }}">Login</a></div>
		</div>
	</div>

	<!-- Submit -->
	<div class="form-group">
		<label class="col-md-4 control-label" for=""></label>
		<div class="col-md-4">
			<button id="" name="" class="btn btn-primary">Log in</button>
		</div>
	</div>

	</fieldset>
</form>


@stop
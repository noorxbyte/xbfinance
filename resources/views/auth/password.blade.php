<!-- views/auth/password.blade.php -->

@extends('master')

@section('content')

	@if (session('status'))
		<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('status') }}
        </div>
    @endif

	@if (count($errors) > 0)
    	<div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
    @endif

	<div class="text-center">
		{!! Form::open(['action' => "Auth\PasswordController@postEmail", 'class' => "form-inline"]) !!}
		    {!! csrf_field() !!}

			<!-- email -->
		    <div class="form-group">
		        <input autofocus type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
		    </div>

			<!-- Submit -->
		    <div class="form-group">
		        <button type="submit" class="btn btn-primary">
		            Send Password Reset Link
		        </button>
		    </div>
		{!! Form::close() !!}
	</div>

@stop
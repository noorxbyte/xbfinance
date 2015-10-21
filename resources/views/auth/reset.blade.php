<!-- resources/views/auth/reset.blade.php -->

@extends('master')

@section('content')

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
        {!! Form::open(['action' => "Auth\PasswordController@postReset", 'class' => "form-horizontal"]) !!}
            {!! csrf_field() !!}
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email"></label>  
                <div class="col-md-4">
                    <input autofocus name="email" type="email" placeholder="Email Address" class="form-control input-md" required>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="password"></label>  
                <div class="col-md-4">
                    <input name="password" type="password" placeholder="New Password" class="form-control input-md" required>
                </div>
            </div>

            <!-- Password Confirmation -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="password_confirmation"></label>  
                <div class="col-md-4">
                    <input name="password_confirmation" type="password" placeholder="New Password Confirmation" class="form-control input-md" required>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-group">
                <label class="col-md-4 control-label" for=""></label>
                <div class="col-md-4">
                    <button class="btn btn-primary">Reset Password</button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>

@stop
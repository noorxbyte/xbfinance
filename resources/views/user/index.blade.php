<!-- views/user/index.blade.php -->

@extends('master')

@if (Session::has('active_id'))
	<input type="hidden" id="active_id" value="{{ Session::get('active_id') }}">
@endif

@section('content')

	<div class="text-center">
		<ul class="nav nav-tabs">
			<li><a data-toggle="tab" href="#summary">Summary</a></li>
			<li><a data-toggle="tab" href="#changeName">Change Name</a></li>
			<li><a data-toggle="tab" href="#changePass">Change Password</a></li>
			<li><a data-toggle="tab" href="#changeTheme">Theme</a></li>
			<li><a data-toggle="tab" href="#delAcc">Delete My Account</a></li>
		</ul>
	</div>

	<div class="tab-content">

		<div id="summary" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				@include('user._summary')
			</div>
		</div>

		<div id="changeName" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				@include('user._changeName')
			</div>
		</div>

		<div id="changePass" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				@include('user._changePass')
			</div>
		</div>

		<div id="changeTheme" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				@include('user._changeTheme')
			</div>
		</div>

		<div id="delAcc" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				@include('user._deleteAccount')
			</div>
		</div>

	</div>

@stop
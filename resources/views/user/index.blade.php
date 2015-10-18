<!-- views/user/index.blade.php -->

@extends('master')

@section('content')

	<div class="text-center">
		<ul class="nav nav-tabs">
			<li><a data-toggle="tab" href="#validate">Validate</a></li>
			<li><a data-toggle="tab" href="#changeName">Change Name</a></li>
			<li><a data-toggle="tab" href="#changePass">Change Password</a></li>
			<li><a data-toggle="tab" href="#changeTheme">Theme</a></li>
			<li><a data-toggle="tab" href="#delAcc">Delete My Account</a></li>
		</ul>
	</div>

	<div class="tab-content">

		<div id="validate" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				// Validate
			</div>
		</div>

		<div id="changeName" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				// Change Name
			</div>
		</div>

		<div id="changePass" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				// Change Pass
			</div>
		</div>

		<div id="changeTheme" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				{!! Form::open(['action' => 'UserController@changeTheme', 'class' => 'form-inline']) !!}
					<fieldset>
						<!-- theme select -->
						<div class="form-group">
							<select class="form-control" name="theme">
								@foreach($themes as $theme)
									<option value="{{ $theme->id }}">{{ $theme->name }}</option>
								@endforeach
							</select>
						</div>
						
						<!-- submit -->
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Change Theme</button>
						</div>
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>

		<div id="delAcc" class="tab-pane fade">
			<br/><br/>
			<div class="text-center">
				// Delete Account
			</div>
		</div>

	</div>

@stop
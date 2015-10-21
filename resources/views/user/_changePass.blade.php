{!! Form::open(['action' => 'UserController@changePass', 'class' => 'form-horizontal']) !!}
	<fieldset>
		<!-- Old Password-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="password"></label>  
			<div class="col-md-4">
				<input name="old_password" type="password" placeholder="Old Password" class="form-control input-md" required>
			</div>
		</div>

		<!-- New Password-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="new_password"></label>  
			<div class="col-md-4">
				<input name="new_password" type="password" placeholder="New Password" class="form-control input-md" required>
			</div>
		</div>

		<!-- Password Confirm -->
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
				<button id="" name="" class="btn btn-primary">Change Password</button>
			</div>
		</div>
	</fieldset>
{!! Form::close() !!}
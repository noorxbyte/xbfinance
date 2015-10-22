{!! Form::open(['action' => 'UserController@changeName', 'class' => 'form-horizontal']) !!}
	<fieldset>
		<!-- Name-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="name">Name</label>
			<div class="col-md-4">
				<input name="name" type="text" value="{{ Auth::user()->name }}" placeholder="Name" class="form-control input-md" required>
			</div>
		</div>

		<!-- Email-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="email">Email</label>  
			<div class="col-md-4">
				<input autofocus name="email" type="email" value="{{ Auth::user()->email }}" placeholder="Email Address" class="form-control input-md" required>
			</div>
		</div>
		
		<!-- submit -->
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Change Name/Email</button>
		</div>
	</fieldset>
{!! Form::close() !!}
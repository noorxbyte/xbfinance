{!! Form::open(['action' => 'UserController@changeName', 'class' => 'form-inline']) !!}
	<fieldset>
		<!-- name -->
		<div class="form-group">
			<input type="text" name="name" placeholder="Your Name" class="form-control" value="{{ Auth::user()->name }}" max="255">
		</div>
		
		<!-- submit -->
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Change Name</button>
		</div>
	</fieldset>
{!! Form::close() !!}
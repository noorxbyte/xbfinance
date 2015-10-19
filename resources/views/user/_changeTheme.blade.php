{!! Form::open(['action' => 'UserController@changeTheme', 'class' => 'form-inline']) !!}
	<fieldset>
		<!-- theme select -->
		<div class="form-group">
			<select class="form-control" name="theme">
				@foreach($themes as $theme)
					<option value="{{ $theme->id }}">{{ strtoupper($theme->name) }}</option>
				@endforeach
			</select>
		</div>
		
		<!-- submit -->
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Change Theme</button>
		</div>
	</fieldset>
{!! Form::close() !!}
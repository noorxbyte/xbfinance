<fieldset>
	<div class="text-center">
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

		<div class="form-group">
			{!! Form::text('name', null, ['placeholder' => "Payee Name", 'class' => "form-control", 'required', 'autofocus']) !!}
		</div>
		<div class="form-group">
			<button id="" name="" class="btn btn-primary">{{ $buttonText }}</button>
		</div>
	</div>
</fieldset>
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

	<!-- Date -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="date">Date</label>  
		<div class="col-md-4">
			{!! Form::text('date', null, ['placeholder' => "Date", 'id' => "dpicker", 'class' => "form-control", 'required']) !!}
		</div>
	</div>

	<!-- From -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="account_id">From</label>
		<div class="col-md-4">
			{!! Form::select('account_from', ['0' => '-- Account --'] + $accounts, null, ['class' => "form-control"]) !!}
		</div>
	</div>

	<!-- To -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="account_id">To</label>
		<div class="col-md-4">
			{!! Form::select('account_to', ['0' => '-- Account --'] + $accounts, null, ['class' => "form-control"]) !!}
		</div>
	</div>

	<!-- Amount-->
	<div class="form-group">
		<label class="col-md-4 control-label" for="amount">Amount</label>  
		<div class="col-md-4">
			{!! Form::number('amount', null, ['placeholder' => "Amount", 'step' => '0.01', 'class' => "form-control", 'required']) !!}
		</div>
	</div>

	<!-- Comment -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="comment">Comment</label>
		<div class="col-md-4">                     
			{!! Form::textarea('comment', null, ['placeholder' => 'Comment on Transaction', 'class' => "form-control", 'rows' => '5']) !!}
		</div>
	</div>

	<!-- Submit -->
	<div class="form-group">
		<label class="col-md-4 control-label" for="singlebutton"></label>
		<div class="col-md-4">
			<button class="btn btn-primary">{{ $buttonText }}</button>
		</div>
	</div>

</fieldset>
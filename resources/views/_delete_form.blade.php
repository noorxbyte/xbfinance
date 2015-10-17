<div class="hidden">
	{!! Form::open(['method' => 'DELETE', 'id' => 'del_form']) !!}
		<input disabled type="hidden" id="del_msg" value="{{ $message }}">
	{!! Form::close() !!}
<div>
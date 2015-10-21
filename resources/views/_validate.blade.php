{!! Form::open(['action' => 'UserController@validateBalances', 'id' => 'validateForm']) !!}
	{!! csrf_field() !!}
{!! Form::close() !!}
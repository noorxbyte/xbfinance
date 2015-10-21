<!-- resources/views/user/validate.blade.php -->

@extends('master')

@section('content')

	@if($news)
		<div class="alert alert-info">{{ $message }}</div>
	@else
		<div class="alert alert-warning">{{ $message }}</div>
	@endif

	<table class="table table-striped table-nonfluid">
		<thead>
			<th>Account</th>
			<th>Account Balance</th>
			<th>Calculated Balance</th>
			<th>Offset</th>
		</thead>
		<tbody>
			@foreach($accounts as $account)
				<tr>
					<td><a href="{{ route('accounts.show', $account->id) }}">{{ $account->name }}</a></td>
					<td>${{ number_format($account->balance, 2) }}</td>
					<td>${{ number_format($account->cbalance, 2) }}</td>
					<td>${{ number_format(($account->cbalance - $account->balance), 2) }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@stop
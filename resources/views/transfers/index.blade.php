<!-- views/transfers/index.blade.php -->

@extends('master')

@section('content')

	@if(sizeof($transfers) > 0)
		<table class="table table-striped sortable">
			<thead>
				<th>Actions</th>
				<th>Date</th>
				<th>From</th>
				<th>To</th>
				<th>Amount</th>
				<th>Comment</th>
			</thead>
			<tbody>
				@foreach($transfers as $transfer)
					<tr>

						<td>
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Actions
								<span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="{{ route('transfers.edit', $transfer['id']) }}">Edit</a></li>
									<li><a href="{{ route('transfers.destroy', $transfer['id']) }}" class="btn_del">Delete</a></li>
								</ul>
							</div>
						</td>

						<td>{{ (new \DateTime($transfer['date']))->format('d/m/Y') }}</td>
						<td><a href="{{ route('accounts.show', $transfer['account_from']) }}">{{ $transfer->from->name or '-' }}</a></td>
						<td><a href="{{ route('accounts.show', $transfer['account_to']) }}">{{ $transfer->to->name or '-' }}</a></td>
						<td>${{ number_format($transfer['amount'], 2) }}</td>
						<td>{{ $transfer['comment'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<br/>
		{!! $transfers->render() !!}

		@include('_delete_form', ['message' => 'Are you sure you want to delete the transfer? The transfer will be rolled back!'])
	@else
		<h4>You haven't made any transfers</h4>
	@endif

@stop
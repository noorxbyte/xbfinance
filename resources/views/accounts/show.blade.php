<!-- views/accounts/show.blade.php -->

@extends('master')

@section('content')

	@if(sizeof($transactions) > 0)
		<table class="table table-striped sortable">
			<thead>
				<th>Actions</th>
				<th>Date</th>
				<th>Type</th>
				<th>Payee</th>
				<th>Category</th>
				<th>Amount</th>
				<th>Comment</th>
			</thead>
			<tbody>
				@foreach($transactions as $transaction)
					<tr>

						<td>
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Actions
								<span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="{{ route('transactions.edit', $transaction['id']) }}">Edit</a></li>
									<li><a href="{{ route('transactions.destroy', $transaction['id']) }}">Delete</a></li>
								</ul>
							</div>
						</td>

						<td>{{ (new \DateTime($transaction['date']))->format('d/m/Y') }}</td>
						<td>{{ $transaction['type'] }}</td>
						<td><a href="{{ route('payees.show', $transaction['payee']) }}">{{ $transaction['payee']['name'] or 'N/A' }}</a></td>
						<td><a href="{{ route('categories.show', $transaction['category']) }}">{{ $transaction['category']['name'] or 'N/A' }}</a></td>
						<td>${{ number_format($transaction['amount'], 2) }}</td>
						<td>{{ $transaction['comment'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<br/>
		{!! $transactions->render() !!}

		@include('_delete_form', ['message' => 'Are you sure you want to delete the transaction? It will be rolled back!'])
	@else
		<h4>No transactions for this account</h4>
	@endif

@stop
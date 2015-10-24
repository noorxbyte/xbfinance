<!-- views/accounts/show.blade.php -->

@extends('master')

@section('content')

	@if(sizeof($transactions) > 0)
		@include('_modal', [
			'title' => 'Delete Transaction',
			'message' => 'Are you sure you want to delete the transaction? The transaction will be rolled back and balances reset.'
		])

		{!! Form::open(['route' => ['accounts.show', $transactions[0]->account_id], 'method' => 'GET', 'class' => 'form-inline']) !!}
			<div class="form-group">
				{!! Form::select('sort', ['date' => 'Date', 'amount' => 'Amount'], null, ['class' => 'form-control input-sm']) !!}
			</div>
			<div class="form-group">
				{!! Form::select('order', ['DESC' => 'Descending', 'ASC' => 'Ascending'], null, ['class' => 'form-control input-sm']) !!}
			</div>
			<div class="form-group">
				{!! Form::button('Sort', ['type' => 'submit', 'class' => 'btn btn-default input-sm'], 'Sort') !!}
			</div>
		{!! Form::close() !!}

		<br/>

		<table class="table table-striped">
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
									<li><a href="{{ route('transactions.destroy', $transaction['id']) }}" class="btn-del">Delete</a></li>
								</ul>
							</div>
						</td>

						<td>{{ (new \DateTime($transaction['date']))->format('d/m/Y') }}</td>
						<td>{{ $transaction['type'] }}</td>
						<td><a href="{{ ($transaction['payee'] !== null) ? route('payees.show', $transaction['payee']) : route('payees.show', 0) }}">{{ $transaction['payee']['name'] or '-- N/A --' }}</a></td>
						<td><a href="{{ ($transaction['category'] !== null) ? route('categories.show', $transaction['category']) : route('categories.show', 0) }}">{{ $transaction['category']['name'] or '-- N/A --' }}</a></td>
						<td>${{ number_format($transaction['amount'], 2) }}</td>
						<td>{{ $transaction['comment'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		{!! $transactions->appends(['sort' => old('sort'), 'order' => old('order')])->render() !!}
		<div class="text-center"><b>Page {{ $transactions->currentPage() }} of {{ Session::get('total_count') }}</b></div><br/>

	@else
		<h4>No transactions for this account</h4>
	@endif

@stop
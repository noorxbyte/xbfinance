<!-- views/payees/index.php -->

@extends('master')

@section('content')

	@if(sizeof($payees) != 0)
		@include('_modal', [
			'title' => 'Delete Payee',
			'message' => "Are you sure you want to delete the payee? All the transactions of the payee will be marked as NULL payee."
		])

		<table class="table table-bordered table-nonfluid sortable">
			<thead>
				<th>Actions</th>
				<th>Payee Name</th>
				<th>Income Total</th>
				<th>Expense Total</th>
				<th>Income %</th>
				<th>Expense %</th>
			</thead>
			<tbody>
				@foreach($payees as $payee)
					<tr>
						
						<td>
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Actions
								<span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="{{ route('payees.edit', $payee['id']) }}">Edit</a></li>
									<li><a href="{{ route('payees.destroy', $payee['id']) }}" class="btn-del">Delete</a></li>
								</ul>
							</div>
						</td>

						<td><a href="{{ route('payees.show', $payee['id']) }}">{{ $payee['name'] }}</a></td>
						<td>${{ number_format($payee->transactions->where('type', 'DEPOSIT')->sum('amount'), 2) }}</td>
						<td>${{ number_format($payee->transactions->where('type', 'WITHDRAWAL')->sum('amount'), 2) }}</td>
						<td>{{ ($incomeTotal > 0) ? round(((($payee->transactions->where('type', 'DEPOSIT')->sum('amount')) / $incomeTotal) * 100), 2) : 0 }}%</td>
						<td>{{ ($expenseTotal > 0) ? round(((($payee->transactions->where('type', 'WITHDRAWAL')->sum('amount')) / $expenseTotal) * 100), 2) : 0 }}%</td>
					</tr>
				@endforeach
				<tr>
					<td class="text-center">-</td>
					<td><a href="{{ route('payees.show', 0) }}">-- N/A --</a></td>
					<td>${{ number_format($transactions->where('type', 'DEPOSIT')->sum('amount'), 2) }}</td>
					<td>${{ number_format($transactions->where('type', 'WITHDRAWAL')->sum('amount'), 2) }}</td>
					<td>{{ ($incomeTotal > 0) ? round(((($transactions->where('type', 'DEPOSIT')->sum('amount')) / $incomeTotal) * 100), 2) : 0 }}%</td>
					<td>{{ ($expenseTotal > 0) ? round(((($transactions->where('type', 'WITHDRAWAL')->sum('amount')) / $expenseTotal) * 100), 2) : 0 }}%</td>
				</tr>
			</tbody>
		</table>
	@else
		<h4>You don't have any payees.</h4>
	@endif

@stop
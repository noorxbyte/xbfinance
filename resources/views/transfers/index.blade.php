<!-- views/transfers/index.blade.php -->

@extends('master')

@section('content')

	@if(sizeof($transfers) > 0)
		@include('_modal', [
			'title' => 'Delete Transfer',
			'message' => "Are you sure you want to delete the transfer? The transfer will be rolled back and balances reset."
		])
	
		<div class="row-fluid">
			<!-- Sort Form -->
			<div class="col-sm-6">
				{!! Form::open(['action' => $action, 'method' => 'GET', 'class' => 'form-inline']) !!}
					<div class="form-group">
						{!! Form::select('sort', ['date' => 'Date', 'amount' => 'Amount'], null, ['class' => 'form-control input-sm']) !!}
					</div>
					<div class="form-group">
						{!! Form::select('order', ['DESC' => 'Descending', 'ASC' => 'Ascending'], null, ['class' => 'form-control input-sm']) !!}
						{!! Form::input('hidden', 'q', null) !!}
					</div>
					<div class="form-group">
						{!! Form::button('Sort', ['type' => 'submit', 'class' => 'btn btn-default input-sm'], 'Sort') !!}
					</div>
				{!! Form::close() !!}
			</div>

			<!-- Search Form -->
			<div class="col-sm-6">
				{!! Form::open(['action' => 'TransfersController@search', 'method' => 'GET', 'class' => 'form-inline pull-right']) !!}
					<div class="form-group">
						{!! Form::input('search', 'q', null, ['class' => 'form-control input-sm', 'placeholder' => 'Search Transfers']) !!}
					</div>
					<div class="form-group">
						{!! Form::button('Search', ['type' => 'submit', 'class' => 'btn btn-default input-sm'], 'Sort') !!}
					</div>
				{!! Form::close() !!}
			</div>
		</div>

		<br/><br/><br/>

		<table class="table table-striped">
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
									<li><a href="{{ route('transfers.destroy', $transfer['id']) }}" class="btn-del">Delete</a></li>
								</ul>
							</div>
						</td>

						<td>{{ (new \DateTime($transfer['date']))->format('d/m/Y') }}</td>
						<td><a href="{{ route('accounts.show', $transfer['account_from']) }}">{{ $transfer->from->name or '-- N/A --' }}</a></td>
						<td><a href="{{ route('accounts.show', $transfer['account_to']) }}">{{ $transfer->to->name or '-- N/A --' }}</a></td>
						<td>${{ number_format($transfer['amount'], 2) }}</td>
						<td>{{ $transfer['comment'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		<!-- pager -->
		{!! $transfers->appends(['q' => old('q'), 'sort' => old('sort'), 'order' => old('order')])->render() !!}

	@else
		<h4>{{ $emptyMsg or "You haven't made any transfers" }}</h4>
	@endif

@stop
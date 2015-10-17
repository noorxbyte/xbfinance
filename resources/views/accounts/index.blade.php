<!-- views/accounts/index.blade.php -->

@extends('master')


@section('content')

	@if(sizeof($accounts) != 0)

		<table class="table table-bordered table-nonfluid table-condensed">
			<thead>
				<th>Actions</th>
				<th>Accounts</th>
				<th>Balance</th>
			</thead>
			<tbody>
				@foreach($accounts as $account)
				<tr>

					<td>
						<div class="dropdown">
							<button class="btn btn-default dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Actions
							<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="{{ route('accounts.edit', $account['id']) }}">Edit</a></li>
								<li><a href="{{ route('accounts.destroy', $account['id']) }}" class="btn_del">Delete</a></li>
							</ul>
						</div>
					</td>

					<td><a href="{{ route('accounts.show', $account['id']) }}">{{ $account['name'] }}</a></td>
					<td class="text-right">${{ $account['balance'] }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		@include('_delete_form', ['message' => 'Are you sure you want to delete the account? All the transactions of the account will be lost!'])
	@else

		<h4>You don't have any accounts</h4>

	@endif

@stop
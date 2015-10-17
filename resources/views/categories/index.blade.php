<!-- views/categories/index.blade.php -->

@extends('master')

@section('content')

	@if(sizeof($categories) != 0)
		<table class="table table-bordered table-nonfluid sortable">
			<thead>
				<th>Actions</th>
				<th>Category</th>
				<th>Income %</th>
				<th>Expense %</th>
			</thead>
			<tbody>
				@foreach($categories as $category)
					<tr>

						<td>
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Actions
								<span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="{{ route('categories.edit', $category['id']) }}">Edit</a></li>
									<li><a href="{{ route('categories.destroy', $category['id']) }}" class="btn_del">Delete</a></li>
								</ul>
							</div>
						</td>

						<td><a href="{{ route('categories.show', $category['id']) }}">{{ $category['name'] }}</a></td>
						<td>{{ ($incomeTotal > 0) ? round(((($category->transactions->where('type', 'DEPOSIT')->sum('amount')) / $incomeTotal) * 100), 2) : 0 }}%</td>
						<td>{{ ($expenseTotal > 0) ? round(((($category->transactions->where('type', 'WITHDRAWAL')->sum('amount')) / $expenseTotal) * 100), 2) : 0 }}%</td>
					</tr>
				@endforeach
				<tr>
					<td class="text-center">-</td>
					<td><a href="{{ route('categories.show', 0) }}">N/A</a></td>
					<td>{{ ($incomeTotal > 0) ? round(((($transactions->where('type', 'DEPOSIT')->sum('amount')) / $incomeTotal) * 100), 2) : 0 }}%</td>
					<td>{{ ($expenseTotal > 0) ? round(((($transactions->where('type', 'WITHDRAWAL')->sum('amount')) / $expenseTotal) * 100), 2) : 0 }}%</td>
				</tr>
			</tbody>
		</table>

		@include('_delete_form', ['message' => 'Are you sure you want to delete the category?'])
	@else
		<h4>You don't have any categories.</h4>
	@endif

@stop
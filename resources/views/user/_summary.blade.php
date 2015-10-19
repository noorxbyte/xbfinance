<div class="text-center">
	<table class="table table-striped table-nonfluid">
		<tbody>
			<tr>
				<td><b>Account ID</b></td>
				<td>{{ $user->id }}</td>
			</tr>
			<tr>
				<td><b>Registered On</b></td>
				<td>{{ $user->created_at }}</td>
			</tr>
			<tr>
				<td><b>Name</b></td>
				<td>{{ $user->name }}</td>
			</tr>
			<tr>
				<td><b>Email</b></td>
				<td>{{ $user->email }}</td>
			</tr>
			<tr>
				<td><b>Theme</b></td>
				<td>{{ strtoupper($user->theme) }}</td>
			</tr>
			<tr>
				<td><b>Total Income</b></td>
				<td>$ {{ number_format($incomeTotal, 2) }}</td>
			</tr>
			<tr>
				<td><b>Total Expenses</b></td>
				<td>$ {{ number_format($expenseTotal, 2) }}</td>
			</tr>
		</tbody>
	</table>
</div>
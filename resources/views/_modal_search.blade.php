<!-- Modal -->
<div id="searchForm" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Advanced Search</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#transactions">Transactions</a></li>
					<li><a data-toggle="tab" href="#transfers">Transfers</a></li>
				</ul>

				<div class="tab-content">
					<div id="transactions" class="tab-pane fade in active">
						<br/><h4>Search Transactions</h4>
						{!! Form::open(['action' => 'TransactionsController@index', 'method' => 'GET']) !!}
							<input type="search" name="q" placeholder="Search Transactions" max="255">
							<button type="submit" class="btn btn-default">Search</button>
						{!! Form::close() !!}
					</div>
					<div id="transfers" class="tab-pane fade">
						<br/><h4>Menu 2</h4>
						<p>Some content in menu 2.</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>
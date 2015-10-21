<!-- Modal -->
<div id="delConfirm" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">{{ $title }}</h4>
			</div>
			<div class="modal-body">
				<div class="text-center">
					{!! Form::open(['method' => 'DELETE', 'id' => 'delForm']) !!}
						<div class="form-group">
							<h4 class="text-danger">{{ $message }}</h4><br/>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger" form="delForm">Delete</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>
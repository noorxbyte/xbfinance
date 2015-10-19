

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Proceed To Delete My Account</button><br/><br/>
<div class="text-center"><strong><a href="javascript:history.go(-1);"><span class="glyphicon glyphicon-backward"></span> Get Me Out of Here</a></strong></div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Delete My Account</h4>
			</div>
			<div class="modal-body">
				<p>Enter your password to delete your account.</p>
				{!! Form::open(['method' => 'DELETE', 'action' => 'UserController@deleteAccount', 'class' => 'form-horizontal']) !!}
					<div class="form-group">
						<label class="col-md-4 control-label" for="password"></label>
						<div class="col-md-4">
							<input autofocus type="password" name="password" placeholder="Password" class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label"></label>
						<div class="col-md-4">
							<button type="submit" class="btn btn-danger">Delete My Account</button>
						</div>
					</div>
	
				{!! Form::close() !!}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>
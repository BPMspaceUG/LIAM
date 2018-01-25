<?php
	include_once '_path_url.inc.php';
?>

<?php
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		
if (isset($logged) && $logged == 'in') {
			# Button ADMIN only avaiable when define logged in user has role "admin"--->
			echo '<a class="btn btn-large btn-default" title="Admin" href="'.$url_liam.'register.php">Admin</a>&nbsp';
			echo '<button type="button" class="btn btn-large btn-default" data-toggle="modal" data-target="#MyProfile_Modal">My Profile</button>&nbsp';
			echo '<a class="btn btn-large btn-default" title="Logout" href="' . $url_liam . 'phpSecureLogin/includes/logout.php"><i class="fa fa-sign-out"></i>&nbsp;Logout</a>&nbsp';
		} 

echo '

<!-- Modal -->

<div id="MyProfile_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">My Profile</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="row alert alert-success text-center lead" role="alert">You are currently logged in.</br>Last logon was: [DATE TIME]</div>
								</div>				
							</div>
						</div>
					</div>
				</div>
				<div class="container-fluid">
					<div class="row">
					</br>
					</div>
				</div>
				


				
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="lead">Lastename: </br>Firstname:</br>E-Mail:</br>Role:</br></div>	
								</div>
								<div class="col-md-6">
									<div class="lead">[Lastname] </br>[Firstname]</br>[e-mail]</br>[Role1, Role2, ...]</br></div>	
								</div>								

								<div class="col-md-12">
									</br>
									<a data-toggle="modal" data-target="#name_modal"><button type="button" class="btn btn-primary-outline">Change Name</i></button></a>
									<a data-toggle="modal" data-target="#email_modal"><button type="button" class="btn btn-primary-outline">Change E-Mail</i></button></a>
									<a data-toggle="modal" data-target="#password_modal"><button type="button" class="btn btn-primary-outline">Change Password</i></button></a>
								</div>								
								
							
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div class="modal-footer">
				<div class="clearfix"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>


<div class="modal fade" id="password_modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Change Password</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="current_password" class="control-label">Current
						Password</label>
					<div class="controls">
						<input type="password" name="current_password">

					</div>
				</div>
				<div class="control-group">
					<label for="new_password" class="control-label">New Password</label>
					<div class="controls">
						<input type="password" name="new_password">

					</div>
				</div>
				<div class="control-group">
					<label for="confirm_password" class="control-label">Confirm
						Password</label>
					<div class="controls">
						<input type="password" name="confirm_password">

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="email_modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Change E-Mail</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="new_E-Mail" class="control-label">E-Mail</label>
					<div class="controls">
						<input type="E-Mail" name="new_E-Mail" placeholder="[E-MAIL}">
					</div>
				</div>
				<div class="control-group">
					<label for="confirm_E-Mail" class="control-label">Confirm E-Mail</label>
					<div class="controls">
						<input type="E-Mail" name="confirm_E-Mail">

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="name_modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Change Name</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="new_firstname" class="control-label">Firstname</label>
					<div class="controls">
						<input type="Firstname" name="new_Firstname" placeholder="[FIRSTNAME}">
					</div>
					<label for="new_lastname" class="control-label">Lastname</label>
					<div class="controls">
						<input type="Lastname" name="new_Lastname" placeholder="[LASTNAME}">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>';
}
?>

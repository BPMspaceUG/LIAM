<div class="container">
	<div class="row">
			<div class="col-sm-12 col-md-10 col-md-offset-1">
				<form action="phpSecureLogin/includes/login.php" method="post"
					name="login_form" id="loginForm">
					<div class="form-group input-group">
						<span class="input-group-addon"></span> <input
							class="form-control" type="text" name='email' placeholder="email" />
					</div>
					<div class="form-group input-group">
						<span class="input-group-addon"></span> <input
							class="form-control" type="password" name='password'
							placeholder="password" id="password" />
					</div>
					<div class="form-group">
						<input class="btn btn-primary-outline btn-block" type="button" value="Login"
							onclick="formhash(this.form, this.form.password);" />
						<!---- Button Register only avaiable when define("CAN_REGISTER", "any");--->
						<button type="button" class="btn btn-primary-outline btn-block" data-toggle="modal" data-target="#Register">Register</button>
						<button type="button" class="btn btn-primary-outline btn-block" data-toggle="modal" data-target="#Register">Forgot Password?</button>
						
					</div>
				</form>
			</div>
	</div>
</div>


<!----------------- Modal ----------------->


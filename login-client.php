
<div id="login-form" class="col-md-4 col-xs-12 col-sm-12 col-md-offset-4 centering">
	<div id="logo">	
		<img src="img/logo.jpg" class="img-responsive" alt="Nitro Logo">
	</div>
	<form role="form" action="login.php">
		<div class="form-group">
			<div class="input-group col-md-12 col-xs-12 col-sm-12 ">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" name="username" id="username" class="form-control" placeholder="Username">
			</div>
		</div>
		<div class="form-group">
			<div class="input-group col-md-12 col-xs-12 col-sm-12">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" name="password" id="password" class="form-control" placeholder="Password">
			</div>
		</div>
		<div id="result" class="col-md-12 col-xs-12 col-sm-12 pull-left">
		</div>
		<div class="form-group" id="misc">
	        <label>
	          <input type="checkbox" name="remember"> Remember me
	        </label>
			<button type="submit" class="btn btn-primary btn-sm pull-right">Login</button>
		</div>
		<div class="form-group">
			<p>New User? <a href="register.php">Register</a></p>
		</div>
	</form>
</div>

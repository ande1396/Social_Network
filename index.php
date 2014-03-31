<?php
	session_start();
	include_once("connection.php"); 

?>

<html>
<head>
	<!DOCTYPE html>
	<meta charset="UTF-8">
	<title>KyleAnde Network | Join the community</title>
	<meta name="author" content="Kyle Anderson">
	<meta name="description" content="Network for KyleAnde">
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel="stylesheet" type="text/css" href="css/main.css">

</head>
	<body>
		<header>
			<div class="paperstreet">
				KyleAnde<span> The Social Network</span>
			</div>
		</header>
		<div class="wrapper">
			<div class="left">
				<div id="login">
					<h2>Login</h2>
					<form role="form" action="process.php" method="post">
						<input type="hidden" name="login_action" value="login">

						<div class="form-group">
						    <label class="sr-only" for="email"></label>
						    <input type="email" required name="email" class="form-control" id="email" placeholder="Enter Email">
						</div>
						<div class="form-group">
							<label class="sr-only" for="password"></label>
						    <input type="password" required name="password" class="form-control" id="password" placeholder="Password">
						</div>
						<button type="submit" class="btn btn-primary" id="login-btn">Log in</button>
					</form>
				<?php

					if (isset($_SESSION['login_errors'])) 
					{
						foreach ($_SESSION['login_errors'] as $errors) 
						{
							echo "<p class='alert-danger'>" . $errors . "</p>";
						}
						unset($_SESSION['login_errors']);
					}
					elseif(isset($_SESSION['log_success'])) 
					{
						echo "<p class='success'>" . $_SESSION['log_success'] . "</p>";
					}
						unset($_SESSION['log_success']);
				?>

				</div>

				<div id="register">
					<?php
						if(isset($_SESSION['failure']))
						{
							foreach ($_SESSION['failure'] as $fail) 
							{
								echo "<p id='fail'>" . $fail . "</p>";
							}
							unset($_SESSION['failure']);
						}
						elseif (isset($_SESSION['success'])) 
						{
							echo "<p id='success'>" . $_SESSION['success'] . "</p>"; 
						}
							unset($_SESSION['success']);
					?>
					<h2 id="register">Join the KyleAnde Network</h2>
						<form role="form" action="process.php" method="post">
						  <input type="hidden" name="register_action" value="register">
							<div class="form-group">
							    <label class="sr-only" for="first_name"></label>
							    <input type="text" required name="first_name" class="form-control" id="first_name" placeholder="First Name">
							</div>

							<div class="form-group">
							    <label class="sr-only" for="last_name"></label>
							    <input type="text" required name="last_name" class="form-control" id="last_name" placeholder="Last Name">
							</div>

							<div class="form-group">
							    <label class="sr-only" for="email"></label>
							    <input type="email" required name="email" class="form-control" id="email" placeholder="email">
							</div>

							<div class="form-group">
							    <label class="sr-only" for="birth_date"></label>
							    <input type="date" required name="birth_date" class="form-control" id="birth_date" placeholder="Birth Date">
							</div>

							<div class="form-group">
							    <label class="sr-only" for="password"></label>
							    <input type="password" required name="password" class="form-control" id="password" placeholder="Password (Greater than 6 characters)">
							</div>

							<div class="form-group">
							    <label class="sr-only" for="confirm_password"></label>
							    <input type="password" required name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password">
							</div>		
								<p id="terms">Make sure all fields are correctly filled out</p>
								<button type="submit" class="btn btn-success" id="submit">Register</button>
						</form> 
				</div><!-- end of register-->

			</div> <!-- end of left --> 

			<div class="right">
				<h1 id="main-p">Welcome to the KyleAnde Network</h1>
				<p>Register or use the email and password below to login</p>
				<p>Email: user@kyleande.com</p>
				<p>Password: testpassword</p>
				<p>Users can add friends, make posts,  comment on those post</p>
				<p>Site was developed in PHP, MySQL, HTML5, &amp; CSS3</p>
			</div><!-- end of class right --> 
		</div><!-- end of wrapper --> 
	</body>
</html>
<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<head>
	<title>Register</title>
</head>

<body class="login-register-body">
	<a href="/Client/login" class="login-register-button"><span class="slide-arrow">Login Here</span></a><br><br>
	<h1 class="login-h">Please Register Below!</h1>
	<center>
		<div class="login-register-box">
			<?php
			if ($data != null) {
				echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"width: 400px; height: 50px;\">$data</div>";
			} else {
				// echo "<div class=\"alert alert-light\" role=\"alert\">Registration</div>";
				echo "<br><br>";
			}

			?>
			<form class="login-register-form" action='' method='post'>
				<div style="text-align: left;">
                    Username: <br> <input class="login-register" type='text' name='username' /><br>
					First Name: <br> <input type='text' class="login-register" name='first_name' />
					<br>
					Last Name: <br><input class="login-register" type='text' name='last_name' /><br>
					Password: <br> <input class="login-register" type='password' name='password' /><br>
					Password confirmation: <br> <input class="login-register" type='password' name='password_confirm' /><br><br>
					<input class="login-register-button rainbow rainbow-5" type='submit' name='action' value='Register' />
				</div>
			</form>
		</div>
	</center>
</body>

</html>
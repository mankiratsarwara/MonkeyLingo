<html>

<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title>Register</title>
</head>
<link rel="stylesheet" href="\css/styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fugaz+One&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<body style="background-image: url('\\media\/background.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: cover;">
	<div style="margin-left: 2vw; margin-top: 2vh;">
		<div style="width: fit-content">
			<h1 style="font-family: 'Fugaz One', cursive; color: #5a4836; text-shadow: -7px 5px 8px #7a7a7a;">MonkeyLingo</h1>
		</div>
	</div>

	<center>
		<div class="login-register-box">
			<?php
			if (isset($data['error'])) {
				echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"width: 400px; height: 50px;\">{$data['error']}</div>";
			} else {
				// echo "<div class=\"alert alert-light\" role=\"alert\">Registration</div>";
				echo "<br><br>";
			}

			?>
			<div style="width: 500px;  border: 2px solid #241414;  padding: 50px;  margin: 20px; border-radius: 15px; background: #ffffff8c; box-shadow: 0px 0px 20px 8px #5c5c5c;">
				<img style="width: 75px" src="<?php echo $data['image']; ?>" alt="Monkey">
				<h3 style="margin-bottom: 30px">Registration</h3>
				<form class="login-register-form" action='' method='post'>
					<div style="width: fit-content;">
						<div style="text-align: left;">Username: <br></div>
						<input class="login-register-input" style="margin-bottom: 20px;" type='text' name='username' /><br>
					</div>
					<div style="width: fit-content;">
						<div style="text-align: left;">First name: <br></div>
						<input class="login-register-input" style="margin-bottom: 20px;" name='first_name' /><br>
					</div>
					<div style="width: fit-content;">
						<div style="text-align: left;">Last name: <br></div>
						<input type='text' class="login-register-input" style="margin-bottom: 20px;" name='last_name' /><br>
					</div>
					<div style="width: fit-content;">
						<div style="text-align: left;">Password: <br></div>
						<input class="login-register-input" style="margin-bottom: 20px;" type='password' name='password' /><br>
					</div>
					<div style="width: fit-content;">
						<div style="text-align: left;">Confirm Password: <br></div>
						<input type='password' class='login-register-input' name='password_confirm' /><br><br>
					</div>
					<input type='submit' name='action' value='Register' />
			</div>
			</form>
		</div>
	</center>
</body>

</html>
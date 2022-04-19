<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
	<title>Login</title>
</head>

<body>
	<h1>MonkeyLingo</h1>
	<h3>Login</h3>
	<center>
		<div>
			<?php
			if ($data != null) {
				echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"width: 400px; height: 50px;\">$data</div>";
			} else {
				// echo "<div class=\"alert alert-light\" role=\"alert\">Registration</div>";
				echo "<br><br>";
			}
			?>
			<div>
				<form action='' method='post'>
					Username: <br><input type='text' name='username' /><br>
					Password: <br><input type='password' name='password' /><br><br>
					<input type='submit' name='action' value='Login' />
				</form>
				<a href="/Client/register"><span class="slide-arrow">Register Here</span></a>
			</div>
		</div>
	</center>

</body>

</html>
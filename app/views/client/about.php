<!DOCTYPE html>
<html lang="en">
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<link rel="stylesheet" href="\css/styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fugaz+One&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<head>
	<title>About</title>
</head>

<body>
	<nav class="navbar navbar-light bg-light fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand mx-auto" href="/webclient/translate" style="margin-left: 50px;"><img src="/media/monkey.png" style="height: 100px"></a></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
				<div class="offcanvas-header">
					<h5 class="offcanvas-title" id="offcanvasNavbarLabel">MonkeyLingo</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
					<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
						<li class="nav-item">
							<a class="nav-link active" href="/webclient/about">About</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/webclient/detect">Detect</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/webclient/translate">Translate</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/webclient/logout">Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<div class="main">
		<div style="padding: 20px; margin-top: 150px; text-align: center;">
			<h1>About</h1>
			<h2>Established in 2022.</h2>
			<p style="margin-left: 250px; margin-right: 250px;">We are a team of 2 students from the Computer Science program at Vanier College. MonkeyLingo was created with the goal of making communication
				between people easier and more efficient. We hope to make it easier for you to communicate with your friends and family.
			</p>
		</div>
	</div>

</body>

</html>
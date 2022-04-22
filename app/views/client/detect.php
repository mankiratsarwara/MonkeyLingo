<!DOCTYPE html>
<html lang="en">
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<head>
	<title>Detect</title>
</head>

<body>
	<nav class="navbar navbar-light bg-light fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand mx-auto" href="/Client/home" style="margin-left: 50px;"><img src="/media/monkey.png" style="height: 100px"></a></a>
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
							<a class="nav-link" aria-current="page" href="/Client/Home">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/Client/about">About</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="/Client/detect">Detect</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/Client/translate">Translate</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/Client/logout">Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<div class="main">
		<div style="padding: 20px; margin-top: 150px; text-align: center; ">
            <h1 class="welcome">Please enter some text below to detect</h1>
            <?php if(isset($data['language'])){
                    echo '<h3> Detected language: '.$data['language'].'</h3>';
            } else if(isset($data['error'])){
                    echo '<h3>'.$data['error'].'</h3>';
            } ?>
            <form action='' method='post'>
                <textarea name="string" cols="50" rows="15" placeholder="Bonjour! Hola! Hi! Guten Tag!"></textarea> <br>
                <input class="login-register-button rainbow rainbow-5" type='submit' name='action' value='Detect' />
            </form>
        </div>
	</div>
</body>

</html>
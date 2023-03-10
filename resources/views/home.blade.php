<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="http://localhost/app_mentorizacion/resources/css/HomeStyle.css">
</head>
<body>
	<header>
		<nav>
            <img src="http://localhost/app_mentorizacion/resources/css/logo_blanco.JPG" class = "logo">
			<ul>
				<li><a href=" {{ route('users.create') }} ">Crear Cuenta</a></li>
				<li><a href=" {{ route('users.index') }} ">Iniciar Sesión</a></li>
			</ul>
		</nav>
	</header>

	<main>
		<h1>MENTORING</h1>
		<p>En esta web te podrás encontrar una red social donde según el rol que selecciones podrás mentorizar a alumnos o ser mentorizado:</p>
		<p>En caso de que seas mentor podrás acceder a una sala en la que enseñar como trabjas a los alumnos que aceptes en esta sala. </p>
		<p>En caso de que sea un alumno podrás seleccionar un mentor pidiendole ser añadido a su sala para que te enseñe más sobre el mundo laboral </p>
	</main>

	<footer>
		<p>Derechos reservados 2023</p>
	</footer>
</body>
</html>

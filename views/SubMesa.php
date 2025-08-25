<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="./src/img/table.png">
	<link rel="stylesheet" href="./Recursos/fontawessonmeAll.css" />
	<link rel="stylesheet" href="./Recursos/bootstrap/Css/bootstrap.min.css" />
	<link rel="stylesheet" href="./Recursos/pro.fontawessome.css" />
	<link rel="stylesheet" href="./src/css/SubMesa.css">
	<link rel="stylesheet" href="./src/css/Default.css">
	<title>Mesas</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg  bg-primary  d-flex justify-content-between">
		<div>
			<i class="fas fa-user-tie text-light fa-2x"></i>
			<h5 id="Mozo" class="text-light"></h5>
		</div>

		<div class="Options">
			<button id="Waiting">
				<i class="fas fa-print"></i> Impr. Ord. en Espera
			</button>
			<button id="Dividir">
				<i class="fas fa-plus-square"></i> Dividir Mesa
			</button>
		</div>

	</nav>
	<div id="ListMesas">
	</div>
	<button id="Retornar">Volver a mesa</button>
</body>
<script src="./Recursos/sweetalert2.all.min.js"></script>
<script src="./src/js/Default.js"></script>
<script src="./src/js/SubMesa.js"></script>
</html>
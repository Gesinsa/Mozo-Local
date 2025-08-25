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
	<link rel="stylesheet" href="./src/css/Pedido.css">
	<link rel="stylesheet" href="./src/css/Default.css">
	<title>Mesas</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg  bg-primary  d-flex justify-content-between">
		<div>
			<i class="fas fa-user-tie text-light fa-2x"></i>
			<h5 id="Mozo" class="text-light"></h5>
		</div>
	</nav>
	<div class="status-bar">
		<div id="Agregar" class="btn btn-lg btn-primary">
			<i class="fas fa-plus-circle"></i> Agregar
		</div>
		<div id="Resumir" class="btn btn-lg btn-info">Resumir Cuenta</div>
		<div id="Imprimir" class="btn btn-lg btn-info">
			<i class="fas fa-print"></i> Imprimir 
		</div>
		<div id="Dividir" class="btn btn-lg btn-outline-danger">
			<i class="fas fa-plus-square"></i> Dividir
		</div>
		<div id="Note" class="btn btn-lg btn-info">N Cocina / Ord. Espera
		</div>
	</div>
	<div class="invoice">
		<div class="invoice-Header">
			<h5 id="Mesa">Mesa:</h5>
			<span></span>
			<h4 id="Pedido">Pedido:</h4>
			<h5 id="Hora">Hora Entrada:</h5>
		</div>
		<div class="leaves">
			<div class="header-leaves">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="titles-leaves">
				<div id="Nombre">Nombre:<input type="text" maxlength="25" placeholder="Nombre Cliente"></div>
				<div id="Fecha"></div>
			</div>
			<div id="List-leaves">

			</div>
			<div class="Footer-leaves">
				<div>
					<span>Monto Bruto</span>
					<span id="Bruto">00.0</span>
				</div>
				<div>
					<span>Itbis+</span>
					<span id="Itbis">00.0</span>
				</div>
				<div>
					<span>Ley+</span>
					<span id="Ley">00.0</span>
				</div>
				<div class="Total">
					<span>Total</span>
					<span id="Total">00.0</span>
				</div>
			</div>

		</div>
	</div>
	<button id="Retornar">Volver a Mesas</button>

	<dialog id="Nota">
		<header class="Nota_Header">
			<h4>Imprimir Nota en Cocina</h4>
		</header>
		<main class="Nota_Body">

			<div class="Notas">
				<span class="Title">Nota1:</span>
				<textarea rows="3" cols="55" maxlength="280" placeholder="Inserte aqui la Nota">Sale comida en Mesa</textarea>
			</div>
			<div class="Notas">
				<span class="Title">Nota2:</span>
				<textarea rows="3" cols="55" maxlength="280" placeholder="Inserte aqui la Nota"></textarea>
			</div>
		</main>
		<footer class="Nota_Fotter">
			<button id="Print">Impr. Nota en Cocina</button>
			<button id="Waiting">Impr. Ord. en Espera</button>

			<button id="Cancel">Cancelar</button>
		</footer>
	</dialog>
</body>
<script src="./src/js/Default.js"></script>
<script src="./src/js/Pedido.js"></script>
<script src="./Recursos/sweetalert2.all.min.js"></script>

</html>
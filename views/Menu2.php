<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="./src/img/table.png">
      <link rel="stylesheet" href="./Recursos/fontawessonmeAll.css"/>
      <link rel="stylesheet" href="./Recursos/bootstrap/Css/bootstrap.min.css" />
      <link rel="stylesheet" href="./Recursos/pro.fontawessome.css" />   		
		<link rel="stylesheet" href="./src/css/Default.css">
		<link rel="stylesheet" href="./src/css/Menu.css">
		<title>Menú</title>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg  bg-primary  d-flex justify-content-between">
			<div>
				<i class="fas fa-user-tie text-light fa-2x"></i>
				<h5 id="Mozo" class="text-light"></h5>
         </div>
			<h3 id="Mesa">Mesa:</h3>
			<div id="Carrito">
				<i class="fas fa-shopping-cart fa-2x"></i>
				<button id="Cantidad">0</button>
			</div>
	
      </nav>
		<div class="Logo">
			<img src="./src/img/fondo.jpg">
		</div> 
		<div class="Busquda">
			<input id="Busqueda" type="text" placeholder="Busqueda"  maxlength="35">
			<button><i class="fas fa-search"></i></button>
		</div>	
		<div id="Menu" class="List">
      </div>
      <button id="Retornar">Cerrar Menú</button>
	</body>
	<script src="./src/js/Default.js"></script>
	<script src="./src/js/Menu2.js"></script>
	<script src="./Recursos/sweetalert2.all.min.js"></script>
</html>
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
		<link rel="stylesheet" href="./src/css/Mesas.css">
		<link rel="stylesheet" href="./src/css/Default.css">
		<title>Mesas</title>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg  bg-primary  d-flex justify-content-between">
			<div>
				<i class="fas fa-user-tie text-light fa-2x"></i>
				<h5 id="Mozo" class="text-light"></h5>
         </div>
         <div class="d-flex justify-content-between">
            <i class="fas fa-sliders-h fa-2x text-light" style="margin-right: 10px;"></i>
            <i id="Logout" class="fas fa-sign-out-alt fa-2x text-light"></i>
			</div>	
      </nav>
		<div class="Status-Table">	
			<button class="Options badge-success">libres</button>
			<button class="Options badge-warning">Atendiendo</button>
			<button class="Options Waiting">En Espera</button>
			<button class="Options badge-primary">F. Separadas</button>
			<button class="Options badge-danger">Ocupada</button>								
		</div>	
		<div id="List-Mesa">

		</div>
		<button id="Retornar">Volver a Areas</button>
	</body>
	<script src="./src/js/Default.js"></script>
	<script src="./src/js/Mesas.js"></script>
	<script src="./Recursos/sweetalert2.all.min.js"></script>
</html>
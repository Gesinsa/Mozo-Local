<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="./src/img/table.png">
      <link rel="stylesheet" href="./Recursos/fontawessonmeAll.css"/>
      <link rel="stylesheet" href="./Recursos/bootstrap/Css/bootstrap.min.css" />
      <link rel="stylesheet" href="./Recursos/pro.fontawessome.css" />   		
		<link rel="stylesheet" href="./src/css/Pedido.css">
		<link rel="stylesheet" href="./src/css/Cart.css">
		<link rel="stylesheet" href="./src/css/Default.css">
		<title>Mesas</title>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg bg-primary  d-flex">
			<div id="Cerrar">
				<i class="btn fas fa-arrow-alt-circle-left fa-2x"></i>
         </div>
			<div>
				<i class="fas fa-user-tie text-light fa-2x"></i>
				<h5 id="Mozo" class="text-light"></h5>
         </div>
      </nav>
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

		<div class="Buttons">
			<button id="Pagar">Confirmar</button>
			<button id="Imprimir">Confirmar & Imprimir</button>
		</div>
			
	</body>
	<script src="./src/js/Default.js"></script>
   <script src="./src/js/Cart.js"></script>
	<script src="./Recursos/sweetalert2.all.min.js"></script>
	<script src="./Recursos/moment.min.js"></script>
</html>
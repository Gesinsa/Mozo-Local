<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="./src/img/table.png">
	<link rel="stylesheet" href="./Recursos/fontawessonmeAll.css" />
	<link rel="stylesheet" href="./Recursos/pro.fontawessome.css" />
	<link rel="stylesheet" href="./Recursos/bootstrap/Css/bootstrap.min.css" />
	<link rel="stylesheet" href="./src/css/Default.css">
	<link rel="stylesheet" href="./src/css/Article.css">
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
	<main id="App_Article">
		<div v-if="loader == true" class="preloader flex-column justify-content-center align-items-center">
			<img src="./src/img/Logo.svg" alt="Proisa">
			<i class="fas fa-5x fa-sync-alt fa-spin text-primary"></i>
		</div>

		<div class="Header">
			<i id="Retornar" @click="Regresar" class="btn fas fa-arrow-alt-circle-left"></i>
			<h2 id="Title">{{Article.Name}}</h2>
		</div>


		<div class="Content">
			<div v-show="Cocina == true" class="TypeCook">
				<span>Estatus del plato:</span>
				<select id="Estatus" v-model="TypeCook">
					<option value="E">Entrada</option>
					<option value="F">Plato Fuerte</option>
				</select>
			</div>

			<div class="bulgy-radios">

				<div>
					<span class="subtitle">Ord. en Espera:</span>
					<label class="Option">
				
						<input name="guarnicion" v-model="Waiting"  type="checkbox"></input>
						<span class="radio"></span>
						<span class="label"></span>
					</label>
				</div>
			</div>

		</div>



		<div class="nota">
			<span class="Title">Nota</span>
			<textarea id="Nota" rows="4" cols="55" maxlength="280" v-model="Note" :disabled="Article.ar_select == 'S'" placeholder="Inserte aqui la Nota"></textarea>
		</div>

		<div class="Posicion">
			<span>Posicion en Mesa:</span>
			<select id="Posicion" v-model="Position">
				<option v-for="index in 31" :key="index" v-bind:value="index-1">{{index-1}}</option>
			</select>
		</div>

		<div class="Details">
			<span class="Title">Guarniciones</span>


			<div v-show="isGroup == true" class="Grupos List">
				<div v-if="Object.keys(Group1).length !== 0" id="Grupo" class="bulgy-radios">
					<label v-bind:style="isGroup1 ? {'color': 'black'} : {'color': 'red'}" class="Sub-Grupo">{{Group1[0].SubName}}</label>
					<div>
						<label v-for="index in Group1" :key="index" class="Option">
							<input v-bind:value="index.Name" v-bind:name="index.SubName" v-model="Groups[0]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>

						<label class="Option">
							<input value="" v-bind:name="Group1[0].SubName" v-model="Groups[0]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">Ninguno</span>
						</label>
					</div>
				</div>

				<div v-if="Object.keys(Group2).length !== 0" id="Grupo" class="bulgy-radios">
					<label v-bind:style="isGroup2 ? {'color': 'black'} : {'color': 'red'}" class="Sub-Grupo">{{Group2[0].SubName}}</label>
					<div>
						<label v-for="index in Group2" :key="index" class="Option">
							<input v-bind:value="index.Name" v-bind:name="index.SubName" v-model="Groups[1]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>

						<label class="Option">
							<input value="" v-bind:name="Group2[0].SubName" v-model="Groups[1]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">Ninguno</span>
						</label>
					</div>
				</div>

				<div v-if="Object.keys(Group3).length !== 0" id="Grupo" class="bulgy-radios">
					<label v-bind:style="isGroup3 ? {'color': 'black'} : {'color': 'red'}" class="Sub-Grupo">{{Group3[0].SubName}}</label>
					<div>
						<label v-for="index in Group3" :key="index" class="Option">
							<input v-bind:value="index.Name" v-bind:name="index.SubName" v-model="Groups[2]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>

						<label class="Option">
							<input value="" v-bind:name="Group3[0].SubName" v-model="Groups[2]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">Ninguno</span>
						</label>
					</div>
				</div>

				<div v-if="Object.keys(Group4).length !== 0" id="Grupo" class="bulgy-radios">
					<label v-bind:style="isGroup4 ? {'color': 'black'} : {'color': 'red'}" class="Sub-Grupo">{{Group4[0].SubName}}</label>
					<div>
						<label v-for="index in Group4" :key="index" class="Option">
							<input v-bind:value="index.Name" v-bind:name="index.SubName" v-model="Groups[3]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>

						<label class="Option">
							<input value="" v-bind:name="Group4[0].SubName" v-model="Groups[3]" type="radio"></input>
							<span class="radio"></span>
							<span class="label">Ninguno</span>
						</label>
					</div>
				</div>
				<!-- radio -->

				<!-- checkbox -->

				<div v-if="Object.keys(Group5).length !== 0" id="Grupo" class="bulgy-radios">
					<label v-bind:style="isGroup5 ? {'color': 'black'} : {'color': 'red'}" class="Sub-Grupo">{{Group5[0].SubName}}</label>
					<div>
						<label v-for="(item, index ) in Group5" :key="index" class="Option">
							<input v-bind:value="item.Name" :checked="isChecked(item.Name)" @change="toggleCheckBox(item.Name)" name="MultiplesOptions" type="checkbox"></input>
							<span class="radio"></span>
							<span class="label">{{item.Name}}</span>
						</label>

						<label class="Option">
							<input :checked="isChecked(' ')" @change="toggleCheckBox(' ')" name="MultiplesOptions" type="checkbox"></input>
							<span class="radio"></span>
							<span class="label">Ninguno</span>
						</label>
					</div>
				</div>


			</div>

			<div v-show="isGroup != true" class="Grupos List">

				<div id="Blends" v-if="Object.keys(Blend).length !== 0" class="bulgy-radios">
					<label v-bind:style="isBlend ? {'color': 'black'} : {'color': 'red'}" class="Sub-Grupo" for="">Seleccion Combinada : {{ parseInt(Article.Seleccion)}}</label>

					<div>
						<label v-for="index in Blend" :key="index" class="Option">
							<input v-bind:value="index.Code2" :checked="isCheckedMix(index.Code2)" @change="toggleMix(index.Code2)" name="Blend" v-bind:type="parseInt(this.Article.Seleccion) < 2 ? 'radio' : 'checkbox'"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>
					</div>
				</div>




				<!-- Guarnicion -->
				<div v-if="Object.keys(Guarni).length !== 0" class="bulgy-radios">
					<label class="Sub-Grupo" for="">Guarniciones</label>

					<div>
						<label v-for="index in Guarni" :key="index" class="Option">
							<input v-bind:value="index.Name" name="guarnicion" v-model="Fittings" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>

						<label class="Option">
							<input value="CON " name="guarnicion" v-model="Fittings" type="radio"></input>
							<span class="radio"></span>
							<span class="label">SIN GUARNICION</span>
						</label>


						<label class="Option">
							<input name="guarnicion" type="checkbox"></input>
							<span class="radio"></span>
							<span class="label">Esperar</span>
						</label>
					</div>
				</div>

				<div v-if="Object.keys(Ingre).length !== 0" class="bulgy-radios">
					<label class="Sub-Grupo">Ingredientes</label>
					<div>
						<label v-for="index in Ingre" :key="index" class="Option">
							<input v-bind:value="index.Name" :checked="isCheckedIngre(index.Name)" @change="toggleIngre(index.Name)" name="Ingredientes" type="checkbox"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>
					</div>
				</div>

				<div v-if="Object.keys(Termi).length !== 0" class="bulgy-radios">
					<label class="Sub-Grupo">Termino</label>
					<div>
						<label v-for="index in Termi" :key="index" class="Option">
							<input v-bind:value="index.Name" name="Termino" v-model="Term" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index.Name}}</span>
						</label>
					</div>
				</div>

				<div class="bulgy-radios">
					<label class="Sub-Grupo" for="">Tipo de Entrtega</label>
					<div>
						<label v-for="index in ['Para Llevar', 'Delivery', 'Consumo en local']" :key="index" class="Option">
							<input v-bind:value="index" name="Entrega" v-model="Delivery" :checked="index == 'Consumo en local'" type="radio"></input>
							<span class="radio"></span>
							<span class="label">{{index}}</span>
						</label>
					</div>
				</div>

			</div>

		</div>

		<div class="Price">
			<span> Precio:
				<span id="Precio">{{Price}}</span>
			</span>
		</div>

		<div class="Cantidad">
			<div>
				<span>Cantidad: </span>
				<div class="input-group Cant">
					<input v-model="Count" id="Art-Cantidad" :readonly="parseInt(Article?.Decimales) !== 1" @keypress="onlyNumbers" @focusout="countNull" class="form-control form-control-sidebar" type="number">
					<div class="input-group-append">
						<button id="Menos" class="btn btn-danger" :disabled="Article.ar_select === 'S'" @click="Subtraction">
							<i class="fas fa-minus fa-fw" aria-hidden="true"></i>
						</button>
						<button id="Mas" class="btn btn-success" :disabled="Article.ar_select == 'S'" @click="Addition">
							<i class="fas fa-plus fa-fw" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="Total">
			<span id="Total">{{Totals()}}</span>
		</div>

		<div class="Buttons">
			<!-- <button id="Retornar">Menú</button> -->
			<button id="Agregar" :disabled="AddArticle" @click="AddShoping">Agregar Al Pedido</button>
		</div>
	</main>
</body>
<script src="./Recursos/vue.global.js"></script>
<script src="./Recursos/sweetalert2.all.min.js"></script>
<script src="./src/js/Default.js"></script>
<script src="./src/js/Article.js"></script>

</html>
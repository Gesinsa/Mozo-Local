let Article, Blend, Grupos, Guarni, Ingre, Termi = [];
let Code = "";
const Name = document.querySelector("#Mozo");
const Mesa = document.querySelector("#Mesa");
const Cantidad = document.querySelector("#Cantidad");


const Carrito = document.querySelector("#Carrito");



Carrito.addEventListener("click", () => {
	window.location.href = "Cart";
});


(async () => {
	let Mozo = await Fetch("Mozo");
	Name.textContent = Mozo.Nombre;

	let mesa = await Fetch("Depen");
	Mesa.textContent = "Mesa: " + mesa.Mesa;

	Cantidad.textContent = await ShoppinCant();

	const { createApp } = Vue
	const app = createApp({
		data() {
			return {
				loader: true,
				Cocina: false,
				ValidarExistencia: false,
				Article: [],
				Termi: [],
				isGroup: false,
				Group1: [],
				Group2: [],
				Group3: [],
				Group4: [],
				Group5: [],

				isGroup1: true,
				isGroup2: true,
				isGroup3: true,
				isGroup4: true,
				isGroup5: true,
				isBlend: true,
				Blend: [],
				Guarni: [],
				Ingre: [],
				Price: 0.00,
				Code: '',
				Count: 1,
				Groups: [null, null, null, null],
				Ingredients: [],
				Mix: [],
				Term: '',
				Fittings: '',
				Note: '',
				Delivery: '',
				Position: 0,
				TypeCook: '',
				Waiting: false,
				AddArticle: false
			};
		},
		methods: {

			async GetAticle() {

				[this.Article, this.Group5, this.Blend, this.Guarni, this.Ingre, this.Termi] = await Fetch("Articulo");

				this.Cocina = this.Article?.Cocina ?? false;
				this.Code = this.Article.Code;
				this.TypeCook = this.Article?.TypeCook
				this.Price = this.Format(this.Article?.ar_predet ?? 0.00);
				this.ValidarExistencia = (this.Article?.AR_VALEXI ?? 0) != 0

				if (Object.keys(this.Group5).length != 0) {
					this.isGroup = true
					this.Group1 = this.Group5.filter((x) => x.Grupo == 1);
					this.Group2 = this.Group5.filter((x) => x.Grupo == 2);
					this.Group3 = this.Group5.filter((x) => x.Grupo == 3);
					this.Group4 = this.Group5.filter((x) => x.Grupo == 4);
					this.Group5 = this.Group5.filter((x) => x.Grupo == 5);
				}

				this.loader  = false
				if (this.Article?.ar_select.toUpperCase() === 'S') {
					let Note = document.querySelector("textarea#Nota");
					Note.disabled = true;
					Mas.disabled = true;
					Menos.disabled = true;

					Swal.fire({
						title: "El Articulo Esta Deshabilitado",
						text: "No disponible para la Venta",
						icon: 'warning',
						showCancelButton: false,
					});

				}
			},
			async Existence(){
				let Data = new FormData();
				Data.append("Code", this.Code);
				return await Fetch("Existence", Data);
			},

			Totals() {
				let total = 0;
				let element = document.querySelector('#Blends');

				let Price = parseFloat(this.Article.ar_predet);
				const Seleccion = parseInt(this.Article.Seleccion)
				if (typeof element !== "undefined" && element !== null) {

					let Blends = Array.from(document.querySelectorAll('#Blends input[type="checkbox"]:checked'));

					Blends.forEach((A, i) => {
						if (i == 0) {
							Price = 0;
						}

						let Art = this.Blend.filter((B) => A.value == B.Code2)[0];
						Price += parseFloat(Art.AR_PREDET / Seleccion);
					});

					this.Price = this.Format(Price);
				}

				if (this.Article.OFERTA != "N") {
					if (this.Article.Tipo == "Desc") {
						total = Price - (Price * (PV_DESC / 100));
					}

					if (this.Article.Tipo == "2x1") {
						total = Offerta(this.Count) * Price;
					}

					if (this.Article.Tipo == "CxP") {
						total = Offerta(this.Count, this.Article.CANTID, this.Article.PV_PRECIO) * Price;
					}

				} else {
					total = Price * this.Count;
				}

				return 'RD$ ' + this.Format(total);
			},

			isChecked(value) {
				return this.Groups.includes(value);
			},
			toggleCheckBox(value) {

				if (this.Groups.includes(value)) {
					this.Groups = this.Groups.filter((A) => A != value);
				} else {

					if (value == ' ') {
						this.Groups.splice(4, Object.keys(this.Groups).length - 4);
					} else {
						this.Groups = this.Groups.filter((A) => A != ' ');
					}

					this.Groups.push(value)
				}
			},

			isCheckedIngre(value) {
				return this.Ingredients.includes(value);
			},
			toggleIngre(value) {
				if (this.Ingredients.includes(value)) {
					this.Ingredients = this.Ingredients.filter((A) => A != value);
				} else {
					this.Ingredients.push(value)
				}
			},


			isCheckedMix(value) {
				return this.Mix.includes(value);
			},
			toggleMix(value) {
				if (this.Mix.includes(value)) {
					this.Mix = this.Mix.filter((A) => A != value);
				} else {
					this.Mix.push(value)
				}

				this.isBlend = true;
			},

			async Regresar() {
				await Fetch("Article", "");
				window.location.href = "Menu";
			},

			Addition() {
				this.Count++;
				this.Totals()
			},
			Subtraction() {
				if (this.Count != 1) {
					this.Count--;
					this.Totals()
				}

			},

			async AddShoping() {
				if (this.Article?.ar_select.toUpperCase() === 'S') {
					Swal.fire({
						title: "El Articulo Esta Deshabilitado",
						text: "No disponible para la Venta",
						icon: 'warning',
						showCancelButton: false,
					});
					return 0;
				}

				/* Validar Existencia */
				if(this.ValidarExistencia == true){
					const Article = await	this.Existence();
					const Count = (Article?.Existence ?? 0);


					if(Count <= 0){
						Swal.fire({
							title: "Validacion de Existencia",
							text: "El Articulo no Tiene Existencia",
							icon: 'warning',
							showCancelButton: false,
						});
						return 0;
					}

					if(Count < this.Count){
						Swal.fire({
							title: "Validacion de Existencia",
							text: `El Articulo Tiene una Existenicia de ${Count}, La Cantidad Vendida deber ser Menor`,
							icon: 'warning',
							showCancelButton: false,
						});
						return 0;
					}
				}

				this.AddArticle = true

				let element = document.querySelector('#Blends');
				let Mix2 = [];
				let Groups2 = [];

				if (typeof element !== "undefined" && element !== null) {

					const Selection = parseInt(this.Article.Seleccion);

	
					if (Object.keys(this.Mix).length !== Selection) {
						this.isBlend = false;
						Swal.fire(`Debe selecionar ${Selection} opciones`);
						let List = document.querySelector(".Grupos.List");
						List.scrollTop = (element.offsetTop - List.offsetTop);
						return 0;
					}

					Mix2 = this.Mix.flatMap(A => { if (A == null || A === undefined || A.trim() == "") { return [] } return A.trim() })
				}

				element = document.querySelector("#Grupo");


				if (typeof element !== "undefined" && element !== null) {
					let Grupo = Array.from(document.querySelectorAll("#Grupo input:checked"));
					let Grups = Array.from(document.querySelectorAll("#Grupo"));

					if (Object.keys(Grupo).length < 5 && Object.keys(Grupo).length < Object.keys(Grups).length) {
						//let Grups = Array.from(document.querySelectorAll("#Grupo"));
						let List = [];

						Grups.map((A, i) => {
							let Checkboxs = A.querySelectorAll("input:checked");

							if (Object.keys(Checkboxs).length === 0) {

								if (Object.keys(List).length === 0) {
									let List = document.querySelector(".Grupos.List");

									List.scrollTop = (A.offsetTop - List.offsetTop);
								}

								let Text = A.querySelector(".Sub-Grupo");

								this[`isGroup${i + 1}`] = false
								List.push(Text.textContent.trim());
							} else {
								this[`isGroup${i + 1}`] = true
							}

						});


						List = new Intl.ListFormat('es-ES', { style: 'long', type: 'conjunction' }).format(List)
						Swal.fire("Debe de seleccionar " + List);
						this.AddArticle = false;
						return 0;
					}

					Groups2 = this.Groups.flatMap(A => { if (A == null || A === undefined || A.trim() == "" ) { return [] } return A.trim() })
				}
	
				let Data = new FormData();
				Data.append("Code", this.Code);
				Data.append("Count", parseFloat(this.Count));
				Data.append("Group", JSON.stringify(Groups2));
				Data.append("Ingre", JSON.stringify(this.Ingredients));
				Data.append("Blend", JSON.stringify(Mix2));
				Data.append("Term", this.Term);
				Data.append("Guarn", this.Fittings);
				Data.append("Note", this.Note);
				Data.append("Delivery", this.Delivery);
				Data.append("Posicion", this.Position || 0);
				Data.append("TypeCook", this.TypeCook)
				Data.append("Espera",  this.Waiting);

				let data = await Fetch("ADDShopping", Data);

				if (data.status === true) {
					window.location.href = "Menu";
				} else {
					this.AddArticle = false;
					Swal.fire(data.Details)
				}
			},

			onlyNumbers($event) {
				let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
				if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
					$event.preventDefault();
				}
			},
			countNull() {
				if (this.Count == 0) {
					this.Count = 1;
					this.Totals()
				}
			},
			Format(value) {
				let Num = new Intl.NumberFormat("en-IN", {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2,
				}).format(value);

				return Num;
			}

		},
		mounted() {
			this.GetAticle()
			this.Totals()
		}
	});


	app.mount('#App_Article')
})();

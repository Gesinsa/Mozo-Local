let Name = document.querySelector("#Mozo");
let Cerar = document.querySelector("#Cerrar");
let Pagar = document.querySelector("#Pagar");
let Pedido = document.querySelector("#Pedido");
let lNombre = document.querySelector("#Nombre");
let Nombre = document.querySelector("#Nombre input");
let Mesa = document.querySelector("#Mesa");
let Hora = document.querySelector("#Hora");
let Fecha = document.querySelector("#Fecha");
let Bruto = document.querySelector("#Bruto");
let Itbis = document.querySelector("#Itbis");
let Ley = document.querySelector("#Ley");
let Total = document.querySelector("#Total");


let Pedidos = [];
let Details = [];


let List = document.querySelector("#List-leaves");

Cerar.addEventListener("click", () => {
	window.location.href = "Menu";
});

Pagar.addEventListener("click", async () => {
	Pagar.disabled = true;
	let Data = await Fetch("Shopping");

	if (Object.keys(Data).length == 0) {
		Swal.fire("El Carrito No Cuenta Con Ningun Articulo Nuevo");
		Pagar.disabled = false;
		return 0;
	}

	let Datos = new FormData();
	Datos.append("Name", Nombre.value);

	let data = await Fetch("Pagar", Datos);

	console.table(data)
	if (data.status) {
		window.location.href = "Mesa";
		return 0;
	}


	Swal.fire({
		title: "Error Al insertar Pedido",
		text: data.Error,
		icon: 'warning',
		showCancelButton: false,
	});

	setTimeout(() => {
		Pagar.disabled = false;
		//window.location.reload()
	}, 1500);

});


let Totals = async () => {

	let NBruto = 0;
	let NItbis = 0;
	let NLey = 0;
	let NTotal = 0;

	let Data = await Fetch("Totals");
	let Pedidos2 = [];
	let Details2 = [];

	[Pedidos2, Details2] = await Fetch("Pedido");



	if (Object.keys(Data).length !== 0) {
		NBruto += Data.Total;
		NItbis += Data.Itbis;
		NLey += Data.Ley;
		NTotal += ((Data.Total - Data.Discount) + Data.Itbis) + Data.Ley;
	}


	if (typeof Pedidos2 === "undefined" || Pedidos2 === null) {
		Pedidos2 = [];
	}

	Pedidos2 = Pedidos2 ?? [];

	if (Object.keys(Pedidos2).length !== 0) {
		NBruto += parseFloat(Pedidos2.Monto);
		NItbis += parseFloat(Pedidos2.Itbis);
		NLey += parseFloat(Pedidos2.Ley);
		NTotal += parseFloat(Pedidos2.Neto);
	}


	Bruto.textContent = Format(NBruto);
	Itbis.textContent = Format(NItbis);
	Ley.textContent = Format(NLey);
	Total.textContent = Format(NTotal);
}

let Factura = () => {
	let Fragment = document.createDocumentFragment();
	List.innerHTML = "";

	let Article = "";
	let Detail = "";
	let List2 = "";
	let div1 = "";
	let num = 0;
	let num2 = 0;
	

	Details.forEach((A) => {
		if (parseFloat(A.Price) > 0) {
			Article = document.createElement("div");
			let header = document.createElement("div");
			let Item = document.createElement("div");
			let Count = document.createElement("h6");
			let Name = document.createElement("h6");
			let Price = document.createElement("h5");

			Count.textContent = Format(A.Count);
			Name.textContent = A.Name;
			Price.textContent = Format(A.Price);

			header.setAttribute("class", "Headers");
			Count.setAttribute("class", "Count");
			Item.setAttribute("class", "Items");

			if(parseInt(A?.Espera || 0) === 1) {
				Count.setAttribute("class", "Count Waiting")
			}

			if (parseFloat(A.Count) <= 0) {
				Article.setAttribute("class", "Article Old_Delete");
			}
			else {
				Article.setAttribute("class", "Article Old");
			}



			Item.appendChild(Count);
			Item.appendChild(Name);
			header.appendChild(Item);
			header.appendChild(Price);
			Article.appendChild(header);
			Detail = "";
			List2 = "";
		} else {
			if (List2 == "") {
				num2 = 1;
				num = 0;
				div1 = document.createElement("div");
				Detail = document.createElement("div");
				Detail.setAttribute("class", "Details");
				List2 = document.createElement("ul");
			}

			let Count = document.createElement("h6");
			let Li = document.createElement("span");
			Li.textContent = A.Name;
			Count.textContent = "";
			Count.setAttribute("class", "Count");

			if (num < 2) {
				if (A.Name.length >= 30) {
					Detail.appendChild(Count);

					div1 = document.createElement("div");
					div1.setAttribute("class", "one");
					num2 += 1;
				} else {
					if (num2) {
						Detail.appendChild(Count);
					}
				}

				div1.appendChild(Li);
				num2 = 0;
				num += 1;
			} else {
				num = 0;
				Detail.appendChild(Count);
				div1 = document.createElement("div");
				div1.appendChild(Li);
			}

			//List2.appendChild(Li);
			Detail.appendChild(div1);
			Article.appendChild(Detail);
		}

		Fragment.appendChild(Article);
	});
	List.appendChild(Fragment);
};

let NewPedido = (Data) => {
	let Fragment = document.createDocumentFragment();
	console.table(Data)

	Data.forEach((A) => {

		let Article = document.createElement("div");
		let header = document.createElement("div");
		let Item = document.createElement("div");
		let Count = document.createElement("h6");
		let Name = document.createElement("h6");
		let div = document.createElement("div");
		let Price = document.createElement("h5");
		let icon = document.createElement("i");
		let div2 = document.createElement("div");

		Count.textContent = Format(A.Count);
		Name.textContent = A.Name;
		Price.textContent = Format(A.Price);

		header.setAttribute("class", "Headers");
		Count.setAttribute("class", "Count");
		Item.setAttribute("class", "Items");
		Price.setAttribute("class", "Price");


		if /* The above code is checking if the value of `A?.Espera` is truthy (either `true` or a non-zero
		value). If it is truthy, the expression will evaluate to `true`. If it is falsy (either `false`
		or `0`), the expression will evaluate to `false`. */
		(Boolean(A?.Espera || 0) === true) {
			Count.setAttribute("class", "Count Waiting")
		}

		Article.setAttribute("class", "Article");

		div2.setAttribute("id", "Delete-Item");
		icon.setAttribute("class", "fas fa-times-circle");

		Item.appendChild(Count);
		Item.appendChild(Name);
		div.setAttribute("class", "Price");

		div.appendChild(Price);
		div2.appendChild(icon);
		div.appendChild(div2);

		header.appendChild(Item);
		header.appendChild(div);
		Article.appendChild(header);



		if (A.Note !== "") {
			let div1 = document.createElement("div");
			let Detail = document.createElement("div");
			let List2 = document.createElement("ul");
			let Count = document.createElement("h6");
			let Li = document.createElement("span");
			Li.textContent = A.Note;
			Count.textContent = "";

			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");
			div1.setAttribute("class", "one");

			div1.appendChild(Li);
			Detail.appendChild(Count);
			Detail.appendChild(div1);
			Article.appendChild(Detail);
		}

		let Opt = [];

		if (A.Delivery !== "") {
			Opt.push(A.Delivery)
		}

		if (A.Guarn !== "") {
			Opt.push(A.Guarn)
		}

		if (A.Term !== "") {
			Opt.push(A.Term)
		}

		if (parseInt(A.Posicion) !== 0) {
			let div1 = document.createElement("div");
			let Detail = document.createElement("div");
			let Count = document.createElement("h6");

			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");

			let Li = document.createElement("span");
			Li.textContent = `Posicion: ${A.Posicion}`;
			Count.textContent = "";
			div1.appendChild(Li);


			Detail.appendChild(Count);
			Detail.appendChild(div1);
			Article.appendChild(Detail);

			div1 = document.createElement("div");
			Detail = document.createElement("div");
			Count = document.createElement("h6");
			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");

		}


		if (Object.keys(Opt).length !== 0) {

			let cant = Object.keys(Opt).length - 1;
			let div1 = document.createElement("div");
			let Detail = document.createElement("div");
			let Count = document.createElement("h6");

			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");
			let num = 0;

			Opt.forEach((B, i) => {

				if (num == 2) {

					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);

					div1 = document.createElement("div");
					Detail = document.createElement("div");
					Count = document.createElement("h6");
					Detail.setAttribute("class", "Details");
					Count.setAttribute("class", "Count");
				}

				let Li = document.createElement("span");
				Li.textContent = B;
				Count.textContent = "";
				div1.appendChild(Li);

				if (cant == i) {
					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);
				}

				num++;
			});

		}

		if (Object.keys(A.Group).length !== 0) {

			let cant = Object.keys(A.Group).length - 1;

			let div1 = document.createElement("div");
			let Detail = document.createElement("div");
			let Count = document.createElement("h6");

			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");
			let num = 0;

			A.Group.forEach((B, i) => {

				if (num == 2) {

					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);

					div1 = document.createElement("div");
					Detail = document.createElement("div");
					Count = document.createElement("h6");
					Detail.setAttribute("class", "Details");
					Count.setAttribute("class", "Count");
				}

				let Li = document.createElement("span");
				Li.textContent = B;
				Count.textContent = "";
				div1.appendChild(Li);

				if (cant == i) {
					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);
				}

				num++;
			});
		}

		if (Object.keys(A.Ingre).length !== 0) {
			let cant = Object.keys(A.Ingre).length - 1;

			let div1 = document.createElement("div");
			let Detail = document.createElement("div");
			let Count = document.createElement("h6");

			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");
			let num = 0;

			A.Ingre.forEach((B, i) => {


				if (num == 2) {

					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);

					div1 = document.createElement("div");
					Count = document.createElement("h6");
					Count.setAttribute("class", "Count");
				}

				let Li = document.createElement("span");
				Li.textContent = B;
				Count.textContent = "";
				div1.appendChild(Li);

				if (cant == i) {
					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);
				}

				num++;
			});
		}

		if (Object.keys(A.Blend).length !== 0) {
			let cant = Object.keys(A.Blend).length - 1;

			let div1 = document.createElement("div");
			let Detail = document.createElement("div");
			let Count = document.createElement("h6");

			Detail.setAttribute("class", "Details");
			Count.setAttribute("class", "Count");
			let num = 0;

			A.Blend.forEach((B, i) => {


				if (num == 2) {

					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);

					div1 = document.createElement("div");
					Count = document.createElement("h6");
					Count.setAttribute("class", "Count");
				}

				let Li = document.createElement("span");
				Li.textContent = B.Name;
				Count.textContent = "";
				div1.appendChild(Li);

				if (cant == i) {
					Detail.appendChild(Count);
					Detail.appendChild(div1);
					Article.appendChild(Detail);
				}

				num++;
			});
		}

		icon.addEventListener("click", async () => {

			let Datos = new FormData();
			Datos.append("Code", A.Code);
			Datos.append("Count", -1);
			Datos.append("Group", JSON.stringify(A.Group));
			Datos.append("Term", A.Term);
			Datos.append("Ingre", JSON.stringify(A.Ingre));
			Datos.append("Guarn", A.Guarn);
			Datos.append("Blend", JSON.stringify(A.Blend));
			Datos.append("Delivery", A.Delivery);
			Datos.append("Posicion", A.Posicion);
			Datos.append("Note", A.Note);
			Datos.append("Espera", A?.Espera || 0) /// areglo


			let { status, Details } = await Fetch("ADDShopping", Datos);


			let Detalle = Details.filter((B) => B.ID == A.ID);

			SetSession("Shopping", Details);

			if (Object.keys(Detalle).length === 0) {
				List.removeChild(Article);
				Totals();
			}
			else {
				Count.textContent = parseInt(Detalle[0].Count);
				Totals();
			}


		});


		Fragment.appendChild(Article);
	});

	List.appendChild(Fragment);
};


(async () => {
	let Mozo = await Fetch("Mozo");
	Name.textContent = Mozo.Nombre;


	let Imprimir = document.querySelector("#Imprimir");

	console.table(Mozo)
	if (Mozo.Print == 1) {
		Imprimir.addEventListener("click", async () => {
			Imprimir.disabled = true;
			let Data = await Fetch("Shopping");

			if (Object.keys(Data).length == 0) {
				Swal.fire("El Carrito No Cuenta Con Ningun Articulo Nuevo");
				Imprimir.disabled = false;
				return 0;
			}

			let Datos = new FormData();
			Datos.append("Name", Nombre.value);

			let data = await Fetch("Pagar", Datos);

			if (data.status) {
				if (isMovil()) {
					await PrintAndroid();
					window.location.href = "Mesa";
				} else {
					window.location.href = "Print";
				}

				return 0;
			} else {
				Swal.fire({
					title: "Error Al insertar Pedido",
					text: data.Error,
					icon: 'warning',
					showCancelButton: false,
				});
			}

			console.table(data)


			setTimeout(() => {
				window.location.reload()
			}, 1500);
		});
	}
	else {
		Imprimir.addEventListener("click", async () => {
			Imprimir.disabled = true;
			let Data = await Fetch("Shopping");


			if (Object.keys(Data).length == 0) {
				Swal.fire("El Carrito No Cuenta Con Ningun Articulo Nuevo");
				Imprimir.disabled = false;
				return 0;
			}


			let Datos = new FormData();
			Datos.append("Name", Nombre.value);

			let data = await Fetch("Pagar_Imprimir", Datos);

			if (data.status) {
				window.location.href = "Mesa";
				return 0;
			}

			Swal.fire({
				title: "Error Al insertar Pedido",
				text: data.Error,
				icon: 'warning',
				showCancelButton: false,
			});

			setTimeout(() => {
				window.location.reload()
			}, 1500);

		});
	}


	//* Factura  *//

	[Pedidos, Details] = await Fetch("Pedido");


	if (typeof Pedidos == "undefined" && Pedidos == null) {
		Pedidos = [];
	}

	if (Object.keys(Pedidos).length != 0) {

		Pedido.textContent = "Pedido: " + Pedidos.Factura;
		Nombre.value = Pedidos.Nombre;
		Mesa.textContent = "Mesa: " + Pedidos.Mesa;
		Hora.textContent = "Hora Entrada: " + Pedidos.Hora;
		Fecha.textContent = Pedidos.Fecha;
		Details = Details.filter((X) => X.Code != "");
	} else {

		let Datos = await Fetch("Depen");
		Mesa.textContent = "Mesa: " + Datos.Mesa;
		Hora.textContent = "Hora Entrada: " + moment().format('h:mm a');
		Fecha.textContent = moment().format('DD/MM/YYYY');
	}

	Factura();

	let Data = await Fetch("Shopping");
	NewPedido(Data);

	Totals();
	let list = document.querySelector("#List-leaves");
	let top = list.scrollHeight;
	list.scrollTop = top;

})();
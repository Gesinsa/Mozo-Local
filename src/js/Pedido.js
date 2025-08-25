let Name = document.querySelector("#Mozo");
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
let Retornar = document.querySelector("#Retornar");


let List = document.querySelector("#List-leaves");


let Dividir = document.querySelector("#Dividir");
let Waiting = document.querySelector("#Nota #Waiting");
let Resumir = document.querySelector("#Resumir");
let Nota = document.querySelector(".status-bar #Note");
let NotaPrint = document.querySelector("#Nota #Print");
let NotaCancel = document.querySelector("#Nota #Cancel");

let Pedidos = [];
let Details = [];




Resumir.addEventListener("click", async () => {
	let Resumen = await Fetch("Resumir");

	Swal.fire({
		html: `<div id="print" class="print">
          <div class="Header">
            <H1 class="Titulo">Resumen de Cuenta</H1>
          </div>  
			 <div class="letrero">
					<span>Cantidad</span>
					<span>Descripcion</span>
					<span>Precio</span>
			 </div>
          <div id="Body" class="List-Pedido">
          </div>
			 <hr class='linea'>
      </div>
		`,
		showCancelButton: true,
		showConfirmButton: false,
		cancelButtonText: "Retornar",
		allowOutsideClick: false,
		allowEnterKey: false,
		allowEscapeKey: false,
	});

	let btn = document.querySelector(".swal2-styled.swal2-cancel");
	btn.setAttribute("style", "background-color: #ffc107 !important;")

	let Body = document.querySelector("#Body");
	Body.innerHTML = "";
	let Fragmant = document.createDocumentFragment();

	Resumen = Resumen.filter((A) => {
		return parseInt(A.Cantidad) > 0;
	});

	Resumen.forEach((A) => {
		let div1 = document.createElement("div");
		let div2 = document.createElement("div");
		let strong1 = document.createElement("strong");
		let span1 = document.createElement("div");
		let span2 = document.createElement("span");
		let div3 = document.createElement("div");
		let strong2 = document.createElement("strong");

		span1.textContent = parseInt(A.Cantidad);
		span2.textContent = A.Descrip;
		strong2.textContent =

			div1.setAttribute("class", "d-flex justify-content-between");
		div1.setAttribute("style", "margin:5px 0px;");
		span1.setAttribute(
			"style",
			"margin-right:10px;width: 70px;display: inline-block;",
		);
		span2.setAttribute("style", "font-size:13px;");

		strong1.appendChild(span1);
		strong1.appendChild(span2);
		div2.appendChild(strong1);
		div3.appendChild(strong2);
		div1.appendChild(div2);
		div1.appendChild(div3);
		Fragmant.appendChild(div1);
	});

	Body.appendChild(Fragmant);

});


Dividir.addEventListener("click", () => {
	let Data = Fetch("Dividir");
	localStorage.setItem("Datos", JSON.stringify(Data));
	window.location.href = "Menu";
});


Nota.addEventListener("click", async () => {
	let Dialog = document.querySelector("dialog#Nota");
	Dialog.showModal()
});


Waiting.addEventListener("click", async () => {
	let Dialog = document.querySelector("dialog#Nota");
	Dialog.close();
	let Data = await Fetch("PrintWaiting");

	if (Data.status == true) {
		Swal.fire("Imprimiendo Pedidos en Espera")
		const Data = Array.from(document.querySelectorAll(".Waiting"))
		Data.map((a)=>a.classList.remove("Waiting"))
	} else {
		Swal.fire(Data.Details)
	}
});


NotaPrint.addEventListener("click", async () => {
	const Notas = Array.from(document.querySelectorAll("#Nota .Nota_Body textarea"));
	let Notes = [];

	Notes = Notas.flatMap((A) => {
		if (A.value.trim() !== "") {
			return A.value.trim();
		}
		return [];
	});

	let Data = new FormData();
	Data.append("Note", JSON.stringify(Notes));

	let msg = await Fetch("Note", Data);
	console.table(msg)

	let Dialog = document.querySelector("dialog#Nota");
	Dialog.close();

	Swal.fire("La Nota Se envio a Imprimir");
});


NotaCancel.addEventListener("click", async () => {
	let Dialog = document.querySelector("dialog#Nota");
	Dialog.close();
});



Nombre.addEventListener("change", async () => {
	let Datos = new FormData();
	Datos.append("Name", Nombre.value);
	let data = await Fetch("Name", Datos);

	if (!data.status) {
		location.reload()
	}


});


let Factura = async (NMesa) => {
	let Fragment = document.createDocumentFragment();
	List.innerHTML = "";

	let Article = "";
	let Detail = "";
	let List2 = "";
	let div1 = "";
	let num = 0;
	let num2 = 0;
	let Mozo = await Fetch("Mozo");

	Details.forEach((A) => {
		if (parseFloat(A.Price) > 0) {
			Article = document.createElement("div");
			let header = document.createElement("div");
			let Item = document.createElement("div");
			let Count = document.createElement("h6");
			let Name = document.createElement("h6");
			let Price = document.createElement("h5");
			let Add = document.createElement("i");
			let Div = document.createElement("div");


			Count.textContent = Format(A.Count);
			Name.textContent = A.Name;
			Price.textContent = Format(A.Price);

			header.setAttribute("class", "Headers");
			Count.setAttribute("class", "Count");

			if (parseInt(A?.Espera || 0) === 1) {
				Count.setAttribute("class", "Count Waiting")
			}
			
			Item.setAttribute("class", "Items");
			Add.setAttribute("class", "fas fa-plus-circle");
			Div.setAttribute("id", "Add-Item");

			if (parseFloat(A.Count) < 1) {
				Article.setAttribute("class", "Article Delete");
			}
			else {
				Article.setAttribute("class", "Article");
			}

			Div.addEventListener("click", async () => {
				let Data = new FormData();
				Data.append("Mesa", NMesa);
				let Mesa = await Fetch("MesaStatus", Data);

				let Codigo = Mesa.Codigo;

				if (Mesa.Ocupa == "*") {
					Swal.fire("Numero de Mesa esta Siendo Utilizada por Camarero");
					return 0;
				}
				else {

					if (Codigo != Mozo.Codigo && Codigo != "") {
						if (Mozo.Acceso != 1) {
							Swal.fire("Esta Mesa pertenece a otro Camarero");
							return 0;
						}
					}
				}

				localStorage.setItem("Datos", JSON.stringify({ Mesa: NMesa, Depen: '' }));

				let Data2 = new FormData();
				Data2.append("Article", A.Code);
				Fetch("Article", Data2);
				window.location.href = "Article";
			});

			Div.appendChild(Add);
			Item.appendChild(Count);
			Item.appendChild(Name);
			header.appendChild(Item);
			header.appendChild(Price);
			header.appendChild(Div);
			Article.appendChild(header);

			if (parseInt(A.Posicion) !== 0) {

				div1 = document.createElement("div");
				Detail = document.createElement("div");
				Detail.setAttribute("class", "Details");
				div1.setAttribute("class", "one");

				let Count = document.createElement("h6");
				let Li = document.createElement("span");
				Li.textContent = `Posicion: ${A.Posicion}`;
				Count.textContent = "";
				Count.setAttribute("class", "Count");


				div1.appendChild(Li);
				Detail.appendChild(Count);
				Detail.appendChild(div1);
				Article.appendChild(Detail);

			}


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

(async () => {
	let Shopping = sessionStorage.getItem("Shopping");

	if (typeof Shopping === "undefined" || Shopping == null) {
		Shopping = [];
	}
	else {
		Shopping = JSON.parse(Shopping);
	}


	if (Object.keys(Shopping).length != 0) {
		window.location.href = "Menu";
	}

	sessionStorage.setItem("Shopping_Old", JSON.stringify([]));
	sessionStorage.setItem("Shopping", JSON.stringify([]));

	let Mozo = await Fetch("Mozo");
	Name.textContent = Mozo.Nombre;

	let Imprimir = document.querySelector("#Imprimir");

	if (Mozo.Print == 1) {

		if (isMovil()) {
			Imprimir.addEventListener("click", async () => {
				Imprimir.setAttribute("disabled", "true")
				await PrintAndroid();
				Imprimir.removeAttribute("disabled")
			});
		} else {

			Imprimir.addEventListener("click", () => {
				window.location.href = "Print";
			});
		}

	}
	else {
		Imprimir.addEventListener("click", async () => {

			let data = await Fetch("Imprimir");
			Swal.fire("La Factura Se envio Imprimir");
		});
	}

	[Pedidos, Details] = await Fetch("Pedido");

	Pedido.textContent = "Pedido: " + Pedidos.Factura;
	Nombre.value = Pedidos.Nombre;
	Mesa.textContent = "Mesa: " + Pedidos.Mesa;
	Hora.textContent = "Hora Entrada: " + Pedidos.Hora;
	Fecha.textContent = Pedidos.Fecha;

	Bruto.textContent = Format(Pedidos.Monto);
	Itbis.textContent = Format(Pedidos.Itbis);
	Ley.textContent = Format(Pedidos.Ley);
	Total.textContent = Format(Pedidos.Neto);


	/* Agregar */

	let Agregar = document.querySelector("#Agregar");

	Agregar.addEventListener("click", async () => {
		let Data = new FormData();
		Data.append("Mesa", Pedidos.Mesa);
		let Mesa = await Fetch("MesaStatus", Data);


		let Codigo = Mesa.Codigo;

		if (Mesa.Ocupa == "*") {
			//Swal.fire("La Mesa Esta Siendo Ocupada");
			Swal.fire("Numero de Mesa esta Siendo Utilizada por Camarero");
			return 0;
		}
		else {

			if (Codigo != Mozo.Codigo && Codigo != "") {
				if (Mozo.Acceso != 1) {
					Swal.fire("Esta Mesa pertenece a otro Camarero");
					return 0;
				}
			}
		}

		localStorage.setItem("Datos", JSON.stringify({ Mesa: Pedidos.Mesa, Depen: '' }));
		window.location.href = "Menu";
	});


	Factura(Pedidos.Mesa);

	let SubMesas = await Fetch("SubMesas");
	let count = Object.keys(SubMesas).length - 1;

	let Text = "Volver a Mesa";

	if (count > 0) {
		Text = "Volver a SubMesas";
	}

	Retornar.textContent = Text;

	let list = document.querySelector("#List-leaves");
	let top = list.scrollHeight;
	list.scrollTop = top;

	Retornar.addEventListener("click", async () => {
		let ruta = "Mesa";
		let Mesa = "";

		if (count > 0) {
			Mesa = SubMesas[count].Depen;
			ruta = "SubMesa";
		}

		let Data = new FormData();
		Data.append("Mesa", Mesa);
		await Fetch("Mesa", Data);

		window.location.href = ruta;
	});

})();

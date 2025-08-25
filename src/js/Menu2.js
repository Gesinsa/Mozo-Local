let Name = document.querySelector("#Mozo");
let Mesa = document.querySelector("#Mesa");
let Menu = document.querySelector("#Menu");
let Cantidad = document.querySelector("#Cantidad");
let Retornar = document.querySelector("#Retornar");
let Busqueda = document.querySelector("#Busqueda");
let BtnBusqueda = document.querySelector(".Busquda button");
let Carrito = document.querySelector("#Carrito");


let Depart = [];
let Article = [];


Carrito.addEventListener("click", () => {
	window.location.href = "Cart";
});

Busqueda.addEventListener("keydown", async (e) => {
	if (e.keyCode == "13") {
		let Text = Busqueda.value.trim();
		if (Text != "") {
			let Data = new FormData();
			Data.append("Name", Text);
			Article = await Fetch("Search", Data);

			if (Object.keys(Article).length === 0) {
				Swal.fire("No hay Resultado en la Busqueda");
				Busqueda.value = "";
				return 0;
			}
			ListArticle("Busqueda");
		}
		e.preventDefault();
	}
});

BtnBusqueda.addEventListener("click", async (e) => {
	let Text = Busqueda.value.trim();
	if (Text != "") {
		let Data = new FormData();
		Data.append("Name", Text);
		Article = await Fetch("Search", Data);

		if (Object.keys(Article).length === 0) {
			Swal.fire("No hay Resultado en la Busqueda");
			Busqueda.value = "";
			return 0;
		}
		ListArticle("Busqueda");
	}
	e.preventDefault();
});

Retornar.addEventListener("click", async () => {
	let Data = await Fetch("Shopping");

	if (Object.keys(Data).length === 0) {
		sessionStorage.setItem("Shopping_Old", JSON.stringify([]));
		sessionStorage.setItem("Shopping", JSON.stringify([]));
		window.location.href = "Mesa";
	} else {

		Swal.fire({
			title: "Tienes Articulos agregados al Carrito Que no has Validado",
			text: "¿Desea Salir Sin Confimar?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: "Sí, Eliminar",
			cancelButtonText: "Cancelar",
			confirmButtonColor: "#d33",
			cancelButtonColor: "#facea8"
		})
			.then(resultado => {
				if (resultado.value) {
					Fetch("ClearShopping");
					sessionStorage.setItem("Shopping_Old", JSON.stringify([]));
					sessionStorage.setItem("Shopping", JSON.stringify([]));
					window.location.href = "Mesa";
				}
				else {
					// Dijeron que no
				}
			});

		let btn = document.querySelector(".swal2-styled.swal2-cancel");
		btn.setAttribute("style", "background-color: #ffc107 !important;")
		document.querySelector("#swal2-content").setAttribute("style", "display: block;font-size: 22px;font-weight: 800;")
	}


});

let Departa = () => {
	let Fragment = document.createDocumentFragment();
	Menu.innerHTML = "";

	// All Departamanto
	let All = document.createElement("div");
	let text = document.createElement("span");

	text.textContent = "Todos";
	All.setAttribute("class", "Departamento btn-All");
	All.appendChild(text);

	Fragment.appendChild(All);

	All.addEventListener("click", async () => {
		let Data = new FormData();
		Data.append("Code", "All");
		Article = await Fetch("Area_Depart", Data);
		window.location.href = "Menu";
	});

	Depart.forEach((A) => {
		let div = document.createElement("div");
		let text = document.createElement("span");


		text.textContent = A.Name;
		div.setAttribute("class", "Departamento");
		div.appendChild(text);

		Fragment.appendChild(div);

		div.addEventListener("click", async () => {
			let Data = new FormData();
			Data.append("Code", A.Code);
			Article = await Fetch("Area_Depart", Data);
			window.location.href = "Menu";
		});
	});

	Menu.appendChild(Fragment);
};

let ListArticle = (Name, Type = true) => {
	Swal.fire({
		html: `<div id="List_Arti">
			<div class="Header">
			 <i id="Close" class="btn fas fa-arrow-alt-circle-left"></i>
			 <H1 class="Titulo">${Name}</H1>
			</div>  
			<div class="List-Article List">
			</div>
			<hr class='linea'>
		</div>`,
		showCancelButton: false,
		showConfirmButton: false,
		showCloseButton: true,
		cancelButtonText: "Cerrar",
		allowOutsideClick: false,
		allowEnterKey: false,
		allowEscapeKey: false,
	});

	let Arti = document.querySelector(".List-Article");
	let Fragment = document.createDocumentFragment();
	Arti.innerHTML = "";

	let Close = document.querySelector(".swal2-close");
	Close.textContent = "";
	//Close.appendChild(icon);

	let icon = document.querySelector("#Close");
	icon.addEventListener("click", () => {
		localStorage.removeItem("Departamento")
		Swal.close();
	})


	let Dep = "";
	let renglon = document.createElement("div");
	renglon.setAttribute("class", "Elements");

	let cant = Object.keys(Article).length - 1;

	Article.forEach((A, i) => {
		if (Dep != A.Depart) {

			if (Dep != "") {
				Fragment.appendChild(renglon);
			}

			let title = document.createElement("div");
			renglon = document.createElement("div");

			renglon.setAttribute("class", "Elements");
			title.setAttribute("class", "Title");
			title.textContent = A.Depart;
			Dep = A.Depart;

			if (Type) {
				Fragment.appendChild(title);
			}
		}


		let content = document.createElement("div");
		let divicion = document.createElement("div");
		let info = document.createElement("div");
		let text = document.createElement("h5");
		let nota = document.createElement("span");
		let title = document.createElement("span");
		let guarni = document.createElement("span");
		let Precio = document.createElement("h5");
		let img = document.createElement("img");
		let imag2 = document.createElement("img");

		let subtitle = document.createElement("span");


		let imagen = A.Code + ".jpg";

		img.setAttribute("src", "./src/img/" + imagen);

		imag2.setAttribute("class", "imagen2");
		imag2.setAttribute("style", "display: None");

		divicion.setAttribute("class", "Rows");
		content.setAttribute("class", "Article");
		Precio.setAttribute("class", "Precio");
		text.setAttribute("class", "Descrip");
		nota.setAttribute("class", "nota");
		title.setAttribute("class", "subtitle");

		if (A.ar_select.toUpperCase() === 'S') {
			content.setAttribute("class", "Article disable");
		}

		guarni.setAttribute("class", "guarni");

		text.textContent = A.Name;
		Precio.textContent = "$ " + Format(A.ar_predet);
		nota.textContent = A.Detalle;
		title.textContent = "";


		guarni.textContent = A.GUARNICIONES;


		if (A.GUARNICIONES != "") {
			title.textContent = "GUARNICIONES:";
		}

		info.appendChild(text);
		info.appendChild(nota);
		//info.appendChild(title);
		info.appendChild(guarni);

		divicion.appendChild(info);
		divicion.appendChild(img);

		content.appendChild(divicion);
		content.appendChild(imag2);
		content.appendChild(Precio);
		renglon.appendChild(content);


		if (cant == i) {
			Fragment.appendChild(renglon);
		}


		content.addEventListener("click", async (e) => {
			if (e.target != img) {
				let imags2 = Array.from(document.querySelectorAll("img.imagen2[style='display: Block']"));

				imags2.forEach((x) => {
					x.setAttribute("style", "display: none");
				});


				if (A.ar_select.toUpperCase() !== 'S') {
					if (Object.keys(imags2).length === 0) {
						let Data = new FormData();
						Data.append("Article", A.Code);


						let data = await Fetch("Article", Data);
						window.location.href = "Article";
					}
				} else {

					Swal.fire({
						title: "El Articulo Esta Deshabilitado",
						text: "No disponible para la Venta",
						icon: 'warning',
						showCancelButton: false,
					})
				}

			}

		});


		img.addEventListener("click", () => {
			if (img.getAttribute("src") != './src/img/blanco.jpg') {
				let imags2 = Array.from(document.querySelectorAll("img.imagen2[style='display: Block']"));

				imags2.forEach((x) => {
					x.setAttribute("style", "display: none");
				});


				imag2.setAttribute("style", "display: Block");
				imag2.setAttribute("src", img.getAttribute("src"));
			}

		});

		img.addEventListener("error", () => {
			img.onerror = null;
			img.src = './src/img/blanco.jpg';
		});
	});

	Arti.appendChild(Fragment);
}

(async () => {
	let Mozo = await Fetch("Mozo");
	Name.textContent = Mozo.Nombre;


	let mesa = await Fetch("Depen");
	Mesa.textContent = "Mesa: " + mesa.Mesa;

	Depart = await Fetch("Areas_Departs");

	window.location.hash = "no-back-button";
	window.location.hash = "Again-No-back-button" //chrome
	window.onhashchange = function () { window.location.hash = ""; }

	Cantidad.textContent = await ShoppinCant();

	Departa();

	/* Mantener Departamento Abierto */
/*
  const Config = await Fetch("Config");
	if (parseInt(Config?.DepartFijo || 0) === 1) {
		const Departamento = JSON.parse(localStorage.getItem("Departamento")) ?? [];

		if (Object.keys(Departamento).length !== 0) {

			let Data = new FormData();
			Data.append("Code", Departamento.Code);
			Article = await Fetch("Articulos", Data);

			if (Object.keys(Article).length === 0) {
				Swal.fire("El Departamanto No Tiene Artuclos Disponibles");
				Busqueda.value = "";

				localStorage.removeItem("Departamento")
				return 0;
			}


			ListArticle(Departamento.Name, false);
		}
	}*/

})();

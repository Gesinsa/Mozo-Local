let Camarero = document.querySelector("#Mozo");
let Retornar = document.querySelector("#Retornar");
let ListMesas = document.querySelector("#ListMesas");
let Dividir = document.querySelector("#Dividir");
let Waiting = document.querySelector("#Waiting");
let SubMesas = [];
let Mozo = [];


Retornar.addEventListener("click", () => {
	window.location.href = "Mesa";
});


Waiting.addEventListener("click", async () => {
	let Data = await Fetch("AllPrintWaiting");

	if (Data.status == true) {
		Swal.fire("Imprimiendo Pedidos en Espera")
		const Data = Array.from(document.querySelectorAll(".Waiting"))
		Data.map((a)=>a.classList.remove("Waiting"))
	} else {
		Swal.fire(Data.Details)
	}
});

Dividir.addEventListener("click", async () => {
	let Data = await Fetch("Dividir");
	localStorage.setItem("Datos", JSON.stringify(Data));
	window.location.href = "Menu";
});

let List = () => {
	let Fragment = document.createDocumentFragment();
	ListMesas.innerHTML = "";
	console.log(SubMesas)

	SubMesas.forEach((A) => {
		let div = document.createElement("div");
		let num = document.createElement("button");
		let Name = document.createElement("div");
		let icon = document.createElement("i");

		num.textContent = A.Mesa;
		Name.textContent = A.Nombre;

		div.setAttribute("class", "Mesa");
	


		if (parseInt(A?.Espera || 0) === 1) {
			num.setAttribute("class", "Number Waiting");
			Name.setAttribute("class", "Name Waiting");
		} else {
			num.setAttribute("class", "Number");
			Name.setAttribute("class", "Name");
		}

		icon.setAttribute("class", "fas fa-file-invoice-dollar");

		div.appendChild(num);
		div.appendChild(icon);
		div.appendChild(Name);
		Fragment.appendChild(div);

		div.addEventListener("click", async () => {

			let Datos = new FormData();
			Datos.append("Mesa", A.Mesa);
			let Mesa = await Fetch("MesaStatus", Datos);

			let Codigo = Mesa.Codigo;

			if (Mesa.Ocupa == "*") {
				if (Codigo != Mozo.Codigo && Codigo != "") {
					if (Mozo.Acceso != 1) {
						Swal.fire("Esta Mesa esta siendo ocupada por otro Camarero");
						return 0;
					}
				}

				if (Codigo == "" && Mesa.Ocupa == "*") {
					Swal.fire("Esta Mesa esta siendo ocupada por otro Camarero");
					return 0;
				}
			}
			else {

				if (Codigo != Mozo.Codigo && Codigo != "" && Mozo.Acceso != true) {
					Swal.fire("Esta Mesa pertenece a otro Camarero");
					return 0;
				}

			}

			let Data = new FormData();
			Data.append("Mesa", A.Mesa);
			Data.append("Depen", A.Depen);
			await Fetch("Mesa", Data);
			window.location.href = "Pedido";
		});
	});

	ListMesas.appendChild(Fragment);
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

	Mozo = await Fetch("Mozo");
	Camarero.textContent = Mozo.Nombre;

	SubMesas = await Fetch("SubMesas");
	localStorage.clear();
	List();
})();

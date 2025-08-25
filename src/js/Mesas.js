let Name = document.querySelector("#Mozo");
let ListMesas = document.querySelector("#List-Mesa");
let Retornar = document.querySelector("#Retornar");
let Mesas = [];
let Mozo = [];

let getMesas = () => {
	let Fragment = document.createDocumentFragment();
	ListMesas.innerHTML = "";


	Mesas.forEach((A) => {
		let div = document.createElement("div");
		let num = document.createElement("button");
		let img = document.createElement("img");
		let name = document.createElement("div");
		let span = document.createElement("span");

		div.setAttribute("class", "Mesa");
		name.setAttribute("class", "Name");



		if (parseInt(A?.Espera || 0) === 1) {

			num.setAttribute("class", "Waiting");
			span.setAttribute("class", "Waiting");

			if (["", Mozo.Codigo].includes(A.Codigo) && A.Ocupa == "" && A.Letra !== "") {
				num.setAttribute("class", "Waiting-primary");
				span.setAttribute("class", "Waiting-primary");
			}

			if ((A.Codigo == "" && A.Ocupa == "*") || A.Codigo != Mozo.Codigo && A.Codigo != "" && A.Letra !== "") {
				num.setAttribute("class", "Waiting-danger");
				span.setAttribute("class", "Waiting-danger");
			}
		} else {

			if (A.Codigo == "" && A.Ocupa == "") {
				if (A.Letra != "") {
					num.setAttribute("class", "badge-primary");
					span.setAttribute("class", "badge-primary");
					img.setAttribute("src", "./src/img/Mesa2.png");
				} else {
					num.setAttribute("class", "badge-success");
					span.setAttribute("class", "badge-success");
				}
			}

			if (A.Codigo == "" && A.Ocupa == "*") {
				num.setAttribute("class", "badge-danger");
				span.setAttribute("class", "badge-danger");
			}

			if (A.Codigo == Mozo.Codigo) {
				if (A.Letra == "") {
					num.setAttribute("class", "badge-warning");
					span.setAttribute("class", "badge-warning");
				} else {
					num.setAttribute("class", "badge-primary");
					span.setAttribute("class", "badge-primary");
				}
			}

			if (A.Codigo != Mozo.Codigo && A.Codigo != "") {
				if (A.Letra == "") {
					num.setAttribute("class", "badge-danger");
					span.setAttribute("class", "badge-danger");
				} else {
					num.setAttribute("class", "badge-primary");
					span.setAttribute("class", "badge-primary");
				}
			}
		}



		if (A.Letra != "") {
			img.setAttribute("src", "./src/img/Mesa2.png");
		} else {
			img.setAttribute("src", "./src/img/Mesa.png");
		}

		if (A.Name != "") {
			let Nomb = A.Name.split(" ", 1)[0];

			span.textContent = Nomb.substring(0, 10);
			name.appendChild(span);
		}

		num.textContent = A.Mesa;
		div.appendChild(name);
		div.appendChild(img);
		div.appendChild(num);
		Fragment.appendChild(div);

		/* Evento click */
		div.addEventListener("click", async () => {
			let Depen = "";

			let Data = new FormData();
			Data.append("Mesa", A.Mesa);
			let Mesa = await Fetch("MesaStatus", Data);
			let Codigo = Mesa.Codigo;

			if (Codigo == "" && Mesa.Ocupa == "" && Mesa.Letra != "") {
				Data = new FormData();
				Data.append("Mesa", Mesa.Mesa);
				Data.append("Depen", "");
				await Fetch("Mesa", Data);


				const SubMesa = await Fetch("SubMesas");

				if (Object.keys(SubMesa).length !== 0) {

					Codigo = SubMesa[0].Codigo;
					Mesa.Mesa = SubMesa[0].Mesa;
					Depen = SubMesa[0].Depen;

					if (SubMesa[0].Depen.trim() !== "") {
						Mesa.Mesa = SubMesa[0].Depen;
						Depen = "";
					} else {
						Mesa.Mesa = SubMesa[0].Mesa;
						Depen = SubMesa[0].Depen;
					}

					Data = new FormData();
					Data.append("Mesa", "");
					Data.append("Depen", "");
					await Fetch("Mesa", Data);
				}

			}

			if (Mesa.Ocupa == "*") {
				if (Codigo != Mozo.Codigo && Codigo != "") {
					Swal.fire("Esta Mesa esta siendo ocupada por otro Camarero");
					return 0;
				}

				if (Codigo == "" && Mesa.Ocupa == "*") {
					Swal.fire("Esta Mesa esta siendo ocupada por otro Camarero");
					return 0;
				}
			} else {
				if (Codigo != Mozo.Codigo && Codigo != "") {
					if (Mozo.Acceso != 1) {
						Swal.fire("Esta Mesa pertenece a otro Camarero");
						return 0;
					}
				}
			}

			localStorage.setItem("Datos", JSON.stringify({ Mesa: Mesa.Mesa, Depen: Depen }));
			Data = new FormData();
			Data.append("Mesa", Mesa.Mesa);
			Data.append("Depen", Depen);
			await Fetch("Mesa", Data);

			window.location.href = "SubMesa";
		});
	});

	ListMesas.appendChild(Fragment);
};

Retornar.addEventListener("click", () => {
	window.location.href = "Areas";
});

(async () => {
	sessionStorage.setItem("Shopping_Old", JSON.stringify([]));
	sessionStorage.setItem("Shopping", JSON.stringify([]));

	Mozo = await Fetch("Mozo");
	Name.textContent = Mozo.Nombre;

	window.location.hash = "no-back-button";
	window.location.hash = "Again-No-back-button"; //chrome
	window.onhashchange = function () {
		window.location.hash = "no-back-button";
	};
	Mesas = await Fetch("Mesas");


	getMesas();

	/* Limpiar Mesas  */
	const Datos = JSON.parse(localStorage.getItem("Datos")) ?? [];

	if (Object.keys(Datos).length !== 0) {
		let Data = new FormData();
		Data.append("Mesa", Datos.Mesa);
		await Fetch("ClearMesa", Data);
	}

	localStorage.clear();
})();

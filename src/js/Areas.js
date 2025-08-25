let AresContent = document.querySelector("#Areas-content");
let Name = document.querySelector("#Mozo");

(async () => {
	let Mozo = await Fetch("Mozo");
	Name.textContent = Mozo.Nombre;

	AresContent.innerHTML = "";
	let Fragment = document.createDocumentFragment();
	let Areas = await Fetch("Areas");

	Areas.forEach((A) => {
		let div = document.createElement("div");
		let Left = document.createElement("div");
		let Right = document.createElement("div");
		let span1 = document.createElement("span");
		let span2 = document.createElement("span");

		div.setAttribute("class", "Areas");
		Left.setAttribute("class", "Areas-Name");
		Right.setAttribute("class", "Areas-Rango");
		span1.innerText = A.Descri;
		span2.innerText = "Mesas en el Area " + A.Desde + " - " + A.Hasta;

		Left.appendChild(span1);
		Right.appendChild(span2);
		div.appendChild(Left);
		div.appendChild(Right);
		Fragment.appendChild(div);

		div.addEventListener("click", async () => {
			let Data = new FormData();
			Data.append("Area", A.Codigo);

			await Fetch("Area", Data);
			window.location.href = "Mesa";
		});
	});

	AresContent.appendChild(Fragment);

	
	/* Limpiar Mesas  */
	const Datos = JSON.parse(localStorage.getItem("Datos")) ?? [];

	if(Object.keys(Datos).length !== 0){
	 	let Data = new FormData();
		Data.append("Mesa", Datos.Mesa);
		await Fetch("ClearMesa",Data);
	}
	
	localStorage.clear();
})();

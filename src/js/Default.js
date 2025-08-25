let OnClick = async (Selector, Callback) => {
	let element = document.querySelector(Selector);

	if (typeof element != "undefined" && element != null) {
		element.addEventListener("click", Callback);
	}
};

let SelectList = (Obj, Filter, Callback) => {
	Obj.forEach((item) => {
		item.addEventListener("click", () => {
			let Code = item.getAttribute("data-code");
			let Data2 = Data.filter((X) => X[Filter].trim() == Code);
			Callback(Data2);
		});
	});
};

let Format = (value) => {
	let Num = new Intl.NumberFormat("en-IN", {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	}).format(value);

	return Num;
};

let Fetch = async (Url, formCode = []) => {
	let Data;
	try {
		let Response = await fetch(Url, {
			method: "POST",
			body: formCode,
		});

		Data = [{ Status: Response.status }];

		if (Response.status === 200) {
			if (typeof Response === "undefined" && Response == null) {
				Response = "";
			}

			Data = await Response.json();
		}

	} catch (error) {
		Data = error;
	}

	return Data;
};


let GetSession = (Name) => {
	let json = sessionStorage.getItem(Name);
	let Data = JSON.parse(json);

	if (typeof Data === "undefined" || Data == null) {
		Data = [];
	}
	return Data;
};

let SetSession = (Name, ObjetValue = []) => {
	sessionStorage.setItem(Name, JSON.stringify(ObjetValue));
};

let ClearSession = () => {
	sessionStorage.setItem("Data", JSON.stringify([]));
	sessionStorage.setItem("Cart", JSON.stringify([]));
};


function Offerta(Count, Value = 2, Price = 1) {
	if (Count < Value) {
		Oferta = Count;
	} else {
		if (Count % Value === 0) {
			Value = Count / Value;
			Oferta = Value * Price;
		} else {
			Residue = Count % Value;
			Value = Math.floor(Count / Value) + Residue;
			Oferta = Value * Price;
		}
	}
	return Oferta;
}

/* Redirection */

OnClick("#Logout", () => {
	ClearSession();
	Fetch("LogOut");
	location.href = "./";
});

let ShoppinCant = async (Element) => {
	let CartOld = [];
	let Cart = [];

	[CartOld, Cart] = await Fetch("Cart");

	sessionStorage.setItem("Shopping_Old", JSON.stringify(CartOld));
	sessionStorage.setItem("Shopping", JSON.stringify(Cart));


	Cart = Cart.filter((X) => X.Code != "");
	CartOld = CartOld.filter((X) => X.Code != "");

	let Cant = Object.keys(Cart).length;

	let Cant2 = Object.keys(CartOld).length;

	return Cant + Cant2;
}

const isMovil = ()=>{
	let Device =['Android','webOS','iPhone','iPad','iPod','BlackBerry','Windows Phone'];
	let Movil = Device.some((A)=>{
		let Search = new RegExp(A,'i')
		return navigator.userAgent.match(Search)
	});
	return Movil || false
};


const PrintAndroid = async()=>{
	let Title = [];
	let Pedidos = [];
	let Mesa = [];

	[Title, Pedidos, Mesa] = await Fetch("HeaderPrint");

	let Titles = [];

	if(Title.type === 1 || Title.type === "1"){
		Title.telf = Title.telf.replaceAll("  "," ")
	}

	Titles.push(Title);

	let SubTitle = [];
	SubTitle.push(Pedidos);
	
	let Details = await Fetch("Resumir");
			

	PrintingEvent.showToast(JSON.stringify(Titles),JSON.stringify(SubTitle), JSON.stringify(Details));
}

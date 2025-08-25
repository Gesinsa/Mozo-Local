const container = document.getElementById("containera");
let code = document.getElementById("code");
let codeping = [];



//clear pins
function clearpin() {

	let BtnsCode = Array.from(document.querySelectorAll("#code div.code"));

	BtnsCode.forEach((BtnCode)=>{
		BtnCode.classList.remove("code-checked");
	});

	codeping = [];
}

//add digit pin
function addpin(codes) {
	const btn = document.getElementById("pin" + codes);
	document.getElementById("error").setAttribute("style", "display: none;");
	container.classList.remove("animate__shakeX");

	if (codeping.length < 4) {
		codeping.push(btn.textContent);

    let BtnCode = document.querySelector("div.code:not(.code-checked)")
	  BtnCode.classList.add("code-checked");

		if(codeping.length == 4){
			Sendcode();
		}
	}
}


//Auth pin
let Sendcode = async () => {
	let pinx = "";
	for (i of codeping) {
		pinx += i;
	}


	let body = new FormData();
	body.append("PIN", pinx);

	let Login = await Fetch("Login", body);


	
	if ((Login?.succes || false) === false) {
		clearpin();
		let DivError = document.querySelector("#error");
		DivError.removeAttribute('style')
		container.classList.add("animate__shakeX");
	} 
	else {
		window.location.href = "Areas";
	}
};


//Clear digit pin
function Delete() {

   let BtnsCode = Array.from(document.querySelectorAll("#code div.code-checked"));
	let cout = Object.keys(BtnsCode).length;

	if (cout> 0) {
		codeping.pop(cout - 1);
		BtnsCode[cout-1].classList.remove("code-checked");
	}
}


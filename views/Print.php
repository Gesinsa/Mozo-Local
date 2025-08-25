<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title id="Title">print</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>

<body>
  <style>
    @media screen,
    print {
      * {
        font-size: 12px;
        font-family: ui-sans-serif;
      }

      body {
        display: grid;
        justify-items: center;
      }

      #Factura {
        width: 82mm;
      }

      #Header {
        display: grid;
        justify-items: center;
      }

      #Header h1,
      #Header h2 {
        text-align: center;
        font-weight: bold;
      }

      #Header h1:empty,
      #Header h2:empty {
        display: none;
      }

      #Header h1 {
        font-size: 21px;
        margin-top: 0;
        margin-bottom: 5px;
      }

      #Header h2 {
        font-size: 17px;
        margin: 5px 0px;
      }

      h3 {
        text-align: center;
        margin: 5px 0px;
      }

      .Cuadro {
        border: 1px solid black;
        width: -webkit-fill-available;
      }

      .Cuadro1 {
        display: grid;
        justify-items: center;

      }

      .Cuadro1 h2 {
        font-size: 16px;
      }

      .Details-Fact {
        width: -webkit-fill-available;
        padding: 0px 2px;
        margin: 5px 0px;
      }

      .Details-Fact div {
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
      }

      .Details-Fact p {
        font-size: 12px;
        font-weight: bold;
        margin: 3px 0px;
      }

      .Details-Fact #Mesa {
        font-size: 16px;
      }

      .Cuadro2 {
        display: grid;
        /*justify-items: center;*/

      }

      .Cuadro2 p {
        font-size: 14px;
        font-weight: bolder;
        padding: 0px 5px;
        margin: 2px 0px;
      }

      .Cuadro2 div {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      #Detail {
        width: -webkit-fill-available;
        padding: 0px 2px;
        margin: 3px 0px;
        border-bottom: 1px solid black;
      }

      #Detail p {
        font-size: 15px;
        font-weight: bolder;
        padding: 0px 5px;
        margin: 2px 0px;
      }

      #Detail div {
        display: grid;
        align-items: center;
        grid-template-columns: 1fr 1fr 1fr;
      }

      #Detail div p {
        font-size: 12px;
      }

      #Detail div p:nth-child(n+2) {
        text-align: right;
      }

      .Totals {
        display: grid;
        grid-template-columns: 0.8fr 1.2fr;
        justify-items: end;
        width: -webkit-fill-available;
        padding: 0px 5px;
        margin: 5px 0px;
      }

      .Totals p {
        font-size: 14px;
        font-weight: bolder;
        margin: 3px 0px;
      }



      .Cuadro3 {
        width: -webkit-fill-available;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0px 5px;

      }

      .Cuadro3 p {
        font-size: 18px;
        font-weight: bold;
        margin: 2px 0px;
      }


      .Cuadro3 p:nth-child(2) {
        text-align: right;
      }

      .Cuadro.Cuadro3 {
        margin-bottom: 10px;
      }

      .Monto p {
        font-size: 15px
      }

      .Text1 {
        border-top: 1px solid black;
        padding: 0px 5px;
      }

      .Text1 p.comprobante {
        font-size: 17px;
        font-weight: bold;
        margin: 5px 0px;
      }

      .Text2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }

      .Text2 p {
        font-size: 15px;
        font-weight: 600;
        margin: 0px 0px 3px 0px;
      }

      .Info {
        margin-top: 8px;
      }

      .Text3 {
        display: grid;
        grid-template-columns: 55px auto;
        align-items: center;
        gap: 5px;
      }

      .Text3 p {
        font-size: 13px;
        font-weight: bold;
        margin: 0px 0px 5px 0px;
      }

      .linea {
        border-bottom: 1px solid black;
      }

      .Footers {
        display: flex;
        width: -webkit-fill-available;
        align-items: center;
        padding: 0px 5px;
        justify-content: space-between;
      }

      .Footers p {
        font-size: 16px;
        margin: 3px 0px;
      }

      #msg {
        font-size: 16px;
      }

      #Footer {
        margin-bottom: 15px;
      }
    }
  </style>

  <div id="Factura">
    <div id="Header">
      <H1 id="Name"> . </H1>
      <h2 id="Direction"> . </h2>
      <h2 id="Telf"></h2>
      <h2 id="Rnc"></h2>
    </div>

    <div class="Cuadro Cuadro1">
      <h2>ORDEN - DE - PAGO</h2>
    </div>

    <div class="Details-Fact">
      <div>
        <p id="Orden"></p>
        <p id="Mesa"></p>
      </div>

      <div>
        <p id="Fecha"></p>
        <p id="Apertura"></p>
      </div>
      <!-- <p id="Cajero"></p> -->

      <!-- <p id="Caja"></p>
        <p id="Turno"></p> -->

      <p id="Camarero"></p>
      <!-- <p id="pedido"></p> -->
    </div>

    <div class="Cuadro Cuadro2">
      <p>DESCRIPCION</p>
      <div>
        <p>CANTIDAD</p>
        <p>PRECIO</p>
        <p>TOTAL</p>
      </div>
    </div>

    <div id="Detail">

    </div>

    <div class="Totals">
      <p>MONTO BRUTO:</p>
      <p id="Bruto"></p>

      <p>% ITBIS:</p>
      <p id="Itbis"></p>

      <p>% LEY:</p>
      <p id="Ley"></p>
    </div>

    <div class="Cuadro Cuadro3">
      <p>MONTO NETO: </p>
      <p id="Neto"></p>
    </div>

    <div class="Monto Cuadro3">
      <p>TOTAL EN DOLAR: </p>
      <p id="NetoUS"></p>
    </div>

    <div class="Monto Cuadro3">
      <p>TOTAL EN EURO: </p>
      <p id="NetoEU"></p>
    </div>

    <div class="Text1">
      <p class="comprobante">Si Desea Comprobante, Expecificar:</p>

      <div class="Text2">
        <p>()Valor Fisca</p>
        <p>()Reg. Especial</p>
      </div>

      <div class="Text2">
        <p>()Cons. Final</p>
        <p>()Gu\bernamental</p>
      </div>

      <div class="Info">
        <div class="Text3">
          <p>RNC :</p>
          <p class="linea"></p>
        </div>
        <div class="Text3">
          <p>Nombre :</p>
          <p class="linea"></p>
        </div>
      </div>
    </div>

    <div class="Footers">
      <p id="Terminal"></p>

    </div>

    <h3 id="msg"></h3>
    <h3 id="Footer">******** FIN DE DOCUEMENTO NO VENTA ********</h3>
  </div>
</body>
<script src="./src/js/Default.js"></script>
<script>
  (async () => {

    let Factura = async () => {

      let Pedidos, Title, Mesa = [];

      [Title, Pedidos, Mesa] = await Fetch("HeaderPrint");

      document.querySelector("#msg").textContent = "<< COPYRIGHT PROISA >>";

      let Name = document.querySelector("#Name");
      let Direc = document.querySelector("#Direction");
      let Telf = document.querySelector("#Telf");
      let Rnc = document.querySelector("#Rnc");

      let Header = document.querySelector("#Header");

      if (parseInt(Title.type) == 1) {
        let Fragment = document.createDocumentFragment();

        Name.textContent = Title.name.trim();
        Direc.textContent = Title.direc1.trim();
        Telf.textContent = Title.direc2.trim();



        let h2 = document.createElement("h2");
        let h3 = document.createElement("h2");

        h2.textContent = Title.RCN.trim();
        h3.textContent = Title.telf.trim();

        Fragment.appendChild(h2);
        Header.appendChild(Fragment);
      } else {

        Name.textContent = Title.name.trim();
        Direc.textContent = Title.direc1.trim();
        Telf.textContent = Title.telf.trim() + " / " + Title.telf2.trim();

        if (Title.direc2.trim() != "") {
          Rnc.textContent = Title.direc2.trim();
        }
      }

      document.querySelector("#Orden").textContent = "PEDIDO NRO: " + Pedidos.Factura;
      document.querySelector("#Mesa").textContent = "MESA: " + Pedidos.Mesa;
      document.querySelector("#Fecha").textContent = "FECHA : " + Pedidos.Fecha;
      // document.querySelector("#Cajero").textContent = "CAJERO: ";
      // document.querySelector("#Caja").textContent = "CAJA #:";
      // document.querySelector("#Turno").textContent = "TURNO #: ";
      document.querySelector("#Camarero").textContent = "CAMARERO: JUNIOR";
      document.querySelector("#Apertura").textContent = "APERTURA: " + Pedidos.Hora;
      // document.querySelector("#pedido").textContent = "PEDIDO NRO : 0000008572";

      document.querySelector("#Bruto").textContent = Format(Pedidos.Monto);
      document.querySelector("#Itbis").textContent = Format(Pedidos.Itbis);
      document.querySelector("#Ley").textContent = Format(Pedidos.Ley);
      document.querySelector("#Neto").textContent = Format(Pedidos.Neto);


      document.querySelector("#NetoEU").textContent = Format(Pedidos.NetoEU);
      document.querySelector("#NetoUS").textContent = Format(Pedidos.NetoUS);
      //document.querySelector("#Entrada").textContent = "ENTRADA:";
      //document.querySelector("#Cierre").textContent = "CIERRE:";
      document.querySelector("#Terminal").textContent = "Terminal:"


      let Resumen = await Fetch("Resumir");

      Resumen = await Resumen.filter((A) => {
        return parseInt(A.Cantidad) > 0;
      });

      let Detalle = document.querySelector("#Detail");
      Detalle.innerHTML = "";

      let Fragment = document.createDocumentFragment();

      Resumen.forEach((A) => {
        let Name = document.createElement("p");
        let Div = document.createElement("div");
        let Count = document.createElement("p");
        let Price = document.createElement("p");
        let Total = document.createElement("p");

        Name.textContent = A.Descrip;
        Count.textContent = Format(A.Cantidad);
        Price.textContent = Format(A.Precio);
        Total.textContent = Format(A.Total);


        Div.appendChild(Count);
        Div.appendChild(Price);
        Div.appendChild(Total);

        Fragment.appendChild(Name);
        Fragment.appendChild(Div);
      });


      Detalle.appendChild(Fragment);
      console.clear();
    }


    await Factura();

    let isMovil = () => {
      let Device = ['Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone'];
      let Movil = Device.some((A) => {
        let Search = new RegExp(A, 'i')
        return navigator.userAgent.match(Search)
      });
      return Movil || false
    };

    if (isMovil()) {
      setTimeout(() => {

        PrintingEvent.showToast("PrintingEvent");
      }, 700);
    }


    /*
    (()=>{

    	
    	window.addEventListener("error", (e)=>{
    		console.log("prueba")
    		console.log(e.message
    )
    		alert(e.message);
    	});

    	alert("fdgfdgf");

    	PrintingEvent.showToast("Prueba Hola Mundo");
    })()*/

    setTimeout(() => {
      if (parseInt(Mesa.status) == 1) {
        window.location.href = "Mesa";

      } else {

        window.location.href = "Pedido";
      }

    }, 10000);

  })();
</script>

</html>
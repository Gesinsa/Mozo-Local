<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
date_default_timezone_set('America/Santo_Domingo');
$Session = new Session();

$Factura = "";
$Name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
$Mozo = trim($Session->GetProperty("UserData", "Codigo", ''));
$Mesa = trim($Session->Get('Mesa', ''));
$Depen = trim($Session->Get('Depen', ''));

$Desct = $Session->GetProperty('Totals', 'Discount', 0);
$Total = $Session->GetProperty('Totals', 'Total', 0);
$itbis = $Session->GetProperty('Totals', 'Itbis', 0);
$Ley =  $Session->GetProperty('Totals', 'Ley', 0);


$Monto = (($Total - $Desct) + $itbis) + $Ley;
$MontoUS = 0;
$MontoEU = 0;

$Data = $Session->Get("ShoppingCart", []);

if ($Mozo == "") {

	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	return array("status" => false, 'Error' => 'El Mesero Esta Vacio');;
}

if ($Mesa == "") {
	$Depen = "";
	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	return array("status" => false, 'Error' => 'La Mesa Esta Vacia');
}

if (count($Data) == 0) {
	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	return array("status" => false, 'Error' => 'Se Produjo Un Error y el Carito se encuentra Vacio');
}

if (count($Data) == 0 || floatval($Total) <= 0) {
	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	return array("status" => false, 'Error' => 'Se Produjo una interrupcion vuelva a procesar este pedido');
}

$Monedas = $DB->query("SELECT rtrim(mo_descri) as name  ,mo_tasa as Taza FROM  cbbdmone");

foreach ($Monedas  as $key => $A) {
	$name2 = strtoupper(trim($A['name']));

	if (in_array($name2, ['DOLAR', 'DOLARES', 'US', 'DOLAR ESTADOUNIDENSE'])) {
		$MontoUS = floatval($Monto) / floatval($A['Taza']);
	}

	if (in_array($name2, ['EURO', 'EUROS', 'EU'])) {
		$MontoEU =  floatval($Monto) / floatval($A['Taza']);
	}
}

/* Validar Espera en Mesa */
$MesaEspera = false;
$Search = true;
$Count = count($Data);
$i = 0;




while ($Search) {

	if ($Data[$i]['Espera'] == true) {
		$MesaEspera = true;
		$Search = false;
		break;
	}

	if ($Count >= $i) {
		break;
		$Search = false;
	}
	$i++;
}



$Dato = $DB->query("select ITBIS,D_LEY,PV_FECOPER, GETDATE() as fechasql from FABDPROC");
$LeyX = $Dato[0]["D_LEY"];
$ItbisX = $Dato[0]["ITBIS"];
$Fecha = $Dato[0]["PV_FECOPER"];
$FechaFin = $Dato[0]["fechasql"];

$error = 1;
//* *//
function Orden($DB, $Value, $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera = 0)
{
	global $error;

	if (!empty($Value) && $Value != null) {

		$error .= ".1";
		$DB->Insert(
			"INSERT INTO IVBDDETE (DE_FACTURA,MA_DEPEN,AR_CODIGO,DE_DESCRI,DE_CANTID,DE_PRECIO,MA_CODIGO,MO_CODIGO,
			DE_FECHA,DE_FECENT,DE_FECSAL,DE_LEY,DE_TBIS,de_docum,de_pr1,de_pr2,de_pr3,DE_POSICIO, de_espera) values 
			(:factura,:depen,'',:value,'0','0',:mesa, :mozo, :fecha,:fechafin,:fecha2,1,1,:docum,0,0,0,:pos, :wait)",
			[
				":factura" => $Factura, ":depen" => $Depen, ":value" => $Value, ":mesa" => $Mesa, ":mozo" => $Mozo,
				":fecha" => $Fecha, ":fechafin" => $FechaFin, ":fecha2" => $Fecha, ":docum" => $Docum, ":pos" => $Posicion, ":wait" => $Espera
			]
		);


		$Depen2 = (!isset($Depen) || trim($Depen) == "") ? $Mesa : $Depen;

		$error .= ".1";
		$DB->Insert(
			"INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,ARTICULO,DESCRIPCION,
			SECUENCIA,CANTIDAD,TIPO_ORDEN,TIPO_COCINA,TIPO_AREA,CAMARERO, posicion, de_espera, ma_depen) VALUES
			(:factura,:factura2,:mesa,:fecha,'',:value,:docum,0,:tipo,:cook,:area,:mozo, :pos, :wait,:Depen)",
			[
				":factura" => $Factura, ":factura2" => $Factura, ":mesa" => $Mesa, ":fecha" => $Fecha, ":value" => $Value,
				":docum" => $Docum, ":tipo" => $TypeOrden, ":cook" => $TypeCook,  ":area" => $Area, ":mozo" => $Mozo,
				":pos" => $Posicion, ":wait" => $Espera, ":Depen"=> $Depen2
			]
		);
	}
}

//*  Nuevo Pedido *//

try {
	$DB->Transaction();
	$Dato = $DB->query("SELECT HE_FACTURA as Factura from IVBDHETE Where MA_CODIGO = :mesa 
	and MA_DEPEN = :depen and he_tipfac = '' order by MA_CODIGO", [":mesa" => $Mesa, ":depen" => $Depen]);


	$CodeMesa = $Mesa;
	if (!empty($Depen)) {
		$CodeMesa = $Depen;
	}



	if (count($Dato) === 0) {
		$DB->Update("UPDATE IVBDPROC SET PEDIDO=PEDIDO+1");
		$Dato = $DB->query("SELECT PEDIDO FROM IVBDPROC");
		$Factura = str_pad($Dato[0]["PEDIDO"], 10, "0", STR_PAD_LEFT);
		$Session->Edit('Factura', $Factura);


		$error = "1.1";
		$sql = 			[
			":factura" => $Factura, ":depen" => $Depen, ":name1" => $Name, ":mesa" => $Mesa, ":mozo" => $Mozo,
			":total" => $Total, ":monto" => $Monto, ":montoeu" => $MontoEU, ":montous" => $MontoUS, ":itbis1" => $itbis,
			":ley" => $Ley, ":fecha" => $Fecha, ":fechafin" => $FechaFin, ":fecha2" => $Fecha, ":leyx" => $LeyX,
			":itbisx" => $ItbisX, ":desct" => $Desct
		];

		$DB->Insert(
			"INSERT INTO IVBDHETE (HE_FACTURA,MA_DEPEN,HE_NOMBRE,MA_CODIGO,MO_CODIGO,HE_MONTO,HE_NETO,HE_NETOEU, HE_NETOUS,
			HE_ITBIS,HE_TOTLEY,HE_fecha,HE_FECENT,HE_FECSAL,HE_LEY,HE_IMP,he_desc,HE_TURNO,he_CAJA,al_codigo,im_codigo)
			values 
			(:factura, :depen, :name1, :mesa, :mozo, :total, :monto, :montoeu, :montous, :itbis1,
			:ley, :fecha, :fechafin, :fecha2, :leyx, :itbisx, :desct,'1','02','01','02')",
			[
				":factura" => $Factura, ":depen" => $Depen, ":name1" => $Name, ":mesa" => $Mesa, ":mozo" => $Mozo,
				":total" => $Total, ":monto" => $Monto, ":montoeu" => $MontoEU, ":montous" => $MontoUS, ":itbis1" => $itbis,
				":ley" => $Ley, ":fecha" => $Fecha, ":fechafin" => $FechaFin, ":fecha2" => $Fecha, ":leyx" => $LeyX,
				":itbisx" => $ItbisX, ":desct" => $Desct
			]
		);

		$error = 2;
		if (!empty($Depen)) {
			$Letra = '01';
			$error = 3;
			$Dato = $DB->query("SELECT rtrim(LETRA) as Letra  FROM PVBDMESA WHERE MA_CODIGO = :depen", [":depen" => $Depen]);
			if ($Dato[0]["Letra"] != "") {
				$Letra = $Dato[0]["Letra"] + 1;

				if ($Letra < 10) {
					$Letra = '0' . $Letra;
				}
			}

			$error = 4;
			$DB->Update("UPDATE PVBDMESA set LETRA= :letra, MA_COBRAR=MA_COBRAR+1 WHERE MA_CODIGO = :depen", [":letra" => $Letra, ":depen" => $Depen]);
		}



		if ($MesaEspera == false) {

			$Mesas = [];
			if (!empty($Depen)) {
				$Mesas = $DB->query(
					"SELECT de_espera  FROM PVBDMESA WHERE de_espera = 1 and (MA_CODIGO = :mesa or MA_DEPEN = :depen)",
					[":mesa" => $Mesa, ":depen" => $Depen]
				);
			}

			if (count($Mesas) != 0) {
				$DB->Update("update PVBDMESA set de_espera = 1 where MA_CODIGO = :mesa", [":mesa" => $CodeMesa]);
			}

			$Mesas2 = $DB->query("SELECT de_espera  FROM PVBDMESA WHERE de_espera = 1 and MA_CODIGO = :mesa", [":mesa" => $Mesa]);

			if (count($Mesas2) != 0) {
				$MesaEspera = true;
			}
		} else {
			$DB->Update("update PVBDMESA set de_espera = 1 where MA_CODIGO = :mesa", [":mesa" => $CodeMesa]);
		}

		$error = 5;
		$DB->Update("update PVBDMESA set MA_FECENT = :fecha, MA_OCUPA = '', HE_NOMCLI= :name, MO_CODIGO = :mozo , de_espera = :Espera
		 where MA_CODIGO = :mesa", [":fecha" => $Fecha, ":name" => $Name, ":mesa" => $Mesa, ":mozo" => $Mozo, ":Espera" => $MesaEspera]);
	} else {
		$Factura = $Dato[0]["Factura"];

		$Session->Edit('Factura', $Factura);


		$DB->Update("Update IVBDHETE set HE_NETO = HE_NETO + '{$Monto}', HE_MONTO = HE_MONTO + '{$Total}',
        HE_ITBIS = HE_ITBIS + '{$itbis}', HE_TOTLEY =  HE_TOTLEY + '{$Ley}', HE_LEY = '{$LeyX}',
		  HE_IMP = '{$ItbisX}', HE_NOMBRE = '{$Name}', he_desc = he_desc + '{$Desct}', 
		  HE_NETOEU = HE_NETOEU + '{$MontoEU}', HE_NETOUS = HE_NETOUS + '{$MontoUS}'
        Where HE_FACTURA = '{$Factura}' and MA_CODIGO = '{$Mesa}' and he_tipfac = ''");

		//, MO_CODIGO = '{$Mozo}'


		if ($MesaEspera == false) {
			$Mesas = [];
			if (!empty($Depen)) {
				$Mesas = $DB->query(
					"SELECT de_espera  FROM PVBDMESA WHERE de_espera = 1 and (MA_CODIGO = :mesa or MA_DEPEN = :depen)",
					[":mesa" => $Mesa, ":depen" => $Depen]
				);
			}

			if (count($Mesas) != 0) {
				$DB->Update("update PVBDMESA set de_espera = 1 where MA_CODIGO = :mesa", [":mesa" => $CodeMesa]);
			} 

			$Mesas = $DB->query("SELECT de_espera  FROM PVBDMESA WHERE de_espera = 1 and MA_CODIGO = :mesa", [":mesa" => $Mesa]);

			if (count($Mesas) != 0) {
				$MesaEspera = true;
			}
		} else {
			$DB->Update("update PVBDMESA set de_espera = 1 where MA_CODIGO = :mesa", [":mesa" => $CodeMesa]);
		}



		$DB->Update("update PVBDMESA set MA_FECENT = :fecha, HE_NOMCLI= :name, MA_OCUPA = '', de_espera = :Espera where MA_CODIGO = :mesa", [":fecha" => $Fecha, ":name" => $Name, ":mesa" => $Mesa, ":Espera" => $MesaEspera]);
	}



	$T = "1";
	foreach ($Data as $key => $A) {
		$T = "2";
		$Docum = '';

		if (!empty($A['Guarn']) || !empty($A['Term']) || !empty($A['Note']) || count($A['Group']) != 0 || count($A['Ingre']) != 0 || count($A['Blend']) != 0) {
			$DB->Update('UPDATE IVBDPROC SET CREAUX=CREAUX+1');

			$Dato = $DB->query("select CREAUX  as docum from IVBDPROC");
			$Docum = str_pad($Dato[0]["docum"], 10, "0", STR_PAD_LEFT);
		}

		$Posicion = $A['Posicion'];
		$Espera = $A['Espera'];


		$error = 6;
		$DB->Insert(
			"INSERT INTO IVBDDETE (DE_FACTURA,MA_DEPEN,AR_CODIGO,DE_DESCRI,DE_CANTID,DE_PRECIO,MA_CODIGO,MO_CODIGO,
		DE_FECHA,DE_FECENT,DE_FECSAL,DE_LEY,DE_TBIS,de_docum,de_pr1,de_pr2,de_pr3, DE_POSICIO, de_espera) values 
		(:factura,:depen,:code,:name,:count,:price,:mesa,:mozo,:fecha,:fechafin,:fecha2,1,1,:docum,0,0,0,:pos,:wait)",
			[
				":factura" => $Factura, ":depen" => $Depen, ":code" => $A['Code'], ":name" => $A['Name'],
				":count" => $A['Count'], ":price" => $A['Price'], ":mesa" => $Mesa, ":mozo" => $Mozo,
				":fecha" => $Fecha, ":fechafin" => $FechaFin, ":fecha2" => $Fecha, ":docum" => $Docum, ":pos" => $Posicion, ":wait" => $Espera
			]
		);


		$TypeOrden = $A['Orden'];
		$TypeCook = $A['Type'];
		$Area = $A['Area'];


		$error = 7;

		$Depen2 = (!isset($Depen) || trim($Depen) == "") ? $Mesa : $Depen;
		$DB->Insert(
			"INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,ARTICULO,DESCRIPCION,
		SECUENCIA,CANTIDAD,TIPO_ORDEN,TIPO_COCINA,TIPO_AREA,CAMARERO, posicion, de_espera,ma_depen) VALUES
		(:factura,:factura2,:mesa,:fecha,:code,:name,:docum,:count,:tipo,:cook,:area,:mozo,:pos,:wait,:depen)",
			[
				":factura" => $Factura, ":factura2" => $Factura, ":mesa" => $Mesa, ":fecha" => $Fecha, ":code" => $A['Code'],
				":name" => $A['Name'], ":docum" => $Docum, ":count" => $A['Count'], ":tipo" => $TypeOrden, ":cook" => $TypeCook,
				":area" => $Area, ":mozo" => $Mozo, ":pos" => $Posicion, ":wait" => $Espera,":depen" => $Depen2
			]
		);


		if (!empty($A['Note'])) {
			$Note = [];
			array_push($Note, substr($A['Note'], 0, 40));

			$Nota2 = substr($A['Note'], 40, 40);
			if (trim($Nota2) !== "") {
				array_push($Note, $Nota2);
			}

			$error = 8;
			foreach ($Note as $key => $B) {
				Orden($DB, $B, $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
			}
		}

		$error = 9;
		if (!empty($A['Delivery'])) {
			Orden($DB, $A['Delivery'], $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
		}
		$error = 10;
		if (!empty($A['Guarn'])) {
			Orden($DB, $A['Guarn'], $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
		}

		$error = 11;
		if (!empty($A['Term'])) {
			Orden($DB, $A['Term'], $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
		}

		$error = 12;
		foreach ($A['Ingre'] as $key => $B) {
			Orden($DB, $B, $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
		}

		$error = 13;
		foreach ($A['Group'] as $key => $B) {
			Orden($DB, $B, $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
		}
		$error = 14;
		foreach ($A['Blend'] as $key => $B) {
			Orden($DB, $B["Name"], $Factura, $Docum, $Fecha, $FechaFin, $Mesa, $Depen, $Mozo, $Area, $TypeCook, $TypeOrden, $Posicion, $Espera);
		}
	}

	$DB->Commit();
	$Session->Edit('Article', '');

	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);

	return array("status" => true, "Factura" => $Factura);
} catch (PDOException $e) {
	$DB->RollBack();

	$Session->Edit('Article', '');

	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	return array("status" => false, "Factura" => $error, "sql" => $sql, 'Error' => $e);
}

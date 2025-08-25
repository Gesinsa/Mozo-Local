<?php
$error = 0;
try {

	require_once("./Class/DB.php");
	$DB = new DB();

	$Code = isset($_POST['Code']) ? $_POST['Code'] : '';
	$Cant = isset($_POST['Count']) ? $_POST['Count'] : 1;
	$Blend = isset($_POST['Blend']) ? json_decode($_POST['Blend'], false) : [];
	$Group = isset($_POST['Group']) ? json_decode($_POST['Group'], false) : [];
	$Ingre = isset($_POST['Ingre']) ? json_decode($_POST['Ingre'], false) : [];
	$Term = isset($_POST['Term']) ? $_POST['Term'] : "";
	$Guarn = isset($_POST['Guarn']) ? $_POST['Guarn'] : "";
	$Delivery = isset($_POST['Delivery']) ? $_POST['Delivery'] : "";
	$Note = isset($_POST['Note']) ? $_POST['Note'] : "";
	$Posicion =  isset($_POST['Posicion']) ? intval($_POST['Posicion']) : 0;
	$TypeCook = isset($_POST['TypeCook']) ? trim($_POST['TypeCook']) : "";
	$Espera = isset($_POST['Espera']) ? filter_var($_POST['Espera'], FILTER_VALIDATE_BOOLEAN) : false;


	$Day = substr(strftime("%a"), 0, 2);

	$array = [];

	require_once("./models/Totals.php");
	require_once("./Class/Session.php");
	$Session = new Session();


	if (count($Session->Get("ShoppingCart")) == 0 || floatval($Session->GetProperty('Totals', 'Total', 0)) <= 0) {
		$Session->Edit("ShoppingCart");
		$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	}

	function GetCodes($Data)
	{
		$Codes = [];

		foreach ($Data as $key => $X) {
			array_push($Codes, $X['Code']);
		}

		return $Codes;
	}

	function GetListCodes($Data)
	{
		$Str = "";

		$cout = count($Data) - 1;
		foreach ($Data as $key => $X) {
			$Str .= "'" . $X . "'";
			if ($cout != $key) {
				$Str .= ", ";
			}
		}
		return $Str;
	}

	$status = true;

	$Blend = isset($Blend) ? $Blend : [];
	$Blend2 = $Blend;

	if (array_key_exists("Code", $Blend) || array_key_exists("Code", $Blend[0])) {
		$Blend2 = GetCodes($Blend);
	}

	$Shoping = $Session->Get('ShoppingCart');

	foreach ($Shoping as $key => $X) {


		if (
			$X["Code"] == $Code && $X["Group"] == $Group && $X["Guarn"] == $Guarn && $X["Delivery"] == $Delivery && $X["Term"] == $Term && $X["Note"] == $Note
			&& $X["Ingre"] == $Ingre && $X['Posicion'] == $Posicion && GetCodes($X["Blend"]) == $Blend2 && $Espera == $X["Espera"]
		) {

			$T_Cant = $X["Count"];
			$Cant_ = $X["Count"] + $Cant;
			$status = false;


			$Total = $Session->GetProperty('Totals', 'Total', 0) - floatval($X['Total']);
			$Itbis = $Session->GetProperty('Totals', 'Itbis', 0) - floatval($X["Itbis"]);
			$Ley = $Session->GetProperty('Totals', 'Ley', 0) - floatval($X["Ley"]);
			$Discount = $Session->GetProperty('Totals', 'Discount', 0) - floatval($X["Discount"]);

			$Session->Edit('Totals', ['Total' => $Total, 'Itbis' => $Itbis, 'Ley' => $Ley, 'Discount' => $Discount]);



			if ($Cant > 0) {
				$Data = $DB->query("SELECT RTRIM(a.ar_codigo) as codigo,
				a.ar_predet,a.ar_premin,a.ar_premay, a.ar_predet AS Precio, 
				isnull(A.AR_OFERTA,'N') AS OFERTA,
				CASE
					WHEN isnull(D.pv_desc,0) != 0  THEN 'Desc'
					WHEN isnull(D.pV_2X1,0) = 1  THEN '2x1'
					ELSE 'N'
				END AS Tipo, 0 as CANTID, isnull(cast(D.pv_precio as int),0) as PV_PRECIO,
				isnull(D.pv_desc ,0) as PV_DESC,isnull(a.AR_ITBIS,'N') As Itbis,a.AR_DESCU as Descu
				FROM IVBDARTI A
				full join PVBDDIARTI as D on A.AR_CODIGO = D.AR_CODIGO and D.PV_DIA  ='{$Day}'
				AND GETDATE() BETWEEN D.pv_horaI AND D.pv_horaf
				WHERE A.AR_control='S' and a.ar_activado=' '
        and a.ar_codigo ='{$Code}' ORDER BY A.ar_cosfob asc");

				$Descu = $Data[0]["Descu"] / 100;
				$T_Itbis = 0;
				$Total = 0;
				$Precio = $Data[0]["Precio"];


				if ($Data[0]["AR_TIPGUAR"] == "O") {
					$Precio = 0;
					$Codes = GetListCodes($Blend2);

					$Count_Blend = intval($Data[0]["Seleccion"]);

					$Select = $DB->query("Select rtrim(A.AR_CODIGO) As Code ,rtrim(A.AR_CODIGO2) As Code2,
					rtrim(B.AR_DESCRI) As Name,B.AR_PREDET,B.AR_PREMIN,B.AR_PREMAY 
					from PVBDSELECCION AS A LEFT JOIN IVBDARTI AS B ON A.AR_CODIGO2=B.AR_CODIGO 
					WHERE A.AR_CODIGO = '{$Code}' and A.AR_CODIGO2 in($Codes) order by A.AR_CODIGO,B.AR_DESCRI");

					foreach ($Select as $key => $A) {
						$Precio += $A["AR_PREDET"] / $Count_Blend;
					}
				}

				$CantXPrec =  $Cant_ * $Precio;


				if ($Data[0]["OFERTA"] == "S") {

					if ($Data[0]["Tipo"] == "Desc") {
						$Descu = ($Data[0]["Descu"] / 100);
					}

					if ($Data[0]["Tipo"] == "2x1") {
						$CantXPrec = $Precio * Oferta($Cant_);
					}

					if ($Data[0]["Tipo"] == "CxP") {
						$_cant = $Data[0]["CANTID"];
						$_prec = $Data[0]["PV_PRECIO"];

						$CantXPrec = $Precio * Oferta($Cant_, $_cant, $_prec);
					}
				}


				$data = Itbis($DB, $Data[0]["Itbis"], $CantXPrec);
				$T_Itbis = floor( $data["Itbis"] * 100) / 100;
				$Total = floor($data["Total"] * 100) / 100;
				$Ley = floor($data["Ley"] * 100 ) / 100 ;

				$Descu =  floor(($Descu * $CantXPrec) * 100 ) / 100;

				$Shoping = $Session->GetProperty("ShoppingCart");
				$Shoping[$key]["Count"] =  $Cant_;
				$Shoping[$key]["Discount"] =  $Descu;
				$Shoping[$key]["Itbis"] =  $T_Itbis;
				$Shoping[$key]["Total"] =  $Total;
				$Shoping[$key]["Ley"] = $Ley;

				/*$Shoping2 = $Session->Get('ShoppingCart',[]);
				array_push($Shoping2, $array);
				$Session->Edit('ShoppingCart',$Shoping);*/

				$Session->Edit('ShoppingCart', $Shoping);


				$Total = $Session->GetProperty('Totals', 'Total', 0) + floatval($Total);
				$Itbis = $Session->GetProperty('Totals', 'Itbis', 0) + floatval($T_Itbis);
				$Ley = $Session->GetProperty('Totals', 'Ley', 0) + floatval($Ley);
				$Discount = $Session->GetProperty('Totals', 'Discount', 0) + floatval($Descu);
				$Session->Edit('Totals', ['Total' => $Total, 'Itbis' => $Itbis, 'Ley' => $Ley, 'Discount' => $Discount]);
			}

			if ($Cant <= 0) {
				$Shoping = $Session->Get('ShoppingCart');
				array_splice($Shoping, $key, 1);
				$Session->Edit('ShoppingCart', $Shoping);
			}
		}
	}


	if ($status) {

		$Data = $DB->query("SELECT RTRIM(a.ar_codigo) as codigo,
		a.ar_predet,a.ar_premin,a.ar_premay,rtrim(a.ar_descri) as ar_descri,
		a.AR_DETALLE,ISNULL(B.EXIREAL,0) AS EXISTENCIA,a.AR_COCINA as Cocina,
		CASE 
		WHEN a.AR_TIPOCOC = 1 THEN 'E'
		    WHEN a.ar_tipococ = 2 THEN 'F'
			ELSE ''
		END AS TipoCook,
		CASE 
		WHEN a.AR_BAR = 1 or a.AR_BAR2 =1 THEN 'BAR'
			WHEN a.AR_POSTRE = 1 THEN 'POSTRE'
			WHEN a.AR_CAJA = 1 THEN 'CAJA'
			WHEN a.AR_COCINA = 1 THEN 'COCINA'
			else 'BAR'
		END AS Orden,a.AR_TIPOAREA as Area,a.AR_ACOMPA,
		rtrim(a.AR_TIPGUAR) as AR_TIPGUAR, 
		ISNULL(rtrim(B.AL_CODIGO),'') AS ALMACEN,
		isnull(A.AR_OFERTA,'N') AS OFERTA,
		CASE
			WHEN isnull(D.pv_desc,0) != 0  THEN 'Desc'
			WHEN isnull(D.pV_2X1,0) = 1  THEN '2x1'
			ELSE 'N'
		END AS Tipo,
		0 as CANTID,
		isnull(cast(D.pv_precio as int),0) as PV_PRECIO,
		isnull(D.pv_desc ,0) as PV_DESC,isnull(a.AR_ITBIS,'N') As Itbis,a.AR_DESCU,
		isnull(a.ar_valint,0) as Seleccion
		FROM IVBDARTI A
		LEFT JOIN EXISTENCIA_REAL_ALMACEN B ON A.AR_CODIGO=B.AR_CODIGO AND B.AL_CODIGO='01'
		full join PVBDDIARTI as D on A.AR_CODIGO = D.AR_CODIGO and D.PV_DIA  ='{$Day}'
		AND GETDATE() BETWEEN D.pv_horaI AND D.pv_horaf
		WHERE A.AR_control='S' and a.ar_activado=' '
		and a.ar_codigo ='{$Code}'
		ORDER BY A.ar_cosfob asc");


		$Id = "01";
		$Blends = [];

		$Shoping = $Session->Get('ShoppingCart', []);

		if (count($Shoping) > 0) {
			$Key = key(array_slice($Shoping, -1, 1, true));

			$Id = $Shoping[$Key]["ID"] + 1;
			$Id = ($Id < 10) ? "0" . $Id : $Id;
		}


		$Precio = $Data[0]["ar_predet"];


		/* Validar Position */
		$Personas = $Session->GetProperty("Config", "Personas", 0);

		if (intval($Personas) == 1 && $Posicion <= 0) {
			echo json_encode(array("status" => false, "Details" => "Debes de Indicar El Nro. Posición del Comesal"));
			return 0;
		}

		if (trim($Data[0]["AR_TIPGUAR"]) == "O") {
			if (count($Blend) !== 0) {
				$Precio = 0;
				$Blends = [];
				$Codes2 = GetListCodes($Blend);

				$Count_Blend = intval($Data[0]["Seleccion"]);

				$Select = $DB->query("Select rtrim(A.AR_CODIGO) As Code ,rtrim(A.AR_CODIGO2) As Code2,
			rtrim(B.AR_DESCRI) As Name,B.AR_PREDET,B.AR_PREMIN,B.AR_PREMAY 
			from PVBDSELECCION AS A LEFT JOIN IVBDARTI AS B ON A.AR_CODIGO2=B.AR_CODIGO 
			WHERE A.AR_CODIGO = '{$Code}' and A.AR_CODIGO2 in($Codes2) order by A.AR_CODIGO,B.AR_DESCRI");

				if (count($Select) !== $Count_Blend) {
					echo json_encode(array("status" => false, "Details" => "Cantidad de Seleccion Erronea"));
					return 0;
				}

				foreach ($Select as $key => $A) {
					$newArray = ["Code" => $A["Code2"], "Name" => $A["Name"]];
					array_push($Blends, $newArray);

					$Precio += $A["AR_PREDET"] / $Count_Blend;
				}
			} else {
				echo json_encode(array("status" => false, "Details" => "Debes de Indicar Seleccion", "dr" => $Blend));
				return 0;
			}
		}


		if (trim($Data[0]["AR_TIPGUAR"]) == "C") {
			if (trim($Guarn) === "") {
				echo json_encode(array("status" => false, "Details" => "Debes de Indicar Los Guarnicion"));
				return 0;
			}
		} else {
			$Guarn = "";
		}


		if (trim($Data[0]["AR_TIPGUAR"]) == "G") {
			if (count($Group) === 0) {
				echo json_encode(array("status" => false, "Details" => "Debes de Indicar Los Grupos"));
				return 0;
			}
		} else {
			$Group = [];
		}


		$Nombre = $Data[0]["ar_descri"];
		$Almacen = $Data[0]["ALMACEN"];
		$cocina = $Data[0]['Cocina'];


		if (intval($cocina) != 1) {
			$TypeCook = "";
		}

		if (trim($TypeCook) == "") {
			$Type = $Data[0]['TipoCook'];
		} else {
			$Type = $TypeCook;
		}

		$Orden = $Data[0]['Orden'];

		$Area = $Data[0]['Area'];
		$Descu = $Data[0]["AR_DESCU"] / 100;
		$Itbis = 0;
		$Total = 0;
		$CantXPrec = $Cant * $Precio;
		$Letrero = "";


		if ($Data[0]["OFERTA"] == "S") {

			if ($Data[0]["Tipo"] == "Desc") {
				$Descu = $CantXPrec * ($Data[0]["AR_DESCU"] / 100);
				$Letrero = intval(($Data[0]["AR_DESCU"] / 100)) . "%";
			}

			if ($Data[0]["Tipo"] == "2x1") {
				$CantXPrec = $Precio * Oferta($Cant);
				$Letrero = "2x1";
			}

			if ($Data[0]["Tipo"] == "CxP") {
				$_cant = $Data[0]["CANTID"];
				$_prec = $Data[0]["PV_PRECIO"];

				$CantXPrec = $Precio * Oferta($Cant, $_cant, $_prec);
				$Letrero = $_cant . "x" . $_prec;
			}
		}

		$data = Itbis($DB, $Data[0]["Itbis"], $CantXPrec);
		$T_Itbis = floor( $data["Itbis"] * 100) / 100;
		$Total = floor($data["Total"] * 100) / 100;
		$Ley = floor($data["Ley"] * 100 ) / 100 ;

		$Descu =  floor(($Descu * $CantXPrec) * 100 ) / 100;
		$Descu = number_format($Descu, 2, '.', '');

		$array = array(
			'ID' => $Id, 'Code' => $Code, 'Store' => $Almacen, 'Name' => $Nombre, 'Price' => floatval($Precio),
			'Count' => floatval($Cant), 'Type' => $Type, 'Cocina' => $cocina, "Area" => $Area, 'Orden' => $Orden,
			'Discount' => floatval($Descu), 'Itbis' => floatval($T_Itbis), 'Total' => floatval($Total),
			'Letrero' => $Letrero, 'Group' => $Group, 'Term' => $Term,
			'Ingre' => $Ingre, 'Guarn' => $Guarn, 'Delivery' => $Delivery,
			'Note' => $Note, 'Ley' => $Ley, 'Posicion' => $Posicion, 'Blend' => $Blends, "Espera" => $Espera
		);


		$Total = $Session->GetProperty('Totals', 'Total', 0) + number_format($Total, 2, '.', '');
		$Itbis = $Session->GetProperty('Totals', 'Itbis', 0) + number_format($T_Itbis, 2, '.', '');
		$Ley = $Session->GetProperty('Totals', 'Ley', 0) + number_format($Ley, 2, '.', '');
		$Discount = $Session->GetProperty('Totals', 'Discount', 0) + number_format($Descu, 2, '.', '');
		$Session->Edit('Totals', ['Total' => $Total, 'Itbis' => $Itbis, 'Ley' => $Ley, 'Discount' => $Discount]);


		$Shoping = $Session->Get('ShoppingCart', []);
		array_push($Shoping, $array);
		$Session->Edit('ShoppingCart', $Shoping);
	}

	$Session->Edit('Article', '');
	echo json_encode(["status" => true, "Details" => $Shoping]);
} catch (PDOException $e) {
	echo json_encode(["status" => false, "Details" => $e, "error" => $error]);
}
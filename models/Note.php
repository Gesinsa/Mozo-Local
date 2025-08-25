<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();
$Factura = $Session->Get('Factura', '');
$Note = isset($_POST['Note']) ? json_decode($_POST['Note'], true) : [];

try {


	if (trim($Factura) == "") {
		return json_encode(array("status" => false, "error" => "Factura Null"));
	}

	if (count($Note) == 0) {
		return json_encode(array("status" => false, "error" => $Note));
	}

	$DB->Transaction();
	$Dato = $DB->query("SELECT Ma_codigo,Mo_codigo,he_Fecha  
	from IVBDHETE Where he_factura = '{$Factura}' and he_tipfac = '' order by MA_CODIGO");

	$Mesa = $Dato[0]["Ma_codigo"];
	$Mozo = $Dato[0]["Mo_codigo"];
	$Fecha = $Dato[0]["he_Fecha"];


	foreach ($Note as $key => $Value) {
		$DB->Update(
			"INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,CAMARERO, NOTA,TIPO_ORDEN, TIPO_AREA,  TIPO_COCINA) 
			VALUES (:factura, :factura2, :mesa,:fecha,:mozo, :Note ,'COCINA', '1', 'N')",
			[
				":factura" => $Factura, ":factura2" => $Factura,  ":mesa" => $Mesa,
				":fecha" => $Fecha, ":mozo" => $Mozo,	":Note" => $Value
			]
		);
	}


	$DB->Commit();
	return json_encode(array("status" => true, "Factura" => $Factura));
} catch (PDOException $e) {
	$DB->RollBack();
	return json_encode(array("status" => false, "Factura" => $Factura, 'Error' => $e, 'descrip' => "Print"));
}

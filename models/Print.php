<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();
$Factura = $Session->Get('Factura', '');

try{

	$Dato = $DB->query("SELECT Ma_codigo,Mo_codigo,he_Fecha  
	from IVBDHETE Where he_factura = '{$Factura}' and he_tipfac = '' order by MA_CODIGO");

	$Mesa = $Dato[0]["Ma_codigo"];
	$Mozo = $Dato[0]["Mo_codigo"];
	$Fecha = $Dato[0]["he_Fecha"];


	$DB->Update("INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,CAMARERO,TIPO_ORDEN) 
		VALUES ('{$Factura}', '{$Factura}', '{$Mesa}','{$Fecha}','{$Mozo}' ,'FACTURA')");

	$DB->Update("UPDATE PVBDMESA set  MA_pago='*' WHERE MA_CODIGO = '{$Mesa}'");  

	echo json_encode(array("status"=>true, "Factura"=> $Factura));
} catch(PDOException $e){
  echo json_encode(array("status"=>false, "Factura"=> $Factura,'Error'=>$e,'descrip'=>"Print"));
}

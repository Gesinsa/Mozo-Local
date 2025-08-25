<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
date_default_timezone_set('America/Santo_Domingo');
$Session = new Session();

$Name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
$Mesa =  $Session->Get('Mesa', '');
$Depen = $Session->Get('Depen', '');



if ($Mesa == "") {
	$Depen = "";
	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
	echo json_encode(array("status" => false, 'Error' => 'La Mesa Esta Vacia'));
	return 0;
}

$Dato = $DB->query("SELECT HE_FACTURA as Factura from IVBDHETE Where MA_CODIGO = '{$Mesa}' and MA_DEPEN = '{$Depen}' and he_tipfac = '' order by MA_CODIGO");

if (count($Dato[0]) == 0) {
	echo json_encode(array("status" => false, 'Error' => 'No se encontro la Factura'));
	return 0;
}


$Factura = $Dato[0]["Factura"];
$DB->Update("Update IVBDHETE set  HE_NOMBRE = '{$Name}'  Where HE_FACTURA ='{$Factura}' and  MA_CODIGO = '{$Mesa}' and MA_DEPEN = '{$Depen}' and he_tipfac = ''");
$DB->Update("update PVBDMESA set HE_NOMCLI= '{$Name}' where MA_CODIGO = '{$Mesa}' and MA_DEPEN = '{$Depen}'");

echo json_encode(array("status" => true));

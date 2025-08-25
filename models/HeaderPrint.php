<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();
$Mesa = $Session->Get('Mesa', '');

$Data1 = $DB->query("SELECT rtrim(ltrim(LEFT(LETRERO1,40))) as name,rtrim(ltrim(LEFT(LETRERO2,40))) as direc1,
rtrim(ltrim(LEFT(LETRERO3,40))) AS direc2,rtrim(ltrim(LEFT(LETRERO4,40))) as RCN ,
rtrim(ltrim(LEFT(LETRERO5,40))) as telf, 1 as type  FROM FABDPROC where FACTURACIO =1");


if (count($Data1) == 0) {
	$Data1 = $DB->query("SELECT rtrim(ltrim(nombre)) as name,rtrim(ltrim(direc1)) as direc1,
	rtrim(ltrim(telef1)) as telf,rtrim(ltrim(telef2)) as telf2, rtrim(ltrim(direc2)) as direc2,
	0 as type FROM CONTAEMP");
}


$Data2 = $DB->query("SELECT isnull(rtrim(HE_NOMBRE),'') AS Nombre,hE_FACTURA as Factura,MA_CODIGO as Mesa,
  CONVERT(VARCHAR(10),hE_FECENT,103) AS Fecha, RIGHT(hE_FECENT, 7) AS Hora,
	HE_MONTO as Monto,HE_ITBIS as Itbis,HE_TOTLEY as Ley, HE_NETO as Neto,
	HE_NETOEU as NetoEU, HE_NETOUS as NetoUS
  FROM  ivbdhete where MA_CODIGO = '{$Mesa}' and he_tipfac='' order by hE_ID");


$Monedas = $DB->query("SELECT rtrim(mo_descri) as name  ,mo_tasa as Taza FROM  cbbdmone");

foreach ($Monedas  as $key => $A) {
	$name = strtoupper(trim($A['name']));

	if (in_array($name, ['DOLAR', 'DOLARES', 'US', 'DOLAR ESTADOUNIDENSE'])) {
		$Data2[0]['NetoUS'] = floatval($Data2[0]['Neto']) / floatval($A['Taza']);
	}

	if (in_array($name, ['EURO', 'EUROS', 'EU'])) {
		$Data2[0]['NetoEU'] =  floatval($Data2[0]['Neto']) / floatval($A['Taza']);
	}
}


$Data3 = $DB->query("SELECT MA_CODIGO  from PVBDMESA Where MA_CODIGO = '{$Mesa}'  and  MA_OCUPA = '*' ");


if (count($Data3) == 0) {
	$status = 0;
} else {
	$status = 1;
}


$Data = [];
array_push($Data, $Data1[0]);
array_push($Data, $Data2[0]);
array_push($Data, array('status' => $status));
echo json_encode($Data);

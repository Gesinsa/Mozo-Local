<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();

$FormatoArea = $Session->GetProperty("Config", "Area", 0);
$Area = $Session->Get('Area_Dept', '');
$Data = [];


if (intval($FormatoArea) == 1) {
	if ($Area == "All") {
		$Data = $DB->query("select RTRIM(de_codigo) as Code, RTRIM(AR_DESCRI) as Name
		from IVBDDEPT where AR_PRESENT=1 and cod_sucu='1' ORDER BY ar_ORDEN asc");
	} else {
		$Data = $DB->query("	select RTRIM(de_codigo) as Code, RTRIM(AR_DESCRI) as Name
		from IVBDDEPT where AR_PRESENT=1 and cod_sucu='1' and  ARE_CODIGO = :Code ORDER BY ar_ORDEN asc", [":Code" => $Area]);
	}
} else {
	$Data = $DB->query("select RTRIM(de_codigo) as Code, RTRIM(AR_DESCRI) as Name 
	from IVBDDEPT WHERE AR_PRESENT=1  and cod_sucu='1' ORDER BY ar_ORDEN asc");
}

echo json_encode($Data);

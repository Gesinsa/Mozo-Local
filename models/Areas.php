<?php
require_once("./Class/DB.php");
$DB = new DB();

$Data = $DB->query("SELECT rtrim(Am_codigo) as Codigo,rtrim(AM_DESCRI) as Descri,
AM_DESDE as Desde, AM_HASTA as Hasta from IVBDAREAMESA where cod_sucu='1'");

echo json_encode($Data);
?>
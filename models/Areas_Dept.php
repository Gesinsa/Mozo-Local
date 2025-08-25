<?php
require_once("./Class/DB.php");
$DB = new DB();

$Data = $DB->query("select rtrim(ARE_CODIGO) as Code, rtrim(ARE_DESCRI) as Name from IVBDAREA where cod_sucu='1'");

echo json_encode($Data);
?>
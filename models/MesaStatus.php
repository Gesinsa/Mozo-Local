<?php
require_once("./Class/DB.php");
$DB = new DB();


   $Mesa = isset($_POST['Mesa']) ? $_POST['Mesa'] :'';

   $Data =$DB->query("SELECT rtrim(MO_CODIGO) as Codigo,rtrim(MA_OCUPA) as Ocupa,
   rtrim(MA_CODIGO) as Mesa,rtrim(HE_NOMCLI) as Nombre,rtrim(LETRA) as Letra, isnull(de_espera,0) as Espera
   from PVBDMESA where MA_CODIGO ='{$Mesa}'");



echo json_encode($Data[0]);
?>
<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();

$mesa = !empty($Session->Get('Depen', '')) ? $Session->Get('Depen', '') : $Session->Get('Mesa', '');

$Letra = '01';
$Mesa = $DB->query("SELECT rtrim(isnull(LETRA, '')) as LETRA  FROM PVBDMESA WHERE MA_CODIGO='{$mesa}'");

if ($Mesa[0]["LETRA"] != "") {

   $Letra = $Mesa[0]["LETRA"] + 1;

   if ($Letra < 10) {
      $Letra = '0' . $Letra;
   }
}

$Mesa = $DB->query("SELECT rtrim(LE_NOMBRE) as Letra FROM PVBDLETRA WHERE LE_CODIGO = '{$Letra}'");


$Session->Edit('Mesa', $mesa . $Mesa[0]["Letra"]);
$Session->Edit('Depen', $mesa);

echo json_encode(['Mesa' => $mesa . $Mesa[0]["Letra"], 'Depen'=>$mesa ]);

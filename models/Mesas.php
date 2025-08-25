<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();

$Formato = $Session->GetProperty("Config","Formato",0);

if (intval($Formato) == 4) {
   $Ini = $Session->GetProperty('UserData', 'Inicio', -1) + 1;
   $Fin = $Session->GetProperty('UserData', 'Fin', -1) + 1;


   $Data = $DB->query("SELECT rtrim(a.MO_CODIGO) as Codigo,rtrim(a.MA_OCUPA) as Ocupa, rtrim(a.MA_CODIGO) as Mesa,
   rtrim(a.HE_NOMCLI) as Name,rtrim(a.LETRA) as Letra, isnull(a.de_espera,0) as Espera
   from PVBDMESA as a 
   where a.MA_ID >='{$Ini}' and a.MA_ID <='{$Fin}' and a.MA_ID < 1001 order by a.ma_id");
} else {

   $Area = $Session->Get('Area', '');

   $Data = $DB->query("SELECT rtrim(a.MO_CODIGO) as Codigo,rtrim(a.MA_OCUPA) as Ocupa, rtrim(a.MA_CODIGO) as Mesa,
   rtrim(a.HE_NOMCLI) as Name,rtrim(a.LETRA) as Letra, isnull(a.de_espera,0) as Espera
   from PVBDMESA as a 
   inner join IVBDAREAMESA as b on 1=1
   where  a.MA_ID >= b.AM_DESDE +1  and a.MA_ID <= b.AM_HASTA +1 and a.MA_ID < 1001
   and b.Am_codigo ='{$Area}'  order by a.ma_id");
}


echo json_encode($Data);

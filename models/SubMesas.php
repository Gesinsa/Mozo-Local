<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();

$Mesa =  trim($Session->Get('Mesa', ''));
$Depen = trim($Session->Get('Depen', ''));

$Depen = !empty($Depen) ? $Depen : $Mesa;

$Data = $DB->query("SELECT rtrim(a.MA_CODIGO) as Mesa,rtrim(a.HE_NOMBRE) as Nombre,
rtrim(a.MA_DEPEN) as Depen,rtrim(a.MO_CODIGO) as Codigo, isnull(b.de_espera,0) as Espera from IVBDHETE as a
left join PVBDMESA b on b.MA_CODIGO = a.MA_CODIGO and b.MA_DEPEN = a.MA_DEPEN
Where (a.MA_CODIGO = '{$Mesa}' or a.MA_DEPEN ='{$Depen}') and a.he_tipfac='' order by a.MA_CODIGO asc");


/*
SELECT rtrim(a.MA_CODIGO) as Mesa,rtrim(a.HE_NOMBRE) as Nombre,
rtrim(a.MA_DEPEN) as Depen,rtrim(a.MO_CODIGO) as Codigo, isnull(b.de_espera,0) as Espera from IVBDHETE as a 
left join (

select top 1 de_espera,MA_CODIGO,MA_DEPEN,de_tipfac  from IVBDDETE where de_espera = 1
and (MA_CODIGO = '{$Mesa}' or MA_DEPEN =  '{$Depen}')
) as b on a.he_tipfac= b.de_tipfac
Where (a.MA_CODIGO = '{$Mesa}' or a.MA_DEPEN ='{$Depen}') and a.he_tipfac='' order by a.MA_CODIGO asc
*/

echo json_encode($Data);

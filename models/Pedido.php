<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();

$Mesa = $Session->Get('Mesa','');
$Data = [];

$Header = [];
$Details = [];

$Data1 =$DB->query("SELECT isnull(rtrim(HE_NOMBRE),'') AS Nombre,hE_FACTURA as Factura,MA_CODIGO as Mesa,
   CONVERT(VARCHAR(10),hE_FECENT,103) AS Fecha, RIGHT(hE_FECENT, 7) AS Hora,
	HE_MONTO as Monto,HE_ITBIS as Itbis,HE_TOTLEY as Ley,HE_NETO as Neto
   FROM  ivbdhete where MA_CODIGO = '{$Mesa}' and he_tipfac='' order by hE_ID");


if(count($Data1) != 0){

   $Details =$DB->query("SELECT  a.de_cantid as Count,ISNULL(rtrim(a.AR_CODIGO),'') AS Code,
	rtrim(A.DE_DESCRI) as Name,a.DE_PRECIO as Price,a.DE_ITBiS,a.DE_DESC,b.ar_tipo,
   ISNULL(B.AR_ITBIS,' ') AS DIMPUESTO,B.AR_VALEXI, a.DE_POSICIO as Posicion, a.de_espera as Espera
   FROM ivbddete a 
   LEFT JOIN ivbdarti b ON A.AR_CODIGO=B.AR_CODIGO
   LEFT JOIN ivbdhete c ON A.DE_factura=C.HE_factura
   where a.MA_CODIGO = '{$Mesa}' and a.de_tipfac='' and c.HE_TIPFAC='' order by de_docum,DE_ID");


   $Session->Edit('Factura',  $Data1[0]['Factura']);
   $Header = $Data1[0];
}




array_push($Data, $Header);
array_push($Data, $Details);
echo json_encode($Data);
?>
<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();
$Mesa = $Session->Get('Mesa','');

$Data =$DB->query("SELECT CONVERT(decimal(18, 2),sum(a.de_cantid)) as Cantidad,
   ISNULL(rtrim(a.AR_CODIGO),'') AS Codigo,
   rtrim(A.DE_DESCRI) as Descrip,CONVERT(decimal(18, 2), a.DE_PRECIO) as Precio,
   CONVERT(decimal(18, 2) , (a.DE_PRECIO * sum(a.de_cantid))) as Total,
   ISNULL(B.AR_ITBIS,' ') AS DIMPUESTO,B.AR_VALEXI
   FROM ivbddete a 
   LEFT JOIN ivbdarti b ON A.AR_CODIGO=B.AR_CODIGO
   LEFT JOIN ivbdhete c ON A.De_factura=C.He_factura
   where a.MA_CODIGO =  '{$Mesa}' 
	and rtrim(a.AR_CODIGO) !='' and a.de_tipfac='' and c.HE_TIPFAC=''
   group by a.AR_CODIGO,A.DE_DESCRI,a.DE_PRECIO,B.AR_ITBIS,B.AR_VALEXI");


echo json_encode($Data);





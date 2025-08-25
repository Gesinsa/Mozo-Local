<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();
$Session = new Session();

/*select  
isnull(rtrim(HE_NOMBRE),'') AS DE_NOMBRE
,CONVERT(VARCHAR(10),HE_FECENT,103) AS Fecha, RIGHT(HE_FECENT, 7) AS Hora
 from ivbdhete  where MA_CODIGO = '03' and he_tipfac='' */

$Mesa =  trim($Session->Get('Mesa', ''));


$Cart = $DB->query("SELECT ISNULL(rtrim(a.DE_DOCUM),'') as Docum,ISNULL(rtrim(a.AR_CODIGO),'') AS Code, a.de_cantid as Count ,
	rtrim(A.DE_DESCRI) as Name,a.DE_PRECIO as Price,a.DE_ITBiS as Itbis,a.DE_DESC
	FROM ivbddete a 
	LEFT JOIN ivbdarti b ON A.AR_CODIGO=B.AR_CODIGO
	LEFT JOIN ivbdhete c ON c.he_factura=a.de_factura
	where a.MA_CODIGO = '{$Mesa}' and a.de_tipfac='' order by DE_ID");


if (count($Cart) == 0) {
	$Cart = [];
}


$Data = [];

if ($Mesa == "") {
	$Depen = "";
	$Session->Edit("ShoppingCart");
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
}


if (count($Session->Get("ShoppingCart")) == 0 || floatval($Session->GetProperty('Totals', 'Total', 0)) <= 0) {
	$Session->Edit("ShoppingCart", []);
	$Session->Edit('Totals', ['Total' => 0, 'Itbis' => 0, 'Ley' => 0, 'Discount' => 0]);
}

array_push($Data, $Cart);
array_push($Data, $Session->Get("ShoppingCart"));

echo json_encode($Data);

<?php
require_once("./Class/DB.php");
require_once("./Class/Session.php");
$DB = new DB();

$Session = new Session();

$Code = $Session->Get('Article', '');
$Day = substr(strftime("%a"), 0, 2);
$Mount = date("m");
$Year = date("y");

$Data = [];
$Arti = [];
$Grupo = [];
$Blend = [];
$Guarni = [];
$Ingre = [];
$Term = [];

$ArticleConfig = $Session->GetProperty("Config", "Articuoloxdias", 0);
$GroupConfig = $Session->GetProperty("Config", "grupoxdias", 0);

$Arti = $DB->query("SELECT RTRIM(a.ar_codigo) as Code,
	a.ar_predet,a.ar_premin,a.ar_premay,rtrim(a.ar_descri) as Name,
	a.AR_DETALLE,
	CASE 
		WHEN rtrim(A.AR_OFERTA) = '' THEN 'N'
		ELSE isnull(rtrim(A.AR_OFERTA),'N')
	END AS OFERTA,
	CASE
		WHEN isnull(D.pv_desc,0) != 0  THEN 'Desc'
		WHEN isnull(D.pV_2X1,0) = 1  THEN '2x1'
		ELSE 'N'
	END AS Tipo,rtrim(a.ar_select) as ar_select,
	0 as CANTID,
	isnull(cast(D.pv_precio as int),0) as PV_PRECIO,isnull(D.pv_desc ,0) as PV_DESC,
	isnull(ar_madera,0) as  Decimales,isnull(a.ar_valint,0) as Seleccion,
	a.AR_COCINA as Cocina,
	CASE 
		WHEN a.AR_TIPOCOC = 1 THEN 'E'
			WHEN a.ar_tipococ = 2 THEN 'F'
		ELSE ''
	END AS TypeCook,
	isnull(a.AR_VALEXI, 0) AR_VALEXI
	FROM IVBDARTI A
	left join PVBDDIARTI as D on A.AR_CODIGO = D.AR_CODIGO and D.PV_DIA  ='{$Day}'
	AND GETDATE() BETWEEN D.pv_horaI AND D.pv_horaf
	WHERE A.AR_control='S' and a.ar_activado=' ' and a.cod_sucu='1'
	and A.AR_CODIGO ='{$Code}' ORDER BY A.ar_cosfob asc");


if (intval($ArticleConfig) == 1) {
	$Grupo = $DB->query("Select rtrim(A.AR_CODIGO) As Code ,rtrim(A.gr_codigo) As Code2,
	  rtrim(A.GR_DESCRI) As Name,ISNULL(rtrim(B.SUB_NOMBRE),'') AS SubName,rtrim(A.GR_GRUPO) As Grupo 
		FROM pvbdgrupart A 
		LEFT JOIN pvbdsubgrupo B ON A.gr_grupo =  B.sub_codigo 
		right JOIN IVBDARTI as c on c.ar_codigo = A.AR_CODIGO and c.AR_ACOMPA = 1 and c.AR_TIPGUAR ='G'
		where A.AR_CODIGO ='{$Code}' AND A.DE_CODIGO='{$Day}' AND A.ME_CODIGO='{$Mount}'  
    AND A.PV_ANO='{$Year}' ORDER BY A.ar_codigo,A.gr_grupo");
} else {

	if (intval($GroupConfig) == 1) {
		$Grupo = $DB->query("Select rtrim(A.AR_CODIGO) As Code ,rtrim(A.gr_codigo) As Code2,
	  rtrim(A.GR_DESCRI) As Name,ISNULL(rtrim(B.SUB_NOMBRE),'') AS SubName,rtrim(A.GR_GRUPO) As Grupo 
		FROM pvbdgrupart A 
		LEFT JOIN pvbdsubgrupo B ON A.gr_grupo = B.sub_codigo 
		right JOIN IVBDARTI as c on c.ar_codigo = A.AR_CODIGO and c.AR_ACOMPA = 1 and c.AR_TIPGUAR ='G'
		where A.AR_CODIGO ='{$Code}' AND A.DE_CODIGO='{$Day}' 
    ORDER BY A.ar_codigo,A.gr_grupo");
	} else {
		$Grupo = $DB->query("Select rtrim(A.AR_CODIGO) As Code ,rtrim(A.gr_codigo) As Code2,
	  rtrim(A.GR_DESCRI) As Name,ISNULL(rtrim(B.SUB_NOMBRE),'') AS SubName, rtrim(A.GR_GRUPO) As Grupo 
		FROM pvbdgrupart A 
		LEFT JOIN pvbdsubgrupo B ON A.gr_grupo = B.sub_codigo 
		right JOIN IVBDARTI as c on c.ar_codigo = A.AR_CODIGO and c.AR_ACOMPA = 1 and c.AR_TIPGUAR ='G'
		where A.AR_CODIGO ='{$Code}' ORDER BY A.ar_codigo,A.gr_grupo"); 
	}
}

$Type = $DB->query("Select top 1 AR_TIPGUAR from IVBDARTI WHERE ar_codigo ='{$Code}' and AR_TIPGUAR ='O'");

if (count($Type) == 1) {
	$Blend = $DB->query("Select rtrim(A.AR_CODIGO) As Code ,rtrim(A.AR_CODIGO2) As Code2,
	rtrim(B.AR_DESCRI) As Name,B.AR_PREDET,B.AR_PREMIN,B.AR_PREMAY 
	from PVBDSELECCION AS A LEFT JOIN IVBDARTI AS B ON A.AR_CODIGO2=B.AR_CODIGO 
	WHERE A.AR_CODIGO= '{$Code}' order by A.AR_CODIGO,B.AR_DESCRI");
}
//

$Guarni = $DB->query("SELECT  ltrim(rtrim(A.AR_CODIGO)) as Code, rtrim(A.ac_descri) as Name  
FROM PVBDGUARNI as A 
right JOIN IVBDARTI as c on c.ar_codigo = A.AR_CODIGO and c.AR_ACOMPA = 1
where ltrim(rtrim(A.ar_codigo))  = :Code", [":Code" => $Code]);


$Ingre = $DB->query("SELECT rtrim(A.AR_CODIGO) as Code,rtrim(B.IN_DESCRI) as Name
 FROM IVBDINGREARTI A INNER JOIN PVBDINGRE B ON A.IN_codigo=B.In_Codigo
 left JOIN IVBDARTI as c on c.ar_codigo = A.AR_CODIGO and c.AR_ACOMPA = 1 
 WHERE rtrim(A.AR_CODIGO) ='{$Code}'");


$Term = $DB->query("SELECT rtrim(A.AR_CODIGO) as Code, rtrim(B.TE_DESCRI)  as Name
 FROM IVBDARTITERMINOS A 
 INNER JOIN IVBDTERMINOS B ON A.TE_CODIGO=B.TE_CODIGO
 right JOIN IVBDARTI as c on c.ar_codigo = A.AR_CODIGO and c.AR_ACOMPA = 1 
 WHERE rtrim(A.AR_CODIGO) = '{$Code}'");

array_push($Data, $Arti[0]);
array_push($Data, $Grupo);
array_push($Data, $Blend);
array_push($Data, $Guarni);
array_push($Data, $Ingre);
array_push($Data, $Term);

echo json_encode($Data);

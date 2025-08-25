<?php
require_once("./Class/DB.php");
$DB = new DB();

$Name = isset($_POST['Name']) ? $_POST['Name'] :'';
$Day = substr(strftime("%a"), 0, 2);

$Data = $DB->query("SELECT RTRIM(a.ar_codigo)  as Code,rtrim(a.ar_descri) as Name,
	a.ar_premin,a.ar_premay,A.ar_predet,isnull(a.AR_DETALLE,'') as Detalle,
	isnull(A.AR_OFERTA,'N') AS OFERTA,
	ISNULL(STUFF(
   	(SELECT ', ' + LTRIM(RTRIM(B.AC_DESCRI))
   	FROM PVBDACOM B INNER JOIN PVBDGUARNI C ON B.AC_CODIGO = c.AC_CODIGO
   	WHERE A.AR_CODIGO = c.AR_CODIGO
   	FOR XML PATH('')), 1, 2, ''),''
	) As GUARNICIONES, RTRIM(B.ar_descri) as Depart,
	isnull(A.AR_OFERTA,'N') AS OFERTA,
	CASE
		WHEN isnull(D.pv_desc,0) != 0  THEN 'Desc'
		WHEN isnull(D.pV_2X1,0) = 1  THEN '2x1'
		ELSE 'N'
	END AS Tipo, a.ar_select,
	0 as CANTID,
	isnull(cast(D.pv_precio as int),0) as PV_PRECIO,isnull(D.pv_desc ,0) as PV_DESC 
	FROM IVBDARTI A
	right JOIN IVBDDEPT B ON A.de_CODIGO=B.de_CODIGO
	full join PVBDDIARTI as D on A.AR_CODIGO = D.AR_CODIGO and D.PV_DIA  ='{$Day}'
	AND GETDATE() BETWEEN D.pv_horaI AND D.pv_horaf
	WHERE A.AR_control='S' and a.ar_activado=' ' and a.cod_sucu='1'
	and  RTRIM(a.ar_descri) LIKE '%{$Name}%' 
	ORDER BY A.ar_cosfob asc");

echo json_encode($Data);
?>
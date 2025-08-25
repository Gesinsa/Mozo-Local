<?php
try {
	require_once("./Class/DB.php");
	require_once("./Class/Session.php");
	$DB = new DB();

	$Session = new Session();

	$Code = $Session->Get('Article', '');
	$Alma = '01';

	$Existence = 0;
	$ALMACEN = $DB->query("SELECT TOP 1 ltrim(RTRIM(isnull(AL_CODIGO, ''))) AL_CODIGO from IVBDARTI WHERE ar_codigo=:Article", [":Article"=>$Code]);

	if(count($ALMACEN) == 0){
		echo json_encode(["status" => true,"Article"=>$Code, "Almacen"=>$Alma, "Existence"=> $Existence]);
	}

	$Al_Code = $ALMACEN[0]["AL_CODIGO"];

	if(Trim($Al_Code) != ''){
		$Alma = $Al_Code;
	}

	$DB->Transaction();
	
	$Arti = $DB->query("SELECT A.*,ISNULL(B.EXIREAL,0) AS EXISTENCIA, ISNULL(B.CANVEN,0) AS CANVEN FROM IVBDARTI A
	LEFT JOIN EXISTENCIA_REAL_ALMACEN B ON A.AR_CODIGO=B.AR_CODIGO AND B.AL_CODIGO= :ALMACEN
	WHERE A.ar_codigo=:Article",[":Article"=>$Code, ":ALMACEN"=>$Alma]);


	$Offer = $DB->query("SELECT OF_CODIGO,OF_CANTID FROM IVBDOFER WHERE AR_CODIGO=:Article",[":Article"=>$Code]);
	$EXIST = 0;

	foreach ($Offer as $key => $A) {

		$CodeOffert = $A["OF_CODIGO"];

		$Arti2 = $DB->query("SELECT A.*,ISNULL(B.EXIREAL,0) AS EXISTENCIA FROM IVBDARTI A
		LEFT JOIN EXISTENCIA_REAL_ALMACEN B ON A.AR_CODIGO=B.AR_CODIGO AND B.AL_CODIGO= :ALMACEN
		WHERE A.ar_codigo= :Article",[":Article"=>$CodeOffert, ":ALMACEN"=>$Alma]);

		if(Count($Arti2) > 0)
		{
			$Exist = intval($Arti2[0]["EXISTENCIA"] / $A["OF_CANTID"]) - $Arti[0]["CANVEN"];
		}

		
		if($Exist < $Existence){
			$Existence =  $Exist;
		}
	}

	$Diff = $Arti[0]["EXISTENCIA"] - $Existence ;

	$Inventario =  $DB->query("select AL_CODIGO FROM IVBDARIL where ar_codigo=:Article AND AL_CODIGO= :ALMACEN",[":Article"=>$Code, ":ALMACEN"=>$Alma]);

	if(Count($Inventario) > 0){
		$DB->Update("UPDATE IVBDARIL set ar_inv=:Diff where ar_codigo=:Article AND AL_CODIGO= :ALMACEN", [":Article"=>$Code, ":Diff"=>$Diff, ":ALMACEN"=>$Alma]);
	}else{
		$DB->Insert("INSERT into IVBDARIL (ar_codigo,ar_inv,al_codigo) VALUES (:Code,:Diff,:ALMACEN)", [":Article"=>$Code, ":Diff"=>$Diff, ":ALMACEN"=>$Alma]);
	}


	$Arti = $DB->query("SELECT ISNULL(B.EXIREAL,0) AS EXISTENCIA FROM IVBDARTI A
	LEFT JOIN EXISTENCIA_REAL_ALMACEN B ON A.AR_CODIGO=B.AR_CODIGO AND B.AL_CODIGO=:ALMACEN
	WHERE A.ar_codigo= :Article",[":Article"=>$Code, ":ALMACEN"=>$Alma]);

	$Existence = 0;

	if(count($Arti) >0){
		$Existence = $Arti[0]["EXISTENCIA"];
	}

	$DB->Commit();
	echo json_encode(["status" => true,"Article"=>$Code, "Almacen"=>$Alma, "Existence"=> floatval($Existence)]);
} catch (PDOException $e) {
	$DB->RollBack();
	echo json_encode(["status" => false, "Details" => $e, "Error"=>$Error]);
}
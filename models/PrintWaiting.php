<?php
require_once("./Class/DB.php");
$DB = new DB();

try {


	$Dato = [];

	if ($Depen == null) {
		$Dato = $DB->query("select DE_FACTURA,de_docum,MA_CODIGO,MA_DEPEN  from IVBDDETE where de_espera = 1 
			and de_tipfac='' and (MA_CODIGO = :Mesa or MA_DEPEN = :Mesa2)", [":Mesa" => $Mesa, ":Mesa2" => $Mesa]);
	} else {
		$Dato = $DB->query("select DE_FACTURA,de_docum,MA_CODIGO,MA_DEPEN  from IVBDDETE where de_espera = 1 
		and de_tipfac=''and  MA_CODIGO = :Mesa and MA_DEPEN = :Mesa2", [":Mesa" => $Mesa, ":Mesa2" => $Mesa]);
	}


	if (count($Dato) == 0) {
		echo json_encode(array("status" => false, "Details" => "No hay pedidos en Espera"));
		return;
	}


	if ($Depen == null) {
		$DB->Update("update PVBDMESA set de_espera = 0 where MA_CODIGO = :mesa", [":mesa" => $Mesa]);
	}

	$DB->Transaction();
	foreach ($Dato as $key => $A) {
		$Fact = $A['DE_FACTURA'];
		$Docu = $A['de_docum'];
		$NMesa = $A['MA_CODIGO'];
		$NDepe = $A['MA_DEPEN'];


		$DB->Update("update PVBDMESA set de_espera = 0 where MA_CODIGO = :mesa", [":mesa" => $NMesa]);

		$DB->Update(
			"UPDATE ORDENESIMPRESION set de_espera= 0 WHERE FACTURA = :Fact and MESA = :Mesa and SECUENCIA  = :Docu",
			[":Fact" => $Fact, ":Mesa" => $NMesa, ":Docu" => $Docu]
		);

		$DB->Update("UPDATE IVBDDETE set de_espera = 0 WHERE  DE_FACTURA = :Fact and MA_CODIGO = :Mesa 
		and MA_DEPEN = :Depe and de_docum  = :Docu", 	[":Fact" => $Fact, ":Mesa" => $NMesa, ":Depe" => $NDepe, ":Docu" => $Docu]);
	}


	$DB->Commit();
	echo json_encode(array("status" => true, "Mesa" => $Mesa, "depen" => $Depen, "Data" => $Dato));
} catch (PDOException $e) {
	$DB->RollBack();
	echo json_encode(array("status" => false, "Mesa" => $Mesa, "depen" => $Depen));
}

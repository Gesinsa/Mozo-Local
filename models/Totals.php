<?php

	function Oferta($Quantity, $Value  = 2, $Price = 1)
	{
		if ($Quantity < $Value) {
			$Offer= $Quantity;
		} 
		else {
			$Residue = $Quantity % $Value;

			if ($Residue == 0) {
				$Valor =  $Quantity / $Value;
				$Offer= $Valor * $Price;
			} 
			else {
					
				$Valor = floor($Quantity / $Value);
				$Valor += $Residue;
				$Offer = $Valor * $Price;
			}
		}
		
		return $Offer;
	}

	function Itbis($DB,$Type,$CantXPrec){
		
		$Total = 0;
		$T_Itbis = 0;
		$Ley = 0;

		if($Type != "N"){
			$data = $DB->query("SELECT  ITBIS,ITBIS1,D_LEY FROM FABDPROC");
			$data = $data[0];

			if($Type == "S"){
				$_itbis = $data["ITBIS"];
				//$T_Itbis = $CantXPrec - ($CantXPrec / (1 + ($_itbis/100)));
				$T_Itbis = $CantXPrec *  ($_itbis/100);
			}

			if($Type == "T"){
				$_itbis = $data["ITBIS1"];
				$T_Itbis = $CantXPrec *  ($_itbis/100);
			}		

		}
      
		$data = $DB->query("SELECT D_LEY FROM FABDPROC");
		$data = $data[0];
		
		$D_Ley = $data['D_LEY'];
		$Ley =  $CantXPrec * ($D_Ley/100);

		$T_Itbis  =  number_format($T_Itbis , 2, '.', '');

		

		$Total =  $CantXPrec ;
		$Ley = number_format($Ley, 2, '.', '');

		//$Total = $Ley; 
		$Total = number_format($Total, 2, '.', '');

		$array  = array("Itbis" => $T_Itbis , "Total" => $Total,'Ley'=>$Ley);

		return $array;
	}	
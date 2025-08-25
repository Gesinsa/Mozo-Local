<?php
class Session
{
	private $NameSession;

	public function __construct()
	{
		session_start(['cookie_lifetime' => 72000]);
		$Dir = '';

		$Explo = explode('/', dirname($_SERVER["PHP_SELF"]));

		if (count($Explo) > 1) {

			if ($Explo[0] == "") {
				$Dir = $Explo[1];
			} else {
				$Dir = $Explo[0];
			}
		} else {
			$Dir = str_replace('/', '', $Dir);
		}


		$this->NameSession = $Dir;

		if (!isset($_SESSION[$this->NameSession])) {
			$_SESSION[$this->NameSession] = [];
		}
	}


	public  function Edit($Property, $Value = [], $Default = [])
	{
		$Data_ = $this->NameSession;

		if (!isset($Value) && $Value != []) {
			$_SESSION[$Data_][$Property] = $Default;
		} else {
			$_SESSION[$Data_][$Property] = $Value;
		}
	}

	public function EditProperty($Property, $Property2 = null, $Value = [], $Default = [])
	{
		$Data_ = $this->NameSession;


		$Data = (!isset($Value) && $Value != []) ? $Default :   $Value;

		if (isset($Property2)) {
			$_SESSION[$Data_][$Property][$Property2] = $Data;
		} else {
			$_SESSION[$Data_][$Property] = $Data;
		}
	}

	public  function Get($Property, $Default = [])
	{
		$Data_ = $this->NameSession;

		$Session = $_SESSION[$Data_][$Property];

		if (!isset($Session)) {
			$Session = $Default;
		}

		return $Session;
	}

	public function GetProperty($Property, $Property2 = null, $Default = [])
	{
		$Data_ = $this->NameSession;
		$Session = [];

		if (!isset($Property2)) {
			$Session = $_SESSION[$Data_][$Property];

			if (!isset($Session)) {
				$Session = $Default;
			}
			return $Session;
		}

		if (is_array($Property2)) {
			if (is_array($Default) && count($Default) != 0) {
				foreach ($Property2 as $key => $value) {
					$Values = $_SESSION[$Data_][$Property]["{$value}"];

					if (!isset($Values)) {
						$Values = $Default["{$value}"];
					}

					$Session = array_merge($Session, ["{$value}" => $Values]);
				}

				return $Session;
			}

			foreach ($Property2 as $key => $value) {
				$Values = $_SESSION[$Data_][$Property]["{$value}"];

				if (!isset($Values)) {
					$Values = $Default;
				}

				$Session = array_merge($Session, ["{$value}" => $Values]);
			}
			return $Session;
		}

		$Session =  $_SESSION[$Data_][$Property][$Property2];

		if (!isset($Session)) {
			$Session = $Default;
		}

		return $Session;
	}


	public  function Delete()
	{
		$Data_ = $this->NameSession;
		unset($_SESSION[$Data_]);
	}

	public  function DeleteProperty($Property = "", $Property2 = null)
	{
		$Data_ = $this->NameSession;

		if (isset($Property2)) {
			if (is_array($Property2)) {
				foreach ($Property2 as $key => $value) {
					unset($_SESSION[$Data_][$Property]["{$value}"]);
				}
			} else {
				unset($_SESSION[$Data_][$Property][$Property2]);
			}
		} else {
			unset($_SESSION[$Data_][$Property]);
		}
	}



	public function Name()
	{
		return $this->NameSession;
	}
}

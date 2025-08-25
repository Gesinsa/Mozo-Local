<?php

class Auth
{

    private $User;
    private $Pwd;

    public function Data()
    {
        require_once "./Class/DB.php";
        $DB = new DB();

        $array = [":pin" => $this->Pwd];

        if (trim($this->Pwd) == "") {
            return [];
        }

        $Data = $DB->query("SELECT top 1 rtrim(MO_DESCRI) as nombre,MO_ACCESO as sc,
        rtrim(MO_CODIGO) as codigo, rtrim(MO_DESDE) as ini, rtrim(MO_HASTA) as fin,
        ISNULL(mo_imprimetableta,0) as Prints
        FROM PVBDMOZO Where ltrim(rtrim(MO_CLAVE)) = :pin", $array);

        return (count($Data) != 0) ?  $Data[0] : [];
    }


    public function Login($User, $Pass)
    {
        $this->User = $User;
        $this->Pwd = isset($Pass) ? $Pass : "";
    }

    public static function LogOut()
    {
        require_once("./Class/Session.php");
        $Session = new Session();
        $Session->Delete();
        header('Location: ./');
    }
}

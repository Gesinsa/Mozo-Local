<?php
class SSID
{
    private $SSID;
    function Validate()
    {
        global $Dir_;
        require_once("./Class/Session.php");
        $Session = new Session();

        $this->SSID = $Session->GetProperty("UserData", "Session_ID", null);
        if ($_SERVER['REQUEST_URI'] == $Dir_) {
            if (session_id() == $this->SSID) {
                $Formato = $Session->GetProperty("Config", "Formato", 0);
                if (intval($Formato) == 4) {
                    header("Location: Mesa");
                } else {
                    header("Location: Areas");
                }
            }
        } else {

            if (!isset($this->SSID)) {
                require_once("./Auth/Auth.php");
                Auth::logOut();
            }

            if (session_id() != $this->SSID && $_SERVER['REQUEST_URI'] != "/Log") {
                require_once("./Auth/Auth.php");
                Auth::logOut();
            }
        }
    }
}

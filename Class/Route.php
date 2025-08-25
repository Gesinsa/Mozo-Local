<?php
class Route {
  // *Checks if the current route is valid. Checks the route
  public static function isRouteValid() {
    global $Routes;

    $uri = $_SERVER['REQUEST_URI'];
    return in_array(explode('?',$uri)[0], $Routes);
  }

  // Insert the route into the $Routes array.
  private static function registerRoute($route) {
    global $Routes;
    global $Dir_;
    $Routes[] = $Dir_.$route;
  }

  // This method creates dynamic routes.
  private static function dyn($dyn_routes) {
    global $Dir_;

    $route = explode($Dir_, $dyn_routes);
    $Url = explode($Dir_, substr($_SERVER['REQUEST_URI'], strlen("/")-1));

    for ($i = 0; $i < count($route); $i++) {
      if ($i+1 <= count($Url)-1) {
        $route[$i] = str_replace("<$i>", $Url[$i+1], $route[$i]);
      }
    }

    return implode($Dir_,$route);
  }

  // Register the route and run the closure using __invoke().
  public static function Get($route, $closure) {
    global $Dir_;

    if ($_SERVER["REQUEST_METHOD"] == "GET"){

      $Url = isset($_GET['url']) ? $_GET['url'] :[];

      if ($_SERVER['REQUEST_URI'] == $Dir_.$route) {
        self::registerRoute($route);
        $closure->__invoke();
      } 

      else if (explode('?', $_SERVER['REQUEST_URI'])[0] === $Dir_.$route) {
        self::registerRoute($route);
        $closure->__invoke();
      } 
      /*else if ($Url === explode('/', $route)[0]) {
        self::registerRoute(self::dyn($route));
        $closure->__invoke();
      }*/
    }
  }

  public static function Post($route, $closure){
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
      require_once("./Class/Session.php");
      $Session = new Session();
      global $Dir_;
      $Uri = $_SERVER['REQUEST_URI'];

      $SSID = $Session->GetProperty("UserData", "Session_ID", null);

      if (session_id() != $SSID && $Uri != $Dir_ . "Login" && $_SERVER['REQUEST_URI'] != $Dir_ . "LogOut") {
        echo json_encode([]);
      } else {

        if ($_SERVER['REQUEST_URI'] == $Dir_ . $route) {
          self::registerRoute($route);
          $closure->__invoke();
        }
      }
    }
  }
}
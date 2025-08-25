<?php
class View {


  public static function make($view) {
    if (Route::isRouteValid()) {
      $File = './views/'.$view.'.php';

      if(!file_exists($File)){
        die("<h1 style=\"text-align: center;\">View ".$view.".php Not Exists</h1>");
      }

      require_once($File);
      return 1;
    }
  }

}
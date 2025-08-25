<?php
class App {

  public function getRoute() {
    global $Routes;
    global $Dir_; 

    $Dir = str_replace("/",'',$Dir_);
    $uri = explode('?',$_SERVER['REQUEST_URI'])[0];
    $uri = ($uri ==  '/'.$Dir) ? $uri .'/' : $uri;
    $Uri2 = explode('/',$uri);

     
    if(!in_array($Dir,$Uri2)){
      die("<h1 style=\"text-align: center;\">El direcrtorio no coresponde a ".$Dir."</h1>");
    }

    if (!in_array($uri, $Routes)) {
      die("<h1 style=\"text-align: center;\">Rute Invalid ".$uri."</h1>
      <h2 style=\"text-align: center;\">Rute Of Server ".$Dir_."</h2>
      ");
    }
  }

  public function run() {
    $this->getRoute();
  }

}
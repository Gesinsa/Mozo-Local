<?php
class DB {
  private $pdo;

  public function __construct()
  {
  
    $Host = "192.168.88.222"; 
    $DB="FACFOXSQL";
    $User = "sa";
    $Psw = 'pr0i$$a';
    
    $this->pdo = new PDO("sqlsrv:server=$Host; DATABASE=$DB;", $User, $Psw);
    $this->pdo->query("SET NAMES latin1");
    $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function query($query, $params = array()) {
    $Data = $this->pdo->prepare($query);
    $Data->execute($params);
    $Data= $Data->fetchAll(PDO::FETCH_ASSOC);  
    return $Data;
  }

  public function Update($query, $params = array()) {
    $Data = $this->pdo->prepare($query);
    $Data->execute($params);
  }

  public function Insert($query, $params = array()) {
    $Data = $this->pdo->prepare($query);
    $Data->execute($params);
  }

  public function Transaction(){
   $this->pdo->beginTransaction();
  }

  public function Commit(){
    $this->pdo->commit();
  }

  public function RollBack(){
    $this->pdo->rollBack();
  }
}
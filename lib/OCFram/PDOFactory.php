<?php
namespace OCFram;
 
class PDOFactory
{
  public static function getMysqlConnexion()
  {
    $db = new \PDO('mysql:host=localhost;dbname=news', 'root', 'root');
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);//D�sormais, toutes les requ�tes SQL qui comportent des erreurs les afficheront avec un message d'erreur
 
    return $db;
  }
}
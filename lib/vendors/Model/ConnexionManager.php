<?php
namespace Model;

use \OCFram\Manager;

abstract class ConnexionManager extends Manager
{

    abstract public function FindUser($login,$password,$idGroupe);
}
<?php
namespace OCFram;

use Model\MemberManagerPDO;

class AlreadyExistsValidator extends Validator
{
    public function __construct($errorMessage)
    {
        parent::__construct($errorMessage);
    }

    public function isValid($value)
    {
        $dao=new PDOFactory;
        $connexion=$dao::getMysqlConnexion();

        $rmpdo=new MemberManagerPDO($connexion);
        return !$rmpdo->FindUser($value);
    }

}
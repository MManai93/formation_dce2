<?php
namespace OCFram;

use Model\MemberManagerPDO;

class PasswordValidator extends Validator
{
    protected $loginfield;
    public function __construct($errorMessage, HiddenField $Loginfield)
    {
        parent::__construct($errorMessage);
        $this->setHiddenField($Loginfield);
    }

    public function isValid($value)
    {
        $dao=new PDOFactory;
        $connexion=$dao::getMysqlConnexion();

        $rmpdo=new MemberManagerPDO($connexion);
        $array_pass=$rmpdo->FindPassword($this->loginfield->value());
        return sha1($value)==$array_pass['NMC_password'];
    }

    public function setHiddenField(HiddenField $Loginfield)
    {
        $this->loginfield=$Loginfield;
    }

}
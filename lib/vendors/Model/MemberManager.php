<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Member;

abstract class MemberManager extends Manager
{
    abstract public function add(Member $registration);

    abstract public function FindUser($login);

    public function save(Member $Membre)
    {
        if ($Membre->isValid())
        {
            $Membre->isNew()? $this->add($Membre) : $this->modify($Membre);
        }
        else
        {
            throw new \RuntimeException('L\'inscription doit être validée pour être enregistrée');
        }
    }


}
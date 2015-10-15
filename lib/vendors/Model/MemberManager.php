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

    abstract public function modifyLogin($login,$idMembre);

    abstract public function modifyPassword($password,$idMembre);

    abstract public function delete($idMembre);

    abstract public function FindPassword($loginMembre);

    abstract public function getListNews($idMembre);

    abstract public function getListComments($idMembre);

    abstract public function countNews($idMembre);

    abstract public function countComments($idMembre);

    abstract public function getListMember();

    abstract public function count();

    abstract protected function modify(Member $member);

    abstract public function getUnique($idMembre);
}
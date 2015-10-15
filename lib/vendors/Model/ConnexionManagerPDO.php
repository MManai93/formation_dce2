<?php
namespace Model;

class ConnexionManagerPDO extends ConnexionManager
{
    public function FindUser($login,$password,$idGroupe)
    {
        $q = $this->dao->prepare('SELECT NMC_id as id , NMC_login as login, NMC_password as password, NMC_email as email, NMC_fk_NMY as groupe_id
                                  FROM T_NEW_memberc
                                  WHERE NMC_login = :login AND NMC_password = :password AND NMC_fk_NMY = :idGroupe');
        $q->bindValue(':login',$login);
        $q->bindValue(':password',$password);
        $q->bindValue(':idGroupe',$idGroupe,\PDO::PARAM_INT);
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');
        return $q->fetch();
    }

}
<?php
namespace Model;

use \Entity\Member;

class MemberManagerPDO extends MemberManager
{
    protected static $GROUP_ID = 2;

    public function add(Member $membre)
    {
        $requete = $this->dao->prepare('INSERT INTO T_NEW_memberc(NMC_login,NMC_password,NMC_email,NMC_dateregistration,NMC_fk_NMY) VALUES (:login,:password,:email,NOW(),:idGroupe)');

        $requete->bindValue(':login', $membre->login());
        $requete->bindValue(':password', sha1($membre->password()));
        $requete->bindValue(':email', $membre->email());
        $requete->bindValue(':idGroupe', self::$GROUP_ID, \PDO::PARAM_INT);

        $requete->execute();
    }

    public function modifyLogin($login,$idMembre)
    {
        $requete = $this->dao->prepare('UPDATE T_NEW_memberc SET NMC_login=:login WHERE NMC_id = :idMembre');

        $requete->bindValue(':login', $login);
        $requete->bindValue(':idMembre', $idMembre, \PDO::PARAM_INT);
        $requete->execute();
    }

    public function modifyPassword($password,$idMembre)
    {
        $requete = $this->dao->prepare('UPDATE T_NEW_memberc SET NMC_password=:password WHERE NMC_id = :idMembre');

        $requete->bindValue(':password', $password);
        $requete->bindValue(':idMembre', $idMembre, \PDO::PARAM_INT);
        $requete->execute();
    }

    public function delete($idMembre)
    {
        $this->dao->exec('DELETE FROM T_NEW_memberc WHERE NMC_id = '.(int) $idMembre);
    }


    public function FindUser($login)
    {
        $requete = $this->dao->prepare('SELECT NMC_login FROM T_NEW_memberc WHERE NMC_login=:login');

        $requete->bindValue(':login', $login);
        $requete->execute();
        return $requete->fetch();
    }

    public function FindPassword($loginMembre)
    {
        $requete = $this->dao->prepare('SELECT NMC_password FROM T_NEW_memberc WHERE NMC_login=:loginMembre');

        $requete->bindValue(':loginMembre', $loginMembre);
        $requete->execute();
        return $requete->fetch();
    }

    public function getListNews($idMembre)
    {
        $sql = 'SELECT NAC_title as title, NAC_content as content, NAC_dateadd as dateAdd, NAC_datemodif as dateModif, NAC_id as id
                FROM t_new_articlec
                INNER JOIN T_NEW_memberc ON NAC_fk_NMC=NMC_id
                WHERE NMC_id=:idMembre
                ORDER BY NAC_datemodif DESC';
        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':idMembre',$idMembre);

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
        $requete->execute();
        $listeNews = $requete->fetchAll();

        foreach ($listeNews as $news)
        {
            date_default_timezone_set('Europe/Paris');
            $news->setDateAdd(new \DateTime($news->dateAdd()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $requete->closeCursor();

        return $listeNews;
    }

    public function getListComments($idMembre)
    {
        $sql = 'SELECT NCC_fk_NAC as news_id, NMC_login as member_login, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif
                FROM t_new_commentc
                INNER JOIN T_NEW_memberc ON NCC_fk_NMC=NMC_id
                WHERE NMC_id=:idMembre
                ORDER BY NCC_dateadd DESC';
        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':idMembre',$idMembre);

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
        $requete->execute();
        $listeComments = $requete->fetchAll();

        foreach ($listeComments as $comment)
        {
            date_default_timezone_set('Europe/Paris');
            $comment->setDateAdd(new \DateTime($comment->dateAdd()));
            $comment->setDateModif(new \DateTime($comment->dateModif()));
        }

        $requete->closeCursor();

        return $listeComments;
    }

    public function countNews($idMembre)
    {
        $requete=$this->dao->prepare('SELECT COUNT(*) FROM t_new_articlec INNER JOIN T_NEW_memberc ON NAC_fk_NMC=NMC_id WHERE NMC_id=:idMembre');
        $requete->bindValue(':idMembre',$idMembre);
        $requete->execute();

        return $requete->fetchColumn();
    }

    public function countComments($idMembre)
    {
        $requete=$this->dao->prepare('SELECT COUNT(*) FROM t_new_commentc INNER JOIN T_NEW_memberc ON NCC_fk_NMC=NMC_id WHERE NMC_id=:idMembre');
        $requete->bindValue(':idMembre',$idMembre);
        $requete->execute();

        return $requete->fetchColumn();
    }

    public function getListMember()
    {
        $sql = 'SELECT NMC_id as id, NMC_login as login, NMC_dateregistration as dateRegistration , NMC_email as email FROM T_NEW_memberc ORDER BY NMC_id';

        $requete = $this->dao->query($sql);
       $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        $listeMember = $requete->fetchAll();

        foreach ($listeMember as $member)
        {
            date_default_timezone_set('Europe/Paris');
            $member->setDateRegistration(new \DateTime($member->dateRegistration()));
        }
        $requete->closeCursor();

        return $listeMember;
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM T_NEW_memberc')->fetchColumn();
    }

    protected function modify(Member $member)
    {
        $requete = $this->dao->prepare('UPDATE T_NEW_memberc SET NMC_email = :email, NMC_birthday = :birthday, NMC_adress = :adress, NMC_city = :city, NMC_country = :country WHERE NMC_id = :id');

        $requete->bindValue(':email', $member->email());
        $requete->bindValue(':birthday', $member->birthday());
        $requete->bindValue(':adress', $member->adress());
        $requete->bindValue(':city', $member->city());
        $requete->bindValue(':country', $member->country());
        $requete->bindValue(':id', $member->id(), \PDO::PARAM_INT); // setter avec getattribute('id_user') dans controller ou request->getdata('id')

        $requete->execute();
    }

    /**
     * @param $idMembre
     * @return Member
     */
    public function getUnique($idMembre)
    {
        $requete = $this->dao->prepare('SELECT NMC_id as id, NMC_login as login, NMC_email as email, NMC_dateregistration as dateRegistration, NMC_birthday as birthday, NMC_adress as adress, NMC_city as city, NMC_country as country
                                        FROM T_NEW_memberc
                                        WHERE NMC_id = :idMembre');
        $requete->bindValue(':idMembre', (int) $idMembre, \PDO::PARAM_INT);

//        if ($membre = $requete->fetch())
//        {
//            return $membre;
//        }
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');
        $requete->execute();
        return $requete->fetch();


    }
}
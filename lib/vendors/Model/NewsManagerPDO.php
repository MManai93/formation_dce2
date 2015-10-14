<?php
namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
    protected function add(News $news)
    {
        $requete = $this->dao->prepare('INSERT INTO t_new_articlec SET NAC_fk_NMC = :member_id, NAC_title = :title, NAC_content = :content, NAC_dateadd = NOW(), NAC_datemodif = NOW()');

        $requete->bindValue(':member_id', $news->member_id());
        $requete->bindValue(':title', $news->title());
        $requete->bindValue(':content', $news->content());

        $requete->execute();

        $newsId=$this->dao->lastInsertId();

        $tagsArray = $news->tags();

        foreach ($tagsArray as $tag) {
            $requete = $this->dao->prepare('INSERT INTO t_new_keywordc (NKC_word) VALUES (:tag)');
            $requete->bindValue(':tag', $tag);
            $requete->execute();
            $idTag = (int)$this->dao->lastInsertId();
            $requete = $this->dao->prepare('INSERT INTO t_new_keywordd (NKD_fk_NKC,NKD_fk_NAC) VALUES (:idTag,:newId)');
            $requete->bindValue(':idTag', $idTag);
            $requete->bindValue(':newId', $newsId);
            $requete->execute();
        }
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM t_new_articlec')->fetchColumn();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM t_new_articlec WHERE NAC_id = ' . (int)$id);
    }

    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT NAC_id as id, NAC_fk_NMC as member_id, NMC_login as member_login, NAC_title as title, NAC_content as content, NAC_dateadd as dateAdd ,NAC_datemodif as dateModif FROM t_new_articlec INNER JOIN t_new_memberc ON NAC_fk_NMC=NMC_id ORDER BY NAC_id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $requete->fetchAll();

        foreach ($listeNews as $news) {
            date_default_timezone_set('Europe/Paris');
            $news->setDateAdd(new \DateTime($news->dateAdd()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $requete->closeCursor();

        return $listeNews;
    }

    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT NAC_id as id, NAC_fk_NMC as member_id, NMC_login as member_login, NAC_title as title, NAC_content as content, NAC_dateadd as dateAdd ,NAC_datemodif as dateModif FROM t_new_articlec INNER JOIN t_new_memberc ON NAC_fk_NMC=NMC_id WHERE NAC_id = :id');
        $requete->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        if ($news = $requete->fetch()) {
            date_default_timezone_set('Europe/Paris');
            $news->setDateAdd(new \DateTime($news->dateAdd()));
            $news->setDateModif(new \DateTime($news->dateModif()));

            return $news;
        }

        return null;
    }

    protected function modify(News $news)
    {
        $requete = $this->dao->prepare('UPDATE t_new_articlec SET NAC_fk_NMC = :member_id, NAC_title = :title, NAC_content = :content, NAC_datemodif = NOW() WHERE NAC_id = :id');

        $requete->bindValue(':member_id', $news->member_id());
        $requete->bindValue(':title', $news->title());
        $requete->bindValue(':content', $news->content());
        $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function getTagsOf($news)
    {
        $requete = $this->dao->prepare('SELECT NKC_word as tag FROM t_new_keywordc INNER JOIN t_new_keywordd ON NKD_fk_NKC=NKC_id WHERE NKD_fk_NAC=:news');
        $requete->bindValue(':news', $news, \PDO::PARAM_INT);
        $requete->execute();

        return $requete->fetchAll();
    }

}
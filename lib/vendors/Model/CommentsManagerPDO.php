<?php
namespace Model;
 
use \Entity\Comment;
 
class CommentsManagerPDO extends CommentsManager
{
  protected function add(Comment $comment)
  {
    $q = $this->dao->prepare('INSERT INTO t_new_commentc
                              SET NCC_fk_NAC = :news_id, NCC_ghostauthor = :ghost_author, NCC_ghostemail = :ghost_email, NCC_content=:content, NCC_fk_NMC=:member_id, NCC_dateadd =NOW(), NCC_datemodif=NOW()');
 
    $q->bindValue(':news_id', $comment->news_id(), \PDO::PARAM_INT);
    $q->bindValue(':ghost_author', $comment->ghost_author());
    $q->bindValue(':ghost_email', $comment->ghost_email());
    $q->bindValue(':content', $comment->content());
    $q->bindValue(':member_id', $comment->member_id(), \PDO::PARAM_INT);

    $q->execute();
 
    $comment->setId($this->dao->lastInsertId());
  }
 
  public function delete($id)
  {
    $this->dao->exec('DELETE FROM t_new_commentc WHERE NCC_id = '.(int) $id);
  }
 
  public function deleteFromNews($news_id)
  {
    $this->dao->exec('DELETE FROM t_new_commentc WHERE NCC_fk_NAC = '.(int) $news_id);
  }
 
  public function getListOf($news, $debut = -1, $limite = -1)
  {
    if (!ctype_digit($news))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }
    $sql='SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif, NCC_fk_NMC as member_id, NMC_login as member_login, NMC_email as member_email
          FROM t_new_commentc
          LEFT OUTER JOIN t_new_memberc ON NCC_fk_NMC=NMC_id
          WHERE NCC_fk_NAC = :news
          ORDER BY NCC_id DESC';
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
    }

    $q = $this->dao->prepare($sql);
    $q->bindValue(':news', $news, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    $listcomment = $q->fetchAll();
 
    foreach ($listcomment as $Comment)
    {
      date_default_timezone_set('Europe/Paris');
      $Comment->setDateAdd(new \DateTime($Comment->dateAdd()));
      $Comment->setDateModif(new \DateTime($Comment->dateModif()));
    }
    return array_reverse($listcomment);// ou faire ca en sql ?
  }

  public function countComments($news)
  {
    if (!ctype_digit($news))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }

    $q=$this->dao->prepare('SELECT COUNT(*)
                          FROM t_new_commentc
                          WHERE NCC_fk_NAC = :news');
    $q->bindValue(':news', $news, \PDO::PARAM_INT);
    $q->execute();
    return $q->fetchColumn();
  }
 
  protected function modify(Comment $comment)
  {
    $q = $this->dao->prepare('UPDATE t_new_commentc
                              SET NCC_fk_NMC = :member_id, NCC_content = :content, NCC_datemodif=NOW()
                              WHERE NCC_id = :id');
 
    $q->bindValue(':member_id', $comment->member_id(), \PDO::PARAM_INT);
    $q->bindValue(':content', $comment->content());
    $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);
 
    $q->execute();
  }
 
  public function get($id)
  {
    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif, NCC_fk_NMC as member_id, NMC_login as member_login, NMC_email as member_email
                              FROM t_new_commentc
                              LEFT OUTER JOIN t_new_memberc ON NCC_fk_NMC=NMC_id
                              WHERE NCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    return $q->fetch();
  }

  public function getNews($id)
  {
    $q = $this->dao->prepare('SELECT NCC_fk_NAC as id
                              FROM t_new_commentc
                              WHERE NCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

    return $q->fetch();
  }

  public function getGhostAuthor()
  {
    $q=$this->dao->query('SELECT NCC_ghostauthor as ghost_author
                          FROM t_new_commentc');
    return $q->fetchColumn();
  }

  public function getCommentGhostAuthor($ghostauthor)
  {
    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif
                              FROM t_new_commentc
                              WHERE NCC_ghostauthor = :ghost_author');
    $q->bindValue(':ghost_author',$ghostauthor);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

    $listcomment = $q->fetchAll();

    foreach ($listcomment as $Comment)
    {
      date_default_timezone_set('Europe/Paris');
      $Comment->setDateAdd(new \DateTime($Comment->dateAdd()));
      $Comment->setDateModif(new \DateTime($Comment->dateModif()));
    }
    return $listcomment;

  }

  public function countCommentGhostAuthor($ghostauthor)
  {
    $q = $this->dao->prepare('SELECT COUNT(*)
                              FROM t_new_commentc
                              WHERE NCC_ghostauthor =:ghost_author');

    $q->bindValue(':ghost_author',$ghostauthor);
    $q->execute();
    return $q->fetchColumn();
  }

  public function getListOfAfter($news_id,$comment_id_last)
  {
    if (!ctype_digit($news_id))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }

    if (!ctype_digit($comment_id_last))
    {
      throw new \InvalidArgumentException('L\'identifiant de du commentaire passé doit être un nombre entier valide');
    }

    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif, NCC_fk_NMC as member_id, NMC_login as member_login, NMC_email as member_email
                              FROM t_new_commentc
                              LEFT OUTER JOIN t_new_memberc ON NCC_fk_NMC=NMC_id
                              WHERE NCC_fk_NAC = :news_id AND NCC_id > :comment_id_last
                              ORDER BY NCC_id');
    $q->bindValue(':news_id', $news_id, \PDO::PARAM_INT);
    $q->bindValue(':comment_id_last', $comment_id_last, \PDO::PARAM_INT);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

    $listcomment = $q->fetchAll();

    foreach ($listcomment as $Comment)
    {
      date_default_timezone_set('Europe/Paris');
      $Comment->setDateAdd(new \DateTime($Comment->dateAdd()));
      $Comment->setDateModif(new \DateTime($Comment->dateModif()));
    }
    return $listcomment;

  }

  public function getListOfBefore($news_id,$comment_id_old)
  {
    if (!ctype_digit($news_id))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }

    if (!ctype_digit($comment_id_old))
    {
      throw new \InvalidArgumentException('L\'identifiant de du commentaire passé doit être un nombre entier valide');
    }

    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif, NCC_fk_NMC as member_id, NMC_login as member_login, NMC_email as member_email
                              FROM t_new_commentc
                              LEFT OUTER JOIN t_new_memberc ON NCC_fk_NMC=NMC_id
                              WHERE NCC_fk_NAC = :news_id AND NCC_id < :comment_id_old
                              ORDER BY NCC_id DESC');
    $q->bindValue(':news_id', $news_id, \PDO::PARAM_INT);
    $q->bindValue(':comment_id_old', $comment_id_old, \PDO::PARAM_INT);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

    $listcomment = $q->fetchAll();

    foreach ($listcomment as $Comment)
    {
      date_default_timezone_set('Europe/Paris');
      $Comment->setDateAdd(new \DateTime($Comment->dateAdd()));
      $Comment->setDateModif(new \DateTime($Comment->dateModif()));
    }
    return $listcomment;

  }

}
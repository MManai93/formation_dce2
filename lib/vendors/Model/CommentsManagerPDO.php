<?php
namespace Model;
 
use \Entity\Comment;
 
class CommentsManagerPDO extends CommentsManager
{
  protected function add(Comment $comment)
  {
    $q = $this->dao->prepare('INSERT INTO t_new_commentc SET NCC_fk_NAC = :news_id, NCC_ghostauthor = :ghost_author, NCC_ghostemail = :ghost_email, NCC_content=:content, NCC_fk_NMC=:member_id, NCC_dateadd =NOW(), NCC_datemodif=NOW()');
 
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
 
  public function getListOf($news)
  {
    if (!ctype_digit($news))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }
 
    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif, NCC_fk_NMC as member_id, NMC_login as member_login, NMC_email as member_email FROM t_new_commentc INNER JOIN t_new_memberc ON NCC_fk_NMC=NMC_id WHERE NCC_fk_NAC = :news');
    $q->bindValue(':news', $news, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    $listcomment = $q->fetchAll();
 
    foreach ($listcomment as $Comment)
    {
      date_default_timezone_set('Europe/Paris');
      $Comment->setDateAdd(new \DateTime($Comment->dateadd()));
      $Comment->setDateModif(new \DateTime($Comment->dateModif()));
    }
    return $listcomment;
  }
 
  protected function modify(Comment $comment)
  {
    $q = $this->dao->prepare('UPDATE t_new_commentc SET NCC_fk_NMC = :member_id, NCC_content = :content WHERE NCC_id = :id');
 
    $q->bindValue(':member_id', $comment->member_id(), \PDO::PARAM_INT);
    $q->bindValue(':content', $comment->content());
    $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);
 
    $q->execute();
  }
 
  public function get($id)
  {
    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif, NCC_fk_NMC as member_id, NMC_login as member_login, NMC_email as member_email FROM t_new_commentc INNER JOIN t_new_memberc ON NCC_fk_NMC=NMC_id WHERE NCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    return $q->fetch();
  }

  public function getNews($id)
  {
    $q = $this->dao->prepare('SELECT NCC_fk_NAC as id FROM t_new_commentc WHERE NCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

    return $q->fetch();
  }

  public function getGhostAuthor()
  {
    $q=$this->dao->query('SELECT NCC_ghostauthor as ghost_author FROM t_new_commentc');
    return $q->fetchColumn();
  }

  public function getCommentGhostAuthor($ghostauthor)
  {
    $q = $this->dao->prepare('SELECT NCC_id as id, NCC_fk_NAC as news_id, NCC_ghostauthor as ghost_author, NCC_ghostemail as ghost_email, NCC_content as content, NCC_dateadd as dateAdd, NCC_datemodif as dateModif FROM t_new_commentc WHERE NCC_ghostauthor = :ghostauthor');
    $q->bindValue(':ghost_author',$ghostauthor);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

    $listcomment = $q->fetchAll();

    foreach ($listcomment as $Comment)
    {
      date_default_timezone_set('Europe/Paris');
      $Comment->setDateAdd(new \DateTime($Comment->dateadd()));
      $Comment->setDateModif(new \DateTime($Comment->dateModif()));
    }
    return $comments;;

  }

  public function countCommentGhostAuthor($ghostauthor)
  {
    $q = $this->dao->query('SELECT COUNT(*) FROM t_new_commentc WHERE NCC_ghostauthor = :ghostauthor');
    $q->bindValue(':ghost_author',$ghostauthor);
    $q->execute();
    return $q->fetchColumn();
  }

}
<?php
namespace Entity;
 
use \OCFram\Entity;
 
class Comment extends Entity implements \JsonSerializable
{
  protected $news_id,
            $ghost_author,
            $ghost_email,
            $content,
            $dateAdd,
            $dateModif,
            $member_id,
            $member_login,
            $member_email;
 
  const AUTEUR_INVALIDE = 1;
  const CONTENU_INVALIDE = 2;
  const EMAIL_INVALIDE = 2;

  public function isValid()
  {
    return !empty($this->content) && (!empty($this->member_id) || !(empty($this->ghost_email) || empty($this->ghost_author)));
  }
 
  public function setNews_id($news_id)
  {
    $this->news_id = (int) $news_id;
  }
 
  public function setGhost_author($ghost_author)
  {
    if(!is_string($ghost_author) || empty($ghost_author))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }
    $this->ghost_author = $ghost_author;
  }

  public function setGhost_email($ghost_email)
  {
    if(!is_string($ghost_email) || empty($ghost_email))
    {
      $this->erreurs[] = self::EMAIL_INVALIDE;
    }

    $this->ghost_email = $ghost_email;
  }
 
  public function setContent($content)
  {
    if (!is_string($content) || empty($content))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }
 
    $this->content = $content;
  }
 
  public function setDateAdd(\DateTime $dateAdd)
  {
    $this->dateAdd = $dateAdd;
  }

  public function setDateModif(\DateTime $dateModif)
  {
    $this->dateModif = $dateModif;
  }

  public function setMember_id($member_id)
  {
    $this->member_id=$member_id;
  }

  public function setMember_login($member_login)
  {
    $this->member_login=$member_login;
  }

  public function setMember_email($member_email)
  {
    $this->member_email=$member_email;
  }

  public function news_id()
  {
    return $this->news_id;
  }

  public function ghost_author()
  {
    return $this->ghost_author;
  }

  public function ghost_email()
  {
    return $this->ghost_email;
  }

  public function content()
  {
    return $this->content;
  }

  public function dateAdd()
  {
    return $this->dateAdd;
  }

  public function dateModif()
  {
    return $this->dateModif;
  }

  public function member_id()
  {
    return $this->member_id;
  }

  public function member_login()
  {
    return $this->member_login;
  }

  public function member_email()
  {
    return $this->member_email;
  }

  public function jsonSerialize()//mettre format date ici
  {
    return array ('comment_id'=>$this->id, 'news_id'=>$this->news_id, 'ghost_author'=>$this->ghost_author, 'ghost_email'=>$this->ghost_email, 'content'=> $this->content, 'dateAdd'=>$this->dateAdd,
                  'dateModif'=>$this->dateModif, 'member_id'=>$this->member_id, 'member_login'=>$this->member_login, 'member_email'=>$this->member_email );
  }

}
<?php
namespace Entity;
 
use \OCFram\Entity;
 
class News extends Entity
{
  protected $member_id,
            $member_login,
            $title,
            $content,
            $dateAdd,
            $dateModif,
            $tags;

  const AUTEUR_INVALIDE = 1;
  const TITRE_INVALIDE = 2;
  const CONTENU_INVALIDE = 3;
 
  public function isValid()
  {
    return !(empty($this->title) || empty($this->content) || empty($this->tags));
  }
 
 
  // SETTERS //

  public function setMember_id($member_id)
  {
    if (empty($member_id))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }
    $this->member_id = $member_id;
  }

  public function setMember_login($member_login)
  {
    $this->member_login = $member_login;
  }
 
  public function setTitle($title)
  {
    if (!is_string($title) || empty($title))
    {
      $this->erreurs[] = self::TITRE_INVALIDE;
    }
 
    $this->title = $title;
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

  public function setTags($tags)
  {
    $this->tags=explode(' ',$tags);
  }

  // GETTERS //

  public function member_id()
  {
    return $this->member_id;
  }

  public function member_login()
  {
    return $this->member_login;
  }

  public function title()
  {
    return $this->title;
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

  public function tags()
  {
    return $this->tags;
  }

}
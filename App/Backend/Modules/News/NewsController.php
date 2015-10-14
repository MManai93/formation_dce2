<?php
namespace App\Backend\Modules\News;
 
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
 
class NewsController extends BackController
{
  public function executeDelete(HTTPRequest $request)
  {
    $newsId = $request->getData('id');
    $news = $this->managers->getManagerOf('News')->getUnique($newsId);
    if(empty($news))
    {
      $this->app->httpResponse()->redirect404();
    }
    else
    {
      if($this->app->user()->getAttribute('groupe_user')==1 || ($this->app->user()->getAttribute('groupe_user')==2 && $this->app->user()->getAttribute('id_user')==$news->member_id()))
      {
        $this->managers->getManagerOf('News')->delete($newsId);
        $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);
        $this->app->user()->setFlash('La news a bien été supprimée !');
        $this->app->httpResponse()->redirect('/');
      }
      else
      {
        $this->app->user()->setFlash('Erreur : Vous n\'etes pas l\'auteur de la news !');
        $this->app->httpResponse()->redirect('/');
      }
    }
  }
 
  public function executeDeleteComment(HTTPRequest $request)
  {
    $comment=$this->managers->getManagerOf('Comments')->get($request->getData('id'));
    $news=$this->managers->getManagerOf('Comments')->getNews($request->getData('id'));
    if($this->app->user()->getAttribute('groupe_user')==1 || ($this->app->user()->getAttribute('groupe_user')==2 && $this->app->user()->getAttribute('id_user')==$comment->member_id()))
    {
      $this->managers->getManagerOf('Comments')->delete($request->getData('id'));
      $this->app->user()->setFlash('Le commentaire a bien été supprimé !');
      $this->app->httpResponse()->redirect('../news-'.$news->id().'.html');
    }

    else
    {
      $this->app->user()->setFlash('Erreur : Vous n\'etes pas l\'auteur du commentaire !');
      $this->app->httpResponse()->redirect('../news-'.$news->id().'.html');
    }

  }
 
  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Gestion des news');
 
    $manager = $this->managers->getManagerOf('News');
 
    $this->page->addVar('listeNews', $manager->getList());
    $this->page->addVar('nombreNews', $manager->count());
  }
 
  public function executeInsert(HTTPRequest $request)
  {
    if($this->app->user()->isAuthenticated())
    {
      $this->processForm($request);
      $this->page->addVar('title', 'Ajout d\'une news');
    }
    else
    {
      $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
      $this->app->httpResponse()->redirect('/');
    }
  }
 
  public function executeUpdate(HTTPRequest $request)
  {
    if($this->app->user()->isAuthenticated())
    {
      $this->processForm($request);
      $this->page->addVar('title', 'Modification d\'une news');
    }
    else
    {
      $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
      $this->app->httpResponse()->redirect('/');
    }
  }
 
  public function executeUpdateComment(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Modification d\'un commentaire');
 
    if ($request->method() == 'POST')
    {
      $comment = new Comment([
        'id' => $request->getData('id'),
        'member_login' => $this->app->user()->getAttribute('login_user'),
        'content' => $request->postData('content'),
        'member_id' =>$this->app->user()->getAttribute('id_user'),
        'member_email' => $this->app->user()->getAttribute('email_user')
      ]);
    }
    else
    {
      if ($request->getExists('id'))
      {
        $comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
        if($this->app->user()->getAttribute('groupe_user')==2 && $this->app->user()->getAttribute('id_user')!=$comment->member_id())
        {
          $this->app->user()->setFlash('Erreur : Vous n\'êtes pas l\'auteur de ce commentaire');
          $this->app->httpResponse()->redirect('/');
        }
      }
      else
      {
        $comment=new Comment;
      }
    }
    if($comment!=null)
    {
      $formBuilder = new CommentFormBuilder($comment);
      $formBuilder->build(true,true);

      $form = $formBuilder->form();

      $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

      if ($formHandler->process())
      {
        $this->app->user()->setFlash('Le commentaire a bien été modifié');
        $news=$this->managers->getManagerOf('Comments')->getNews($request->getData('id'));
        $this->app->httpResponse()->redirect('../news-'.$news->id().'.html');
      }

      $this->page->addVar('form', $form->createView());
    }
    else
    {
      $this->app->httpResponse()->redirect404();
    }
  }

 
  public function processForm(HTTPRequest $request)
  {
    if ($request->method() == 'POST')
    {
      $news = new News([
        'member_id' => $this->app->user()->getAttribute('id_user'),
        'member_login' => $this->app->user()->getAttribute('login_user'),
        'title' => $request->postData('title'),
        'content' => $request->postData('content'),
        'tags' => $request->postData('tags')
      ]);
 
      if ($request->getExists('id'))
      {
        $news->setId($request->getData('id'));
      }
    }
    else
    {
      // L'identifiant de la news est transmis si on veut la modifier
      if ($request->getExists('id'))
      {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

        if($this->app->user()->getAttribute('groupe_user')==2 && $this->app->user()->getAttribute('id_user')!=$news->member_id())
        {
          $this->app->user()->setFlash('Erreur : Vous n\'êtes pas l\'auteur de cette news');
          $this->app->httpResponse()->redirect('/');
        }
      }
      else
      {
        $news = new News;
      }
    }
    if ($news!=null)
    {
      $formBuilder = new NewsFormBuilder($news);
      $formBuilder->build(true,true);

      $form = $formBuilder->form();

      $formHandler = new FormHandler($form, $this->managers->getManagerOf('News'), $request);

      if ($formHandler->process())
      {
        $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

        $this->app->httpResponse()->redirect('/admin/');
      }

      $this->page->addVar('form', $form->createView());
    }
    else
    {
      $this->app->httpResponse()->redirect404();
    }

  }
}
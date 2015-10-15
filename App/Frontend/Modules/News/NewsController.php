<?php
namespace App\Frontend\Modules\News;
 
use Model\NewsManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
 
class NewsController extends BackController
{
  public function executeIndex(HTTPRequest $request)
  {
    $nombreNews = $this->app->config()->get('nombre_news');
    $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

    // On ajoute une définition pour le titre.
    $this->page->addVar('title', 'Liste des ' . $nombreNews . ' dernières news');

    // On récupère le manager des news.
    $manager = $this->managers->getManagerOf('News');

    $listeNews = $manager->getList(0, $nombreNews);


    foreach ($listeNews as $news) {
      if (strlen($news->content()) > $nombreCaracteres) {
        $debut = substr($news->content(), 0, $nombreCaracteres);
        $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

        $news->setContent($debut);
      }
    }

    // On ajoute la variable $listeNews à la vue.
    $this->page->addVar('listeNews', $listeNews);
  }

  public function executeShow(HTTPRequest $request)
  {
    /** @var NewsManager $newsManager */
    $newsManager=$this->managers->getManagerOf('News');
    $news = $newsManager->getUnique($request->getData('id'));

    if (empty($news)) {
      $this->app->httpResponse()->redirect404();
    }

    $listTags=$newsManager->getTagsof($news->id());
    $stringTags='';
    if (is_array($listTags))
    {
      foreach ($listTags as $tag)
      {
        $stringTags .= $tag[0] . '/';
      }
    }

    $this->page->addVar('title', $news->title());
    $this->page->addVar('news', $news);
    $this->page->addVar('stringTags',$stringTags);
    $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
  }

  public function executeInsertComment(HTTPRequest $request)
  {
    // Si le formulaire a été envoyé.
    $comment=null;
    $auth=$this->app->user()->isAuthenticated();
    if ($request->method() == 'POST')
    {
      if($auth)
      {
        $comment = new Comment([
            'news_id' => $request->getData('news'),
            'member_login' => $this->app->user()->getAttribute('login_user'),
            'member_email' => $this->app->user()->getAttribute('email_user'),
            'content' => $request->postData('content'),
            'member_id' => $this->app->user()->getAttribute('id_user')
        ]);

      }
      else
      {
        $comment = new Comment([
            'news_id' => $request->getData('news'),
            'ghost_author' => $request->postData('ghost_author'),
            'content' => $request->postData('content'),
            'ghost_email'=> $request->postData('ghost_email')
        ]);
      }
    }
    else
    {

      if($this->app->httpRequest()->getExists('news'))
      {
        $news = $this->managers->getManagerOf('News')->getUnique($this->app->httpRequest()->getData('news'));
        if (!empty($news))
        {
          $comment = new Comment;
        }
      }
    }

    if($comment!=null)
    {

      $formBuilder = new CommentFormBuilder($comment);
      $formBuilder->build($auth,true);

      $form = $formBuilder->form();

      $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

      if ($formHandler->process())
      {
        $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');

        $this->app->httpResponse()->redirect('news-' . $request->getData('news') . '.html');
      }

      $this->page->addVar('comment', $comment);
      $this->page->addVar('form', $form->createView());
      $this->page->addVar('title', 'Ajout d\'un commentaire');
    }
    else
    {
      $this->app->httpResponse()->redirect404();
    }
  }

  public function executeGetNewComments(HTTPRequest $request)
  {
    if($request->postExists('news_id') && $request->postExists('comment_id_last'))
    {
      $newsId=$request->postData('news_id');
      $commentIdLast=$request->postData('comment_id_last');

      $newsManager=$this->managers->getManagerOf('News');
      $news = $newsManager->getUnique($newsId);

      if (empty($news))
      {
        $this->app->httpResponse()->redirect404();
      }
      $listCommentsofNews=$this->managers->getManagerOf('Comments')->getListOf($newsId);
      $listCommentsAfterIdComment=[];
      foreach($listCommentsofNews as $comment)
      {
        if($comment->id() > $commentIdLast)
        {
          $listCommentsAfterIdComment[]=$comment;
        }
      }
      $this->page->addVar('title',$news->title());
      $this->page->addVar('listComment',$listCommentsAfterIdComment);
    }
  }

}

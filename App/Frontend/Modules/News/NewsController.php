<?php
namespace App\Frontend\Modules\News;
 
use Model\NewsManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;

//la methode run mettra le lien avec son texte et generara la page aveec addvar

 
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
    $commentManager=$this->managers->getManagerOf('Comments');
    $comments=$commentManager->getListOf($news->id(),0,5);
    $this->page->addVar('title', $news->title());
    $this->page->addVar('news', $news);
    $this->page->addVar('listTags',$listTags);
    $this->page->addVar('comments',$comments);
    $this->page->addVar('display_button_show_more',$commentManager->countComments($news->id()) > count($comments));
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

  public function executeShowTag(HTTPRequest $request)
  {
    $newsManager=$this->managers->getManagerOf('News');
    $listNews=$newsManager->getNewsOf($request->getData('name'));
    $this->page->addVar('title', 'Liste des news ayant pour tag'.$request->getData('name'));
    $this->page->addVar('listNews', $listNews);
    $this->page->addVar('TagName', $request->getData('name'));
    $this->page->addVar('countNews', count($listNews));
  }

  public function executeGetComments(HTTPRequest $request)
  {
    if($request->postData('comment_id_last')!=null)
    {
      $listComment=$this->managers->getManagerOf('Comments')->getListOfAfter($request->postData('news_id'),$request->postData('comment_id_last'));
    }
    else if ($request->postExists('comment_id_old')!=null)
    {
      $listComment=$this->managers->getManagerOf('Comments')->getListOfBefore($request->postData('news_id'),$request->postData('comment_id_old'));
    }
    exit(json_encode($listComment));
  }
}

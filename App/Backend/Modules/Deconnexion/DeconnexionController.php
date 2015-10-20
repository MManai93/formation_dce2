<?php
namespace App\Backend\Modules\Deconnexion;

use App\Backend\AppController;
use \OCFram\BackController;
use \OCFram\HTTPRequest;

class DeconnexionController extends BackController
{
    use AppController;
    private static $ADMIN_ID=1,
                   $MEMBER_ID=2;
    public function executeIndex(HTTPRequest $Request)
    {
        $this->run();
        $this->page->addVar('title', 'Deconnexion');
        if($this->app->user()->isAuthenticated())
        {
            if ($Request->postExists('Yes'))
            {
                $this->app->user()->setAuthenticated(false);
                session_destroy();
                $this->app->httpResponse()->redirect('/');//A CHANGER
            }
            elseif ($Request->postExists('No'))
            {
                if($this->app->user()->getAttribute('groupe_user')==self::$ADMIN_ID)
                {
                    $this->app->httpResponse()->redirect('/admin/');//A CHANGER
                }
                elseif ($this->app->user()->getAttribute('groupe_user')==self::$MEMBER_ID)
                {
                    $this->app->httpResponse()->redirect('/');//A CHANGER
                }
            }
        }
        else
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté!');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }
    }

}
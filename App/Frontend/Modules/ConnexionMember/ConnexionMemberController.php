<?php
namespace App\Frontend\Modules\ConnexionMember;

use App\Frontend\AppController;
use \OCFram\BackController;
use \OCFram\HTTPRequest;

class ConnexionMemberController extends BackController
{
    use AppController;
    private static $GROUP_ID = 2;
    public function executeIndex(HTTPRequest $Request)
    {
        $this->run();
        $this->page->addVar('title', 'Connexion Membre');
        if($this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous �tes d�j� connect� !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }

        if ($Request->postExists('login'))
        {
            $login = $Request->postData('login');
            $password = sha1($Request->postData('password'));
            $Identifiants=$this->managers->getManagerOf('Connexion')->FindUser($login,$password,self::$GROUP_ID);

            if ($Identifiants)
            {
                $this->app->user()->setAuthenticated(true);
                $this->app->user()->setAttribute('id_user',$Identifiants->id());
                $this->app->user()->setAttribute('login_user',$Identifiants->login());
                $this->app->user()->setAttribute('email_user',$Identifiants->email());
                $this->app->user()->setAttribute('groupe_user',$Identifiants->groupe_id());
                $this->app->httpResponse()->redirect('/admin/');// A CHANGER
            }
            else
            {
                $this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.');
            }
        }
    }
}
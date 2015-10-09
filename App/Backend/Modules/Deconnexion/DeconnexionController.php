<?php
namespace App\Backend\Modules\Deconnexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class DeconnexionController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Deconnexion');
        if ($request->postExists('Yes'))
        {
            if($this->app->user()->isAuthenticated())
            {
                $this->app->user()->setAuthenticated(false);
                session_destroy();
                $this->app->httpResponse()->redirect('/');
            }
        }
        elseif ($request->postExists('No'))
        {
            $this->app->httpResponse()->redirect('/admin/');
        }
    }

}
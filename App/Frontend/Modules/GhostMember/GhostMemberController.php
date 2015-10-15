<?php
namespace App\Frontend\Modules\GhostMember;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class GhostMemberController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $commentManager=$this->managers->getManagerOf('Comments');
        $listComment=$commentManager->getCommentGhostAuthor($request->getData('name'));
        if(empty($listComment))
        {
            $this->app->httpResponse()->redirect404();
        }
        $ghost_author=$request->getData('name');
        $this->page->addVar('title', 'Profil de '.$ghost_author);
        $this->page->addVar('listComment',$listComment);
        $this->page->addVar('ghost_author',$ghost_author);
        $this->page->addVar('countComment',$commentManager->countCommentGhostAuthor($ghost_author));
        $this->page->addVar('managerNews',$this->managers->getManagerOf('News'));

    }

}
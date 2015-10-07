<?php
namespace App\Backend\Modules\MobileDetect;
 
use \OCFram\BackController;
use \OCFram\HTTPRequest;
 
class MobileDetectController extends BackController
{
  public function executeIndex(HTTPRequest $request)
  {
   $this->page->addVar('title', 'Detection mobile');
    $md = new Mobile_Detect;

    if ( $md->isMobile() ) {
      //..
    }
    elseif ($md->isTablet())
    {
      //...
    }
    else{}
    $this->page->addVar('Propriétés', $md->getProperties());

  }
}

?>
<?php
namespace App\Frontend\Modules\Registration;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \FormBuilder\RegistrationFormBuilder;
use \OCFram\FormHandler;

class RegistrationController extends BackController
{
    protected static $GROUPE_ID=2;
    public function executeIndex(HTTPRequest $request)
    {
        if ($request->method()=='POST')
        {
            $Membre = new Member([
                'login' => $request->postData('login'),
                'password' => $request->postData('password'),
                'passwordcheck' => $request->postData('passwordcheck'),
                'email' => $request->postData('email'),
                'groupe_id' => self::$GROUPE_ID
            ]);
        }
        else
        {
            $Membre=new Member;
        }


        $formBuilder = new RegistrationFormBuilder($Membre);
        $formBuilder->build(true,true);
        $form=$formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Member'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Vous êtes inscrit !');

            $this->app->httpResponse()->redirect('/');
        }
        $this->page->addVar('registration', $Membre);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Inscription Membre');



    }
}
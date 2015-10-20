<?php
namespace App\Backend\Modules\Member;

use App\Backend\AppController;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \FormBuilder\MemberFormBuilder;
use \OCFram\FormHandler;

class MemberController extends BackController
{
    use AppController;
    public function executeDelete(HTTPRequest $request)
    {
        $this->run();
        $profilId = $request->getData('id');
        $Member =$this->managers->getManagerOf('Member')->getUnique($profilId);
        if(empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }
        elseif (!$this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }
        else
        {
            if(($this->app->user()->getAttribute('groupe_user') < $Member->groupe_id()) || ($this->app->user()->getAttribute('id_user')==$Member->id()))
            {
                $this->page->addVar('idURL',$request->getData('id'));
                if($this->app->user()->getAttribute('id_user')==$Member->id())
                {
                    if ($request->postExists('password'))
                    {
                        $login = $this->app->user()->getAttribute('login_user');
                        $password = sha1($request->postData('password'));
                        $identifiants=$this->managers->getManagerOf('Connexion')->FindUser($login,$password,$this->app->user()->getAttribute('groupe_user'));

                        if ($identifiants)
                        {
                            $this->app->user()->setAuthenticated(false);
                            session_destroy();
                            $this->managers->getManagerOf('Member')->delete($profilId);
                            $this->app->user()->setFlash('Le membre a bien été supprimé !');
                            $this->app->httpResponse()->redirect('/');//A CHANGER
                        }
                        elseif ($request->postExists('No'))
                        {
                            $this->app->httpResponse()->redirect('/profil-'.$Member->id().'.html');//A CHANGER
                        }
                    }
                }
                else
                {
                    $this->managers->getManagerOf('Member')->delete($profilId);
                    $this->app->user()->setFlash('Le membre a bien été supprimé !');
                    $this->app->httpResponse()->redirect('/');//A CHANGER
                }

            }
            else
            {
                $this->app->user()->setFlash('Erreur : Vous n\'avez pas les droits nécessaires !');
                $this->app->httpResponse()->redirect('/');//A CHANGER
            }
        }
    }

    public function executeIndex(HTTPRequest $request)
    {   /** @var Member $Member */
        $Member = $this->managers->getManagerOf('Member')->getUnique($request->getData('id'));

        if (empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }
        $this->page->addVar('title','Profil de '.$Member->login());
        $this->page->addVar('user',$this->app->user());
        $this->page->addVar('idMembre',$Member->id());
        $this->page->addVar('groupeMembre',$Member->groupe_id());
        $this->page->addVar('login',$Member->login()); //gerer les visibilités selon l'envie des utilisateurs
        $this->page->addVar('email',$Member->email());
        $this->page->addVar('dateInscription',$Member->dateRegistration() != '0000-00-00' ? $Member->dateRegistration() : null );
        $this->page->addVar('dateNaissance',$Member->birthday() != '0000-00-00' ? $Member->birthday() : null );
        $this->page->addVar('adresse',$Member->adress());
        $this->page->addVar('ville',$Member->city());
        $this->page->addVar('pays',$Member->country());
        $this->run();
    }

    public function executeShow(HTTPRequest $request)
    {
        $this->run();
        if ($this->app->user()->isAuthenticated())
        {
            $this->page->addVar('title', 'Gestion des membres');
            $manager = $this->managers->getManagerOf('Member');
            $this->page->addVar('listeMembre', $manager->getListMember());
            $this->page->addVar('nombreMembre', $manager->count());
        }
        else
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }
    }

    public function executeShowComments(HTTPRequest $request)
    {
        $this->run();
        $profilId = $request->getData('id');
        $Member = $this->managers->getManagerOf('Member')->getUnique($profilId);
        if(empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }
        elseif (!$this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }
        else
        {
            $this->page->addVar('idURL',$request->getData('id'));
            $this->page->addVar('title', 'Liste des commentaires postés par '.$Member->login());
            $manager = $this->managers->getManagerOf('Member');
            $this->page->addVar('listeComment', $manager->getListComments($profilId));
            $this->page->addVar('nombreComment', $manager->countComments($profilId));
        }

    }

    public function executeShowNews(HTTPRequest $request)
    {
        $this->run();
        $profilId = $request->getData('id');
        $Member = $this->managers->getManagerOf('Member')->getUnique($profilId);
        if(empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }
        elseif (!$this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');
        }
        else
        {
            $this->page->addVar('idURL',$request->getData('id'));
            $this->page->addVar('title', 'Liste des news postés par '.$Member->login());
            $manager = $this->managers->getManagerOf('Member');
            $this->page->addVar('listeNews', $manager->getListNews($profilId));
            $this->page->addVar('nombreNews', $manager->countNews($profilId));
        }
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $this->run();
        $profilId = $request->getData('id');
        $Member = $this->managers->getManagerOf('Member')->getUnique($profilId);
        if(empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }

        elseif (!$this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }

        else
        {
            if(($this->app->user()->getAttribute('groupe_user') < $Member->groupe_id()) || ($this->app->user()->getAttribute('id_user')==$Member->id()))
            {
                $this->app->user()->getAttribute('groupe_user')==1 ? $admin=true : $admin=false;
                $this->processForm($request,$Member,$admin);
                $this->page->addVar('title', 'Modification du membre '.$Member->login());
                $this->page->addVar('idURL',$request->getData('id'));
            }
            else
            {
                $this->app->user()->setFlash('Erreur : Vous n\'avez pas les droits nécessaires !');
                $this->app->httpResponse()->redirect('/');//A CHANGER
            }
        }

    }

    public function executeUpdateLogin(HTTPRequest $request)
    {
        $this->run();
        $profilId = $request->getData('id');
        $memberManager=$this->managers->getManagerOf('Member');
        $Member = $memberManager->getUnique($profilId);
        if(empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }

        elseif (!$this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }

        else
        {
            if(($this->app->user()->getAttribute('groupe_user') < $Member->groupe_id()) || ($this->app->user()->getAttribute('id_user')==$Member->id()))
            {
                $this->page->addVar('title','Modification du login' );
                $this->page->addVar('idURL',$request->getData('id'));
                if ($request->postExists('newlogin'))
                {
                    $login = $Member->login();
                    $password = sha1($request->postData('password'));
                    $managerConnexion=$this->managers->getManagerOf('Connexion');
                    $identifiants=$managerConnexion->FindUser($login,$password,$Member->groupe_id());

                    if($memberManager->FindUser($request->postExists('newlogin')))
                    {
                        $this->app->user()->setFlash('Ce login est déjà utilisé !');
                        $this->app->httpResponse()->redirect('/profil-update-login-'.$Member->id().'.html');//A CHANGER
                    }

                    elseif ($identifiants)
                    {
                        $memberManager->modifyLogin($request->postData('newlogin'),$Member->groupe_id());
                        $this->app->user()->setAttribute('login_user',$request->postData('newlogin'));
                        $this->app->user()->setFlash('Votre login a bien été modifié !');
                        $this->app->httpResponse()->redirect('profil-'.$request->getData('id').'.html');//A CHANGER
                    }

                    else
                    {
                        $this->app->user()->setFlash('Mot de passe incorrect!');
                        $this->app->httpResponse()->redirect('profil-update-login-'.$Member->id().'.html');//A CHANGER
                    }
                }
            }
            else
            {
                $this->app->user()->setFlash('Erreur : Vous n\'avez pas les droits nécessaires !');
                $this->app->httpResponse()->redirect('/');//A CHANGER
            }
        }
    }

    public function executeUpdatePassword(HTTPRequest $request)
    {
        $this->run();
        $profilId = $request->getData('id');
        $memberManager=$this->managers->getManagerOf('Member');
        $Member = $memberManager->getUnique($profilId);
        if(empty($Member))
        {
            $this->app->httpResponse()->redirect404();
        }

        elseif (!$this->app->user()->isAuthenticated())
        {
            $this->app->user()->setFlash('Vous n\'êtes pas connecté !');
            $this->app->httpResponse()->redirect('/');//A CHANGER
        }

        else
        {
            if(($this->app->user()->getAttribute('groupe_user') < $Member->groupe_id()) || ($this->app->user()->getAttribute('id_user')==$Member->id()))
            {
                $this->page->addVar('title','Modification du mot de passe' );
                $this->page->addVar('idURL',$request->getData('id'));
                if ($request->postExists('newpassword'))
                {
                    $login = $this->app->user()->getAttribute('login_user');
                    $password = sha1($request->postData('password'));
                    $manager=$this->managers->getManagerOf('Connexion');
                    $identifiants=$manager->FindUser($login,$password,$Member->groupe_id());
                    if ($identifiants)
                    {
                        $memberManager->modifyPassword(sha1($request->postData('newpassword')),$Member->id());
                        $this->app->user()->setFlash('Le mot de passe a bien été modifié !');
                        $this->app->httpResponse()->redirect('/admin/profil-'.$Member->id().'.html');//A CHANGER
                    }
                    else
                    {
                        $this->app->user()->setFlash('Mot de passe incorrect!');
                        $this->app->httpResponse()->redirect('/admin/profil-update-password-'.$Member->id().'.html');//A CHANGER
                    }
                }
            }
            else
            {
                $this->app->user()->setFlash('Erreur : Vous n\'avez pas les droits nécessaires !');
                $this->app->httpResponse()->redirect('/');//A CHANGER
            }
        }
    }

    public function processForm(HTTPRequest $request,$Membre = null,$admin)
    {
        if ($request->method() == 'POST')
        {
            $Membre = new Member([
                'login' => $this->app->user()->getAttribute('login_user'),
                'password' => $request->postData('password'), // Passwordvalidator
                'email' => $request->postData('email'),
                'birthday' => $request->postData('birthday'),
                'adress' => $request->postData('adress'),
                'city' => $request->postData('city'),
                'country' => $request->postData('country')
            ]);
            if ($request->getExists('id'))
            {
                $Membre->setId($request->getData('id'));
            }
        }
        elseif ($Membre == null)
        {
            $Membre = new Member;
        }

        $formBuilder = new MemberFormBuilder($Membre);
        $formBuilder->build(true,$admin);
        $form = $formBuilder->form();
        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Member'), $request);
        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Les informations du membre ont bien été modifié');
            $this->app->httpResponse()->redirect('profil-'.$request->getData('id').'.html'); //changer redirection pour la page du membre en question
        }
        $this->page->addVar('form', $form->createView());
    }
}
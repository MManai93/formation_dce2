<?php

namespace App\Backend;

trait AppController
{
    protected $listMenu;
    protected $entete;
    protected $flash='';

    public function flash()
    {
        $user=$this->app->user();
        if ($user->hasFlash())
        {
            $this->flash='<p style="text-align: center;">'. $user->getFlash().'</p>';
        }
        $this->page->addVar('flash',$this->flash);
    }

    public function entete()
    {
        $user=$this->app->user();
        if($user->isAuthenticated())
        {
            $this->entete='Connecté en tant que '.$user->getAttribute('login_user');
        }
        else
        {
            $this->entete='Comment ça il y a presque rien ?';
        }
        $this->page->addVar('header',$this->entete);
    }

    public function menu()
    {
        $user=$this->app->user();
        $this->listMenu[]=array('text'=>'Accueil', 'link' => $this->app->router()->getURL('News','index'));
        if($user->isAuthenticated())
        {
            $this->listMenu[]=array('text'=>'Afficher les news', 'link' => $this->app->router()->getURL('News','index'));//A CHANGER
            $this->listMenu[]=array('text'=>'Membres', 'link' =>  $this->app->router()->getURL('Member','show'));//A CHANGER
            $this->listMenu[]=array('text'=>'Ajouter une news', 'link' => $this->app->router()->getURL('News','insert'));//A CHANGER
            $this->listMenu[]=array('text'=>'Afficher mon profil', 'link' => $this->app->router()->getURL('Member','index',['id'=>$user->getAttribute('id_user')]));//A CHANGER
            $this->listMenu[]=array('text'=>'Déconnexion', 'link' => $this->app->router()->getURL('Deconnexion','index'));//A CHANGER

        }
        else
        {
            $this->listMenu[]=array('text'=>'Inscription', 'link'=> $this->app->router()->getURL('Registration','index'));//A CHANGER
            $this->listMenu[]=array('text'=>'Connexion', 'link'=> $this->app->router()->getURL('Connexion','index'));//A CHANGER
        }
        $this->page->addVar('Menu',$this->listMenu);
    }

    public function run()
    {
        $this->menu();
        $this->entete();
        $this->flash();
    }
}
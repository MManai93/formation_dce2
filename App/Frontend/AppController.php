<?php

namespace App\Frontend;

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
        if(!$user->isAuthenticated())
        {
            $this->listMenu[]=array('text'=>'Inscription', 'link'=> $this->app->router()->getURL('Registration','index'));//A CHANGER
            $this->listMenu[]=array('text'=>'Connexion', 'link'=> $this->app->router()->getURL('ConnexionMember','index'));//A CHANGER
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
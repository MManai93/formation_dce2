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
            $this->entete='Connect� en tant que '.$user->getAttribute('login_user');
        }
        else
        {
            $this->entete='Comment �a il y a presque rien ?';
        }
        $this->page->addVar('header',$this->entete);
    }

    public function menu()
    {
        $user=$this->app->user();
        $this->listMenu[]=array('text'=>'Accueil', 'link' => '/');
        if($user->isAuthenticated())
        {
            $this->listMenu[]=array('text'=>'Afficher les news', 'link' =>'/admin/');//A CHANGER
            $this->listMenu[]=array('text'=>'Membres', 'link' => '/admin/profil-list.html');//A CHANGER
            $this->listMenu[]=array('text'=>'Ajouter une news', 'link' =>'/admin/news-insert.html');//A CHANGER
            $this->listMenu[]=array('text'=>'Afficher mon profil', 'link' =>'/admin/profil-'.$user->getAttribute('id_user').'.html');//A CHANGER
            $this->listMenu[]=array('text'=>'D�connexion', 'link' =>'/admin/deconnexion.html');//A CHANGER

        }
        else
        {
            $this->listMenu[]=array('text'=>'Inscription', 'link'=>'/inscription.html');//A CHANGER
            $this->listMenu[]=array('text'=>'Connexion', 'link'=>'/connexion.html');//A CHANGER
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
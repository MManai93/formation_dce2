<?php

namespace App\Frontend;

use OCFram\Router;

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
        $this->listMenu[]=array('text'=>'Accueil', 'link' => '/');
        if($user->isAuthenticated())
        {
            $this->listMenu[]=array('text'=>'Afficher les news', 'link' =>'/admin/');
            $this->listMenu[]=array('text'=>'Membres', 'link' => '/admin/profil-list.html');
            $this->listMenu[]=array('text'=>'Ajouter une news', 'link' =>'/admin/news-insert.html');
            $this->listMenu[]=array('text'=>'Afficher mon profil', 'link' =>'/admin/profil-'.$user->getAttribute('id_user').'.html');
            $this->listMenu[]=array('text'=>'Déconnexion', 'link' =>'/admin/deconnexion.html');
        }
        else
        {
            $this->listMenu[]=array('text'=>'Inscription', 'link'=>'/inscription.html');
            $this->listMenu[]=array('text'=>'Connexion', 'link'=>'/connexion.html');
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
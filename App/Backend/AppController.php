<?php

namespace App\Backend;

trait AppController
{
    protected $listMenu;
    protected $entete;

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
            $this->listMenu[]=array('text'=>'D�connexion', 'link' =>'/admin/deconnexion.html');
            $this->entete='Connect� en tant que '.$user->getAttribute('login_user');
        }
        else
        {
            $this->listMenu[]=array('text'=>'Inscription', 'link'=>'/inscription.html');
            $this->listMenu[]=array('text'=>'Connexion', 'link'=>'/connexion.html');
            $this->entete='Comment �a il y a presque rien ?';
        }
    }

    public function run()
    {
        $this->menu();
        $this->page->addVar('Menu',$this->listMenu);
        $this->page->addVar('header',$this->entete);
    }
}
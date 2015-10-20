<?php

namespace App\Frontend;

trait AppController
{
    protected $listMenu;

    protected function run()
    {
        $this->menu();
        $this->page()->addVar('Menu',$listMenu);
    }

    public function menu($text,$link)
    {
        $listMenu[]=array('text'=>'Accueil', 'link' => '/');
        if($user->isAuthenticated())
        {
            $listMenu[]=array('text'=>'Afficher les news', 'link' =>'/admin/');
            $listMenu[]=array('text'=>'Membres', 'link' => '/admin/profil-list.html');
            $listMenu[]=array('text'=>'Ajouter une news', 'link' =>'/admin/news-insert.html');
            $listMenu[]=array('text'=>'Afficher mon profil', 'link' =>'/admin/profil-',$user->getAttribute('id_user'),'.html');
            $listMenu[]=array('text'=>'Déconnexion', 'link' =>'/admin/deconnexion.html');
        }
        else
        {
            $listMenu[]=array('text'=>'Inscription', 'link'=>'/inscription.html');
            $listMenu[]=array('text'=>'Connexion', 'link'=>'/connexion.html');
        }
    }
}
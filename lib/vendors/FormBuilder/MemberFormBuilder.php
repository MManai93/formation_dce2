<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\PassField;
use \OCFram\HiddenField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\EmailValidator;
use \OCFram\PasswordValidator;
use \OCFram\DateField;

class MemberFormBuilder extends FormBuilder
{

    public function build($auth,$admin)
    {
        $LoginField = new HiddenField([
            'label' => '',
            'name' => 'login',
        ]);
        if(!$admin) // voir is on doit enlever le empty (password)
        {
            $this->form->add($LoginField)
                ->add(new PassField([
                    'label' => 'Mot de passe',
                    'name' => 'password',
                    'validators' => [
                        new PasswordValidator('Mot de passe incorrect !',$LoginField),
                    ],
                ]));
        }
        $this->form->add(new StringField([
            'label' => $admin ? 'Email' : 'Votre email',
            'name' => 'email',
            'validators' => [
                new MaxLengthValidator('L\'email sp�cifi� est trop long (250 caract�res maximum)', 250),
                new NotNullValidator('Merci de sp�cifier un email'),
                new EmailValidator('Email non valide !'),
            ],
        ]))
            ->add(new DateField([
                'label' => $admin ? 'Date de naissance' : 'Votre date de naissance',
                'name' => 'birthday',
            ]))
            ->add(new StringField([
                'label' => $admin ? 'Adresse' : 'Votre adresse',
                'name' => 'adress',
                'validators' => [
                    new MaxLengthValidator('L\'adresse sp�cifi�e est trop longue (100 caract�res maximum)', 100),
                ],
            ]))
            ->add(new StringField([ // barre deroulante ??
                'label' => $admin ? 'Ville' : 'Votre ville',
                'name' => 'city',
                'validators' => [
                    new MaxLengthValidator('La ville sp�cifi�e est trop longue (50 caract�res maximum)', 50),
                ],
            ]))
            ->add(new StringField([ // barre deroulante ??
                'label' => $admin ? 'Pays' : 'Votre pays',
                'name' => 'country',
                'validators' => [
                    new MaxLengthValidator('Le pays sp�cifi� est trop long (50 caract�res maximum)', 50),
                ],
            ]));
    }
}
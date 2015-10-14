<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\PassField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\AlreadyExistsValidator;
use \OCFram\SamePasswordValidator;
use \OCFram\EmailValidator;

class RegistrationFormBuilder extends FormBuilder
{
    public function build($auth,$admin)
    {
        $password = new PassField([
            'label' => 'Votre mot de passe',
            'name' => 'password',
            'validators' => [
                new MaxLengthValidator('Le mot de passe spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier un mot de passe'),
            ],
        ]);

        $this->form->add(new StringField([
            'label' => 'Votre Login',
            'name' => 'login',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le login spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier le login'),
                new AlreadyExistsValidator('Le login existe déjà !'),
            ],
            ]))
            ->add($password)
            ->add(new PassField([
                'label' => 'Retapez votre mot de passe',
                'name' => 'passwordcheck',
                'validators' => [
                    new SamePasswordValidator('Les mots de passe ne correspondent pas', $password),
                ],
            ]))
            ->add(new StringField([
                'label' => 'Votre email',
                'name' => 'email',
                'validators' => [
                    new MaxLengthValidator('L\'email spécifié est trop long (250 caractères maximum)', 250),
                    new NotNullValidator('Merci de spécifier un email'),
                    new EmailValidator('Email non valide !'),
                ],
            ]));

    }
}
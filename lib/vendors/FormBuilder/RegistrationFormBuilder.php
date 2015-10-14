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
                new MaxLengthValidator('Le mot de passe sp�cifi� est trop long (50 caract�res maximum)', 50),
                new NotNullValidator('Merci de sp�cifier un mot de passe'),
            ],
        ]);

        $this->form->add(new StringField([
            'label' => 'Votre Login',
            'name' => 'login',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le login sp�cifi� est trop long (50 caract�res maximum)', 50),
                new NotNullValidator('Merci de sp�cifier le login'),
                new AlreadyExistsValidator('Le login existe d�j� !'),
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
                    new MaxLengthValidator('L\'email sp�cifi� est trop long (250 caract�res maximum)', 250),
                    new NotNullValidator('Merci de sp�cifier un email'),
                    new EmailValidator('Email non valide !'),
                ],
            ]));

    }
}
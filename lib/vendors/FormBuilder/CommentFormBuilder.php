<?php
namespace FormBuilder;
 
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\EmailValidator;

class CommentFormBuilder extends FormBuilder
{
  public function build($auth,$admin)
  {
    if($auth)
    {
        $this->form->add(new TextField([
                'label' => 'Contenu',
                'name' => 'content',
                'rows' => 7,
                'cols' => 50,
                'validators' => [
                    new NotNullValidator('Merci de spécifier votre commentaire'),
                ],
            ]));
    }
    else
    {
        $this->form->add(new StringField([
            'label' => 'Auteur',
            'name' => 'ghost_author',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
            ],
            ]))
            ->add(new TextField([
                'label' => 'Contenu',
                'name' => 'content',
                'rows' => 7,
                'cols' => 50,
                'validators' => [
                    new NotNullValidator('Merci de spécifier votre commentaire'),
                ],
            ]))
            ->add(new StringField([
                'label' => 'Votre email',
                'name' => 'ghost_email',
                'validators' => [
                    new MaxLengthValidator('L\'email spécifié est trop long (250 caractères maximum)', 250),
                    new NotNullValidator('Merci de spécifier un email'),
                    new EmailValidator('Email non valide !'),
                ],
            ]));
    }

  }
}
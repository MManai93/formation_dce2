<?php
namespace FormBuilder;
 
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\TagField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\MaxTagsValidator;

class NewsFormBuilder extends FormBuilder
{
  public function build($auth,$admin)
  {
    $this->form->add(new StringField([
        'label' => 'Titre',
        'name' => 'title',
        'maxLength' => 100,
        'validators' => [
          new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
          new NotNullValidator('Merci de spécifier le titre de la news'),
        ],
       ]))
       ->add(new TextField([
        'label' => 'Contenu',
        'name' => 'content',
        'rows' => 8,
        'cols' => 60,
        'validators' => [
          new NotNullValidator('Merci de spécifier le contenu de la news'),
        ],
       ]))
        ->add(new TagField([
            'label' => 'Tags',
            'name' => 'tags',
            'maxTags' => 15,
            'validators' => [
                new NotNullValidator('Merci de spécifier les tags associés à la news'),
                new MaxTagsValidator('Vous pouvez spécifier seulement 15 tags au maximum !',15),
            ],
        ]));
  }
}
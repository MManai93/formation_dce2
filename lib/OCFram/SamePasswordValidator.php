<?php
namespace OCFram;

class SamePasswordValidator extends Validator
{
    protected $passfield;

    public function __construct($errorMessage, PassField $passfield)
    {
        parent::__construct($errorMessage);
        $this->setPassfield($passfield);
    }
    public function isValid($value)
    {
       return ($value == $this->passfield->value());
    }

    public function setPassfield(Passfield $passfield)
    {
        $this->passfield=$passfield;
    }

}
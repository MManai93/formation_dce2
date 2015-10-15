<?php
namespace OCFram;

class NotNullTagsValidator extends Validator
{
    public function isValid($value)
    {
        return $value[0] !='';
    }
}
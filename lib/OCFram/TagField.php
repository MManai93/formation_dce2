<?php
namespace OCFram;

class TagField extends Field
{
    protected $maxLength;

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage))
        {
            $widget .= $this->errorMessage.'<br />';
        }

        $widget .= '<label>'.$this->label.'</label><input type="text" name="'.$this->name.'"';

        if (!empty($this->value))
        {
            $widget .= ' value="'.implode(' ',$this->value).'"';
        }

        return $widget .= ' />';
    }

    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;

        if ($maxLength > 0)
        {
            $this->maxLength = $maxLength;
        }
        else
        {
            throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
        }
    }
}
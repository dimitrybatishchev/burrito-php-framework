<?php

namespace Burrito\Framework\Form\Element;


class Submit {

    private $params;

    public function __construct($params){
        $this->params = $params;
    }

    public function render(){
        $html = '<input type="submit"';
        foreach ($this->params as $attribute => $value){
            if (in_array($attribute, array('id', 'class', 'name', 'value')) and !empty($value)){
                $html .= ' ' . $attribute . '="' . $value . '"';
            }
        }
        $html .= '>';
        return $html;
    }

    public function isValid(){
        return true;
    }

    public function getName(){
        return null;
    }

}
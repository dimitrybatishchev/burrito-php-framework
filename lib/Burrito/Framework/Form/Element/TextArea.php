<?php

namespace Burrito\Framework\Form\Element;


class TextArea {

    private $params;

    public function __construct($params){
        $this->params = $params;
    }

    public function render(){
        $html = '<textarea';
        foreach ($this->params as $attribute => $value){
            if (in_array($attribute, array('id', 'class', 'name', 'value')) and !empty($value)){
                $html .= ' ' . $attribute . '="' . $value . '"';
            }
        }
        $html .= '></textarea>';
        return $html;
    }

    public function isValid(){
        return true;
    }

    public function getName(){
        return $this->params['name'];
    }

} 
<?php
namespace Blog\Form;

use Burrito\Framework\Form\BasicForm;
use Burrito\Framework\Form\Element\TextInput;
use Burrito\Framework\Form\Element\TextArea;
use Burrito\Framework\Form\Element\Submit;

class PostForm extends BasicForm{

    public function __construct(){

        $this->setMethod('post');

        $this->addElement(new TextInput(array(
            'name' => 'title',
        )));
        $this->addElement(new TextArea(array(
            'name' => 'content',
        )));
        $this->addElement(new Submit(array(

        )));

    }

} 
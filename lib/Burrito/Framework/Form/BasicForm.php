<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 12/4/13
 * Time: 7:46 AM
 */

namespace Burrito\Framework\Form;

use Burrito\Framework\Http\Request;

class BasicForm {

    private $elements = array();
    private $method;
    private $requestData;

    public function isValid(){
        foreach ($this->elements as $element){
            if (!$element->isValid()){
                return false;
            }
            return true;
        }
    }

    public function bindRequest(Request $request){
        $this->requestData = $request->getData($this->method);
    }

    public function getValues(){
        $values = array();
        foreach ($this->elements as $element){
            $elementName = $element->getName();
            if ($elementName){
                if (array_key_exists($elementName, $this->requestData)){
                    $values[$elementName] = $this->requestData[$elementName];
                }
            }
        }
        return $values;
    }

    public function addElement($element){
        $this->elements[] = $element;
    }

    public function render(){
        $html =  $this->getHeader();
        foreach ($this->elements as $element){
            $html .= $element->render();
        }
        $html .= $this->getFooter();
        return $html;
    }

    public function getHeader(){
        return '<form method="' . $this->method .'">';
    }

    public function getFooter(){
        return "</form>";
    }

    public function setMethod($method){
        $this->method = $method;
    }

} 
<?php
namespace Burrito\Framework;

class View {

    private $parent = 'layout';
    private $module;

    public function __construct($module){
        $this->module = $module;
    }

    // получить отренедеренный шаблон с параметрами $params
    function fetchPartial($template, $params = array()){
        extract($params);
        include ROOT.'\views\\'.$template.'.php';
        return ob_get_clean();
    }

    // получить отренедеренный в переменную $content layout-а
    // шаблон с параметрами $params
    function fetch($template, $params = array()){
        ob_start();
        $content = $this->fetchPartial($this->module . '\\' .$template, $params);
        return $this->fetchPartial($this->parent, array('content' => $content));
    }

    // вывести отренедеренный в переменную $content layout-а
    // шаблон с параметрами $params
    function render($template, $params = array()){
        return $this->fetch($template, $params);
    }

    function extend($parent){
        $this->parent = $parent;
    }
}
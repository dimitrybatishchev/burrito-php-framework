<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/5/13
 * Time: 10:46 AM
 */
namespace Burrito\Framework\Http;

final class Request{

    protected $_data = array();

    protected $_post = array();

    protected $_get = array();

    protected $_cookie = array();

    protected $_method;

    protected $route;

    protected $params;

    public function __construct(){
        $this->_data = array_merge($this->_data, $_REQUEST);
        $this->_get = array_merge($this->_get, $_GET);
        $this->_post = array_merge($this->_get, $_POST);
        $this->_cookie = array_merge($this->_cookie, $_COOKIE);
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_clean();
    }

    public function set($key, $value){
        $this->_data[$key] = $value;
    }

    public function get($key){
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function getPathInfo(){
        return $this->get('r');
    }

    public function getData($method){
        switch(strtolower($method)){
            case 'get':
                $array = $this->_get;
                break;
            case 'post':
                $array = $this->_post;
                break;
        }
        return $array;
    }

    public function getRawData($var, $key){
        switch(strtolower($var)){
            case 'get':
                $array = $this->_get;
                break;
            case 'post':
                $array = $this->_post;
                break;
            case 'cookie':
                $array = $this->_cookie;
                break;
            default:
                $array = array();
                break;
        }

        if(isset($array[$key])){
            return $array[$key];
        }

        return null;
    }

    /**
     * Internally clean request data by handling magic_quotes_gpc and then adding slashes.
     *
     */
    protected function _clean(){
        if(get_magic_quotes_gpc()){
            $this->_data = $this->_stripSlashes($this->_data);
            $this->_post = $this->_stripSlashes($this->_post);
            $this->_get = $this->_stripSlashes($this->_get);
        }
    }

    /**
     * Strip slashes code from php.net website.
     *
     * @param mixed $value
     * @return array
     */
    protected function _stripSlashes($value) {
        if(is_array($value)) {
            return array_map(array($this,'_stripSlashes'), $value);
        } else {
            return stripslashes($value);
        }
    }

    public function getMethod(){
        return $this->_method;
    }

    public function setRoute($route){
        $this->route = $route;
    }

    public function getRoute(){
        return $this->route;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

}
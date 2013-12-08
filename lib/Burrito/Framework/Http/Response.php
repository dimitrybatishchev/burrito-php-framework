<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/22/13
 * Time: 4:02 PM
 */

namespace Burrito\Framework\Http;


class Response {

    private $headers = array();
    private $statusCode;
    private $charset;
    private $body;

    public function __construct(){

    }

    public function send(){
        foreach($this->headers as $key => $value){
            header($key . ": " . $value);
        }
        echo $this->body;
    }

    public function setBody($body){
        $this->body = $body;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param mixed $headers
     */
    public function addHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

} 
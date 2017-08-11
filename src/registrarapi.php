<?php
namespace registrarapi;

use GuzzleHttp;

abstract class registrarapi {

  private $lastrequesturl;
  private $error;
  private $response;

  protected $responseFormat;

  /**
   * Construct based purely on defaults
   */
  function __construct()
  {

  }

  public function __get($name){
    switch(strtolower($name)){
      case 'xml':
        $this->responseFormat = 'xml';
        break;
      case 'json':
        $this->responseFormat = 'json';
        break;
    }
  }

  public function get_lastrequest(){
    return $this->lastrequesturl;
  }

  /**
   * Magic __call method, will translate all function calls to object to API requests
   * @param String $name name of the function
   * @param array $arguments an array of arguments
   * @return mixed
   */
  public function  __call($name, $arguments)
  {
    if (count($arguments) < 1 || !is_array($arguments[0])) {
      $arguments[0] = array();
    }
    $response = $this->APIcall($name, $arguments[0]);
    //return formatted xml, or decoded json.
    return $this->responseFormat == 'xml' ? simplexml_load_string($response) : GuzzleHttp\json_decode($response);
  }

  abstract protected function buildRequest($name, $arguments);
  abstract function setRequestType($format);
  /**
   * @param $name
   * @param $arguments
   * @return bool|mixed
   */
  private function APIcall($name, $arguments)
  {
    $url = $this->buildRequest($name, $arguments);
    $this->lastrequesturl = $url;
    $this->response = $this->curl_request($url);
    $this->setError(); //Set any error messages
    return $this->response['content'];
  }

  private function setError(){
    $this->error = array();
    $this->error['errno'] = $this->response['errno'];
    $this->error['errmsg'] = $this->response['errmsg'];
  }

  public function lastError(){
    return $this->error;
  }

  protected function curl_request($url)
  {
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', $url);

    $header['status'] = $res->getStatusCode();
    $header['content'] = $res->getBody();

    return $header;
  }
}
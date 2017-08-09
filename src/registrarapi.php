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
      case 'enom':
        $this->service  = '';
        break;
      case 'ghandi':
        break;
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
    /*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSLVERSION, 5);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100020);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 5 > 0);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);

    curl_close($ch);

    $header['errno'] = $err;
    $header['errmsg'] = $errmsg;
    $header['content'] = $content;*/

    $header['errno'] = $res->getStatusCode();
    //$header['errmsg'] = $errmsg;
    $header['content'] = $res->getBody();

    return $header;
  }
}
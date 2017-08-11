<?php

namespace registrarapi;

use registrarapi\enom\enom;

class Client{

  private $provider;
  private $providerObject;
  private $providerAccessObject;
  private $accessDetails;

  public function __construct($access, $provider = '')
  {
    $this->accessDetails = $access;
    if(!empty($provider)){
      $this->provider = $provider;
      $this->_instantiateProvider();
    }
  }

  public function __get($name){
    switch($name){
      case 'enom':
        $this->provider = 'enom';
        $this->_instantiateProvider();
        break;
    }
    return $this;
  }

  /**
   * Magic PHP __call() function. Pass any request to the provider object which will manage it
   * @param $name
   * @param $arguments
   */
  public function __call($name, $arguments)
  {
    return $this->providerObject->{$name}($arguments[0]);
  }

  /**
   * Instantiate the required provider with platform specific access credentials
   */
  private function _instantiateProvider(){
    //grab the access details specific to the given provider.
    switch($this->provider){
      case 'enom':
        $this->_enomAccessDetails();
        $this->providerObject = new enom($this->providerAccessObject->account, $this->providerAccessObject->user, $this->providerAccessObject->password);
        break;
    }
  }

  /*
   * Function to test for and build the provider access object for enom connections.
   */
  private function _enomAccessDetails(){
    if(!isset($this->accessDetails['account'])){
      $this->throwClientException('The account key is undefined');
    }
    if(!isset($this->accessDetails['user'])){
      $this->throwClientException('The user key is undefined');
    }
    if(!isset($this->accessDetails['password'])){
      $this->throwClientException('The password key is undefined');
    }
    $this->providerAccessObject = (object)['account' => $this->accessDetails['account'], 'user' => $this->accessDetails['user'], 'password' => $this->accessDetails['password']];
  }

  /**
   * Exception wrapper. Allows us to throw exceptions from one place so it can be updated/changed later.
   * @param $message
   * @param int $code
   * @throws \ErrorException
   */
  private function throwClientException($message, $code = 500){
    throw new \ErrorException($message, $code);
  }
}
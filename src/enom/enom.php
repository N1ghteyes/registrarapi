<?php
namespace registrarapi\enom;

use registrarapi\registrarapi;

class enom extends registrarapi {

  private $account;
  private $uid;
  private $pass;

  public function __construct($account, $uid, $pass, $format = 'xml')
  {
    $this->account = $account;
    $this->uid = $uid;
    $this->pass = $pass;
    $this->setRequestType($format);
  }

  public function setRequestType($format)
  {
    $this->responseFormat = $format;
  }

  protected function buildRequest($name, $arguments)
  {
    $url = 'https://'.$this->account.'.enom.com/interface.asp?command='.$name."&uid=".$this->uid."&pw=".$this->pass."&responsetype=".$this->responseFormat;
    foreach ($arguments as $key => $value) {
      $url .= "&".$key . "=" . $value;
    }
    return $url;
  }
}
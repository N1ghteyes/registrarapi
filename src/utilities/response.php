<?php

namespace registrarapi\utilities\response;

/**
 * Class response
 * This class is designed to forcibly unify the responses from different registrar providers.
 * @package registrarapi\utilities\response
 */
abstract class Response{

  public function __construct($response)
  {
    $this->rawData = $response;
  }

  protected function buildResponse(){
    $response = (object)[];
    $response->request = $this->setRequestUrl();
    $response->postData = $this->postData();
  }

  abstract protected function setRequestUrl();
  abstract protected function postData();


  /**
   * Function to check that the provided response data has the required data in the correct format.
   */
  private function checkResponseFormat(){

  }
}
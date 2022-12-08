<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StorageController extends CI_Controller{
  public function index(string $path){
    $explodedUri = explode('StorageController/index', $this->uri->ruri_string());
    $path = end($explodedUri);

    response_file(STORAGE_PATH . "/public/$path");
  }
}

?>
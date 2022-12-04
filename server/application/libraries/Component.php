<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component{
  public function load(string $name, array $data = []){
    $ci = &get_instance();

    $ci->load->view("components/{$name}", $data);
  }
}
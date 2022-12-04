<?php

use App\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');

class Template{
  public function load(
    string $page, string $title = null, array $data = [], bool $withNavbar = true,
    array $meta = []
  ){
    $ci = &get_instance();

    $ci->load->view("main/template", [
      'user' => $ci->session->userdata('user'),
      'page' => $page,
      'title' => $title,
      'withNavbar' => $withNavbar,
      'meta' => (object) $meta,
      'data' => $data
    ]);
  }
}
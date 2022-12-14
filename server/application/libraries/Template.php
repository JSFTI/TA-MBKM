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

  public function show_404(){
    $this->load('404', 'Page Not Found', [], false, [
			'robots' => 'noindex',
			'description' => 'Page not found.'
		]);
  }

  public function show_403(){
    $this->load('403', 'Forbidden Access', [], false, [
      'robots' => 'noindex',
			'description' => 'Forbidden.'
    ]);
  }

  public function show_401(){
    $this->load('401', 'Unauthorized', [], false, [
      'robots' => 'noindex',
			'description' => 'Unauthorized.'
    ]);
  }
}
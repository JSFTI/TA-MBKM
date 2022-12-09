<?php

use App\Models\Category;
use App\Models\Tag;

defined('BASEPATH') OR exit('No direct script access allowed');

class TagController extends CI_Controller{
  public function index(){
    $tags = Tag::withCount('products');

    if($this->input->get('search')){
      $search = $this->input->get('search');
      $tags->where('name', 'LIKE', "%{$search}%");
    }

    if($this->input->get('limit')){
      $tags->limit($this->input->get('limit'));
    }

    $tags = $tags->get();

    response_json($tags);
  }
}
?>
<?php

use App\Models\Category;

defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryController extends CI_Controller{
  public function index(){
    response_json(Category::all());
  }
}
?>
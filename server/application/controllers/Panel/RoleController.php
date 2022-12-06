<?php

use App\Models\Role;

defined('BASEPATH') OR exit('No direct script access allowed');

class RoleController extends CI_Controller{
  public function index(){
    return response_json(Role::all());
  }
}
?>
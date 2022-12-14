<?php

use App\Models\Role;

defined('BASEPATH') OR exit('No direct script access allowed');

class RoleController extends CI_Controller{
  public function index(){
    $user = auth();
		if(!$user || !$user->role || !in_array($user->role->name, ['admin', 'staff'])){
			$this->template->show_401();
			return;
		}
    
    return response_json(Role::all());
  }
}
?>
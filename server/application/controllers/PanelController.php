<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PanelController extends CI_Controller {
  public function index(){
		$user = auth();
		if(!$user || !$user->role || !in_array($user->role->name, ['admin', 'staff'])){
			$this->template->show_401();
			return;
		}
		$this->load->view('panel/index');
	}
}
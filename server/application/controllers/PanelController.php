<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PanelController extends CI_Controller {
  public function index(){
		$this->load->view('panel/index');
	}
}
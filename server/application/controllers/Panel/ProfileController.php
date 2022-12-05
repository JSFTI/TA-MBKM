<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileController extends CI_Controller{
  public function index(){
    $user = auth();
    
    if(!$user){
      response_json(['message' => 'Unauthorized'], 401);
      return;
    }

    response_json(auth());
  }
}

?>
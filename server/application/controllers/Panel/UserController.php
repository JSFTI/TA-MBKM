<?php

use App\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller{
  private array $validationRules;
  // TODO: Create dynamic validation rules
  public function __invoke(){

  }

  public function edit(int $user_id){
    $user = User::find($user_id);
    if(!$user){
      response_json(['message' => 'Not Found'], 404);
      return;
    }

    $unprocessedPost = get_input_json(true);

    $allowedKeys = ['email', 'name'];

    $post = [];
    foreach($unprocessedPost as $key => $value){
      if(in_array($key, $allowedKeys)){
        $post[$key] = $value;

      }
    }

    $this->form_validation->set_data($post);

    $user->fill($post);
    $user->save();

    return response_json($user);
  }
}
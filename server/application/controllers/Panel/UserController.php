<?php

use App\Models\User;
use App\Traits\CustomValidator;

defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller{
  use CustomValidator;

  public function edit(int $user_id){
    $user = User::find($user_id);
    if(!$user){
      response_json(['message' => 'Not Found'], 404);
      return;
    }

    $rules = [
      'name' => [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'trim|required|max_length[255]',
        'errors' => [
          'required' => 'Please provide a name',
          'max_length' => 'Maximum length for name is 255 characters'
        ]
      ],
      'email' => [
        'field' => 'email',
        'label' => 'Email',
        'rules' => "trim|required|valid_email|callback_value_is_unique[users.email.{$user_id}]",
        'errors' => [
          'required' => 'Please provide an email',
          'valid_email' => 'Email is invalid',
          'value_is_unique' => 'Email is not unique',
        ],
      ]  
    ];

    $unprocessedPost = get_input_json(true);

    $allowedKeys = ['email', 'name'];

    $post = [
      'id' => $user_id
    ];

    foreach($unprocessedPost as $key => $value){
      if(in_array($key, $allowedKeys)){
        $post[$key] = $value; 
      }
    }
    
    $this->form_validation->set_data($post);
  
    foreach($unprocessedPost as $key => $value){
      if(in_array($key, $allowedKeys)){
        $this->form_validation->set_rules(
          $rules[$key]['field'],
          $rules[$key]['label'],
          $rules[$key]['rules'],
          $rules[$key]['errors']
        );
      }
    }

    if(!$this->form_validation->run()){
      response_json([
        'message' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    $user->fill($post);
    $user->save();

    response_json($user);
  }
}
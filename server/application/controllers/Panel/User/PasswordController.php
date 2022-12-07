<?php

use App\Models\User;
use App\Traits\CustomValidator;

defined('BASEPATH') OR exit('No direct script access allowed');

class PasswordController extends CI_Controller{
  use CustomValidator;

  public function update(int $user_id){
    $user = User::find($user_id, ['id', 'password']);
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $post = get_input_json(TRUE);

    $this->form_validation->set_data($post);

    $this->form_validation->set_rules([
      [
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'required',
        'errors' => [
          'required' => 'Please provide a password'
        ]
      ],
      [
        'field' => 'repeatPassword',
        'label' => 'Repeat Password',
        'rules' => 'callback_required_if_exists[password]|callback_optional_match[password]',
        'errors' => [
          'required_if_exists' => 'Please confirm new password',
          'optional_match' => 'Password mismatch'
        ]
      ]
    ]);

    if(!$this->form_validation->run()){
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    $user->password = password_hash($post['password'], PASSWORD_ARGON2ID);
    $user->save();

    http_response_code(204);
  }
}
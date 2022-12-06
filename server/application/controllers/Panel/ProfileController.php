<?php

use App\Models\User;
use App\Traits\CustomValidator;

defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileController extends CI_Controller{
  use CustomValidator;

  public function index(){
    $user = auth();
    
    if(!$user){
      response_json(['message' => 'Unauthorized'], 401);
      return;
    }

    response_json(auth());
  }

  public function changePassword(){
    $user = auth();

    if(!$user){
      response_json(['message' => 'Unauthorized'], 401);
      return;
    }

    $post = get_input_json(TRUE);

    $this->form_validation->set_data($post);

    $this->form_validation->set_rules([
      [
        'field' => 'oldPassword',
        'label' => 'Old Password',
        'rules' => 'required|callback_match_current_password',
        'errors' => [
          'required' => 'Please provide old password',
          'match_current_password' => 'Password incorrect'
        ]
      ],
      [
        'field' => 'newPassword',
        'label' => 'New Password',
        'rules' => 'required',
        'errors' => [
          'required' => 'Please provide a new password'
        ]
      ],
      [
        'field' => 'repeatPassword',
        'label' => 'Repeat Password',
        'rules' => 'callback_required_if_exists[newPassword]|callback_optional_match[newPassword]',
        'errors' => [
          'required_if_exists' => 'Please confirm new password',
          'optional_match' => 'Password mismatch'
        ]
      ]
    ]);

    if(!$this->form_validation->run()){
      response_json([
        'message' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    $user = User::find($user->id, ['id', 'password']);
    $user->password = password_hash($post['newPassword'], PASSWORD_ARGON2ID);
    $user->save();

    http_response_code(204);
  }
}

?>
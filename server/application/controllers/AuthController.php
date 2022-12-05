<?php

use App\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {
	public function login(){
    if($this->input->method(true) === 'GET'){
      $this->template->load('login', 'Login', withNavbar: false, meta: [
        'description' => 'Login to Arkastore'
      ]);

    } else if($this->input->method(true) === 'POST') {
      $user = User::select('id', 'role_id', 'profile_picture', 'name', 'email', 'password')
        ->where('email', $this->input->post('email'))
        ->with('role')
        ->first();

      $this->form_validation->set_rules([
        [
          'field' => 'email',
          'label' => 'Email',
          'rules' => 'required|valid_email',
          'errors' => [
            'required' => 'Email is required',
            'valid_email' => 'Email format is invalid'
          ]
        ],
        [
          'field' => 'password',
          'label' => 'Password',
          'rules' => 'required',
          'errors' => [
            'required' => 'Password is required'
          ]
        ]
      ]);
      
      if($this->form_validation->run() === FALSE){
        $this->session->set_flashdata('validation_errors', $this->form_validation->error_array());
        $this->session->set_flashdata('old_values', $_POST);
        redirect(base_url('/login?back_url=' . urlencode($_GET['back_url'])));
        return;
      }

      $invalidFeedbacks = [];

      if(!$user){
        $invalidFeedbacks['email'] = 'Email not found';

        $this->session->set_flashdata('validation_errors', $invalidFeedbacks);
        $this->session->set_flashdata('old_values', $_POST);
        redirect(base_url('/login?back_url=' . urlencode($_GET['back_url'])));
        return;
      }

      if(!password_verify($this->input->post('password'), $user->password)){
        $invalidFeedbacks['password'] = 'Wrong password';

        $this->session->set_flashdata('validation_errors', $invalidFeedbacks);
        $this->session->set_flashdata('old_values', $_POST);
        redirect(base_url('/login?back_url=' . urlencode($_GET['back_url'])));
        return;
      }

      $user = $user->toArray();
      unset($user['password']);

      $this->session->set_userdata('user', $user);

      $backUrl = urldecode($_GET['back_url']);
      if(!validate_url($backUrl)){
        redirect(base_url());
        return;
      }
      redirect($backUrl);
    }
	}

  public function logout(){
    $this->session->unset_userdata('user');
    redirect(base_url('/login'));
  }
}

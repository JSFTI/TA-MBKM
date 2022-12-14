<?php

use App\Models\Role;
use App\Models\User;
use App\Traits\CustomValidator;

defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller{
  use CustomValidator;

  public function index(){
    if(!auth()->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $user = User::query();
    
    $limit = $this->input->get('limit') ?: 10;
    $search = $this->input->get('search');
    $page = $this->input->get('page');

    if($search){
      $user->where('name', 'LIKE', "%$search%");
    }
    
    return response_json($user->paginate($limit, page: $page)); 
  }

  public function show(int $user_id){
    $auth = auth();

    if($auth->id !== $user_id && !$auth->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $user = User::find($user_id);
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    return response_json($user);
  }

  public function edit(int $user_id){
    $auth = auth();

    if($auth->id !== $user_id && !$auth->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $user = User::find($user_id);
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
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
        'status' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }
    
    if(array_key_exists('role_id', $post)){
      $role = Role::find($post['role_id'], ['name'])->name;
      $auth = auth();

      if($auth->role->name === 'admin' && $role !== 'admin'){
        $otherAdminCount = User::where('role_id', $auth->role->id)
          ->where('id', '!=', $user_id)
          ->count();

        if($otherAdminCount === 0){
          return response_json([
            'status' => 'Bad Request',
            'errors' => [
              'role_id' => 'You are the only manager remaining.'
            ]
          ], 400);
        }
      }
    }

    $user->fill($post);
    $user->save();

    if($user->id === auth()->id){
      re_auth();
    }

    response_json($user);
  }

  public function update(int $user_id){
    $auth = auth();

    if($auth->id !== $user_id && !$auth->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }
    
    $user = User::find($user_id);
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $post = get_input_json(TRUE);

    $this->form_validation->set_data($post);
    
    $this->form_validation->set_rules([
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'trim|required|max_length[255]',
        'errors' => [
          'required' => 'Please provide a name',
          'max_length' => 'Maximum length for name is 255 characters'
        ]
      ],[
        'field' => 'email',
        'label' => 'Email',
        'rules' => "trim|required|valid_email|callback_value_is_unique[users.email.{$user_id}]",
        'errors' => [
          'required' => 'Please provide an email',
          'valid_email' => 'Email is invalid',
          'value_is_unique' => 'Email is not unique',
        ],
      ],[
        'field' => 'role_id',
        'label' => 'Role',
        'rules' => "required|callback_exists[roles.id]",
        'errors' => [
          'required' => 'Please give a role for the user',
          'exists' => 'Role not found'
        ],
      ]
    ]);

    if(!$this->form_validation->run()){
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    $role = Role::find($post['role_id'], ['name'])->name;
    $auth = auth();

    if($auth->role->name === 'admin' && $role !== 'admin'){
      $otherAdminCount = User::where('role_id', $auth->role->id)
        ->where('id', '!=', $user_id)
        ->count();

      if($otherAdminCount === 0){
        return response_json([
          'status' => 'Bad Request',
          'errors' => [
            'role_id' => 'You are the only manager remaining.'
          ]
        ], 400);
      }
    }

    $user->name = $post['name'];
    $user->email = $post['email'];
    $user->role_id = $post['role_id'];
    $user->save();

    if($user->id === auth()->id){
      re_auth();
    }

    response_json($user);
  }

  public function create(){
    if(!auth()->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $post = get_input_json(TRUE);

    $this->form_validation->set_data($post);
    
    $this->form_validation->set_rules([
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'trim|required|max_length[255]',
        'errors' => [
          'required' => 'Please provide a name',
          'max_length' => 'Maximum length for name is 255 characters'
        ]
      ],[
        'field' => 'email',
        'label' => 'Email',
        'rules' => "trim|required|valid_email|callback_value_is_unique[users.email]",
        'errors' => [
          'required' => 'Please provide an email',
          'valid_email' => 'Email is invalid',
          'value_is_unique' => 'Email is not unique',
        ],
      ],[
        'field' => 'role_id',
        'label' => 'Role',
        'rules' => "required|callback_exists[roles.id]",
        'errors' => [
          'required' => 'Please give a role for the user',
          'exists' => 'Role not found'
        ],
      ],
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

    $user = new User();
    $user->name = $post['name'];
    $user->email = $post['email'];
    $user->role_id = $post['role_id'];
    $user->password = password_hash($post['password'], PASSWORD_ARGON2ID);
    $user->save();

    response_json($user);
  }

  public function destroy(int $user_id){
    if(!auth()->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }
    
    $user = User::find($user_id);
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $auth = auth();

    if($user->id === $auth->id){
      response_json([
        'status' => 'Bad Request',
        'message' => 'Can\'t delete self.'
      ], '400');
    }
    
    $user->delete();

    http_response_code(204);
  }
}
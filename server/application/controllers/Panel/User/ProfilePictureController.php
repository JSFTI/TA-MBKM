<?php

use App\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');

class ProfilePictureController extends CI_Controller{
  public function index(int $user_id){
    $profile_picture = User::select('profile_picture')->find($user_id)?->profile_picture;
    if(!$profile_picture){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    response_file(realpath(STORAGE_PATH . "/profile_pictures/$profile_picture"));
  }

  public function update(int $user_id){
    $user = User::select('id', 'profile_picture')->find($user_id);
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $filename = random_string(len: 20);

    $this->load->library('upload', [
      'upload_path' => realpath(STORAGE_PATH . '/profile_pictures'),
      'allowed_types' => 'gif|jpg|png|jpeg|webp',
      'max_size' => 4024,
      'file_name' => $filename
    ]);

    if(!$this->upload->do_upload('image')){
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->upload->error_msg
      ], 422);
      return;
    }

    if($user->profile_picture){
      $path = realpath(STORAGE_PATH . "/profile_pictures/{$user->profile_picture}");

      if(file_exists($path)){
        unlink($path);
      }
    }

    $user->profile_picture = $this->upload->data()['file_name'];
    $user->save();

    response_json([
      'profile_picture' => $user->profile_picture
    ]);
  }

  public function destroy(int $user_id){
    $auth = auth();

    if($auth->id !== $user_id && $auth->permissions->contains('crud-users')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }
    
    $user = User::select('id', 'profile_picture')->find($user_id);
    
    if(!$user){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $path = realpath(STORAGE_PATH . "/profile_pictures/{$user->profile_picture}");

    if(file_exists($path)){
      unlink($path);
    }

    $user->profile_picture = null;
    $user->save();

    http_response_code(204);
  }
}
?>
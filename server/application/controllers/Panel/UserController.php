<?php

use App\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileController extends CI_Controller{
  public function edit(int $user_id){
    $user = User::select('id')->find($user_id);
    if(!$user){
      response_json(['message' => 'Not Found'], 404);
      return;
    }

    $post = collect($_POST);

    // User::where('id', $user_id)->update($_POST);

    response_json($post);
  }
}
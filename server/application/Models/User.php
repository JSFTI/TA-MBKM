<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model;

class User extends Model {
  public function role(){
    return $this->belongsTo(Role::class);
  }
}
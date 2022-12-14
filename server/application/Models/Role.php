<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  public function permissions(){
    return $this->hasMany(Permission::class);
  }
}
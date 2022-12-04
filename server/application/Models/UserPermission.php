<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPermission extends Pivot {
  public function user(){
    return $this->belongsTo(User::class);
  }

  public function permission(){
    return $this->belongsTo(Permission::class);
  }
}
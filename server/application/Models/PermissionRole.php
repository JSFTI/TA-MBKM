<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot {
  public function role(){
    return $this->belongsTo(Role::class);
  }

  public function permission(){
    return $this->belongsTo(Permission::class);
  }
}
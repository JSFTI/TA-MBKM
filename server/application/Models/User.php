<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model;

class User extends Model {
  protected $appends = ['permissions'];
  protected $fillable = ['name', 'email'];
  protected $hidden = ['password', 'password_token'];

  public function role(){
    return $this->belongsTo(Role::class);
  }

  public function getPermissionsAttribute(){
    $role = $this->role;

    if(!$this->role_id || !$this->id){
      return null;
    }

    if(!$role){
      $role = Role::select('id')->where('id', $this->role_id);
    }

    $userPermissions = UserPermission::where('user_id', $this->id)
      ->with('permission')
      ->get()
      ->map(fn($x) => $x->permission->name)
      ->toBase();

    $rolePermissions = PermissionRole::where('role_id', $this->role_id)
      ->with('permission')
      ->get()
      ->map(fn($x) => $x->permission->name)
      ->toBase();

    return $userPermissions->merge($rolePermissions->toArray());
  }
}
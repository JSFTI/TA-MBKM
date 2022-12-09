<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
  public $timestamps = false;

  public function products(){
    return $this->belongsToMany(Product::class);
  }
}
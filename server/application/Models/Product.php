<?php
namespace App\Models;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
  protected $casts = [
    'published' => 'boolean'
  ];

  public function tags(){
    return $this->belongsToMany(Tag::class);
  }

  public function category(){
    return $this->belongsTo(Category::class);
  }
}
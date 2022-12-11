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

  public function images(){
    return $this->hasMany(ProductImage::class)->orderByRaw("ISNULL(priority), priority ASC");
  }

  public function thumbnail(){
    return $this->belongsTo(ProductImage::class, 'thumbnail_id', 'id');
  }
}
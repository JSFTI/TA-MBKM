<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller{
  public function index(){
    $category = Category::select('id')
      ->firstWhere(fn($q) => $q->where('name', $this->input->get('category')));
    $name = $this->input->get('name');
    $price = $this->input->get('price');
    $stock = $this->input->get('stock');
    $tags = $this->input->get('tags');

    if(!$category){
      $this->template->show_404();
      return;
    }

    $products = Product::with('thumbnail')->where('published', true)
      ->where('category_id', $category->id);

    if($name){
      $products->where('name', 'LIKE', "%$name%");
    }

    if($tags){
      $products->whereHas('tags', fn($q) => $q->whereIn('name', $tags));
    } 

    if($price){
      if(is_array($price)){
        if(isset($price['gte'])){
          $products->where('price', '>=', $price['gte']);
        }
    
        if(isset($price['lte'])){
          $products->where('price', '<=', $price['lte']);
        }
      }
    }
    
    if($stock){
      if(is_array($stock)){
        if(isset($stock['gte'])){
          $products->where('stock', '>=', $stock['gte']);
        }
    
        if(isset($stock['lte'])){
          $products->where('stock', '<=', $stock['lte']);
        }
      }
    }

    $tags = Tag::whereHas('products', fn($q) => $q->where('category_id', $category->id))
      ->withCount(['products' => fn($q) => $q->where('category_id', $category->id)])
      ->orderBy('products_count', 'DESC')
      ->limit(7)
      ->get();

    $data = [
      'category' => $category,
      'products' => json_decode(json_encode($products->paginate(40, page: $this->input->get('page') ?? 1))),
      'tags' => $tags
    ];

    $this->template->load('products', 'Arkastore Products', $data, meta: [
      'description' => 'See our list of interesting products'
    ]);
  }

  public function show(string $slug){
    $product = Product::with(['thumbnail', 'images', 'tags'])
      ->where('slug', $slug)->first();

    $data = [
      'product' => $product
    ];

    $this->template->load('product', $product->name, $data, meta: [
      'description' => "Details of {$product->name}",
      'keywords' => $product->tags->map(fn($x) => $x->name)->join(', ')
    ]);
  }
}
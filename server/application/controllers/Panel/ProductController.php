<?php

use App\Models\Product;
use App\Models\Tag;
use App\Traits\CustomValidator;
use Illuminate\Database\Capsule\Manager as DB;

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller{
  use CustomValidator;

  public function index(){
    $product = Product::with(['category', 'tags']);
    
    $limit = $this->input->get('limit') ?: 10;
    $search = $this->input->get('search');
    $page = $this->input->get('page');
    $categories = $this->input->get('categories');
    $tags = $this->input->get('tags');
    $price = $this->input->get('price');
    $minPrice = $this->input->get('minPrice');
    $maxPrice = $this->input->get('maxPrice');
    $published = $this->input->get('published');

    if($search){
      $product->where('name', 'LIKE', "%$search%");
    }

    if($categories){
      $product->whereHas('category', fn($q) => $q->whereIn('name', $categories));
    }

    if($tags){
      $product->whereHas('tags', fn($q) => $q->whereIn('name', $tags));
    }

    if($price){
      $product->where('price', $price); 
    }

    if($minPrice){
      // echo $minPrice;
      $product->where('price', '>=', $minPrice);
    }

    if($maxPrice){
      $product->where('price', '<=', $maxPrice);
    }

    if($published !== null){
      $product->where('published', $published);
    }
    
    return response_json($product->paginate($limit, page: $page)); 
  }

  public function create(){
    $post = get_input_json(TRUE);

    $this->form_validation->set_data($post);

    $this->form_validation->set_rules([
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'required|max_length[255]',
        'errors' => [
          'required' => 'Please provide a display name',
          'max_length' => 'Maximum length for display name is 255 characters'
        ]
      ],
      [
        'field' => 'price',
        'label' => 'Price',
        'rules' => 'required|numeric',
        'errors' => [
          'required' => 'Please provide a price',
          'numeric' => 'Please provide a numeric value',
        ]
      ],
      [
        'field' => 'detail',
        'label' => 'Detail',
        'rules' => 'max_length[65535]',
        'errors' => [
          'max_length' => 'Detail too long'
        ]
      ],
      [
        'field' => 'stock',
        'label' => 'Stock',
        'rules' => 'numeric',
        'errors' => [
          'numeric' => 'Please provide a numeric value',
        ]
      ],
      [
        'field' => 'category_id',
        'label' => 'Category',
        'rules' => 'required|callback_exists[categories.id]',
        'errors' => [
          'required' => 'Please provide product\'s category',
          'exists' => 'Category does not exist'
        ]
      ],
    ]);

    if(!$this->form_validation->run()){
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    DB::connection()->beginTransaction();

    $product = new Product();

    $product->name = $post['name'];
    $product->category_id = $post['category_id'];
    $product->price = $post['price'];
    $product->stock = $post['stock'] ?? null;
    $product->published = $post['published'] ?: false;

    $product->save();

    Tag::upsert(
      array_map(fn($x) => ['name' => $x], $post['tags']),
      ['name']
    );

    $tags = Tag::whereIn('name', $post['tags'])->get();

    $product->tags()->sync($tags);

    DB::connection()->commit();

    response_json($product);
  }

  public function update(int $product_id){

  }

  public function destroy(int $product_id){

  }
}
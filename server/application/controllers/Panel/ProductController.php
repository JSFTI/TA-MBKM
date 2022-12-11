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
    $stock = $this->input->get('stock');
    $published = $this->input->get('published');
    $sortBy = $this->input->get('sortBy');
    $select = $this->input->get('select');

    if($this->input->get('relations')){
      $product->with($this->input->get('relations'));
    }

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
      if(is_array($price)){
        if(isset($price['gte'])){
          $product->where('price', '>=', $price['gte']);
        }
    
        if(isset($price['lte'])){
          $product->where('price', '<=', $price['lte']);
        }
      } else {
        $product->where('price', $price);
      }
    }
    
    if($stock){
      if(is_array($stock)){
        if(isset($stock['gte'])){
          $product->where('stock', '>=', $stock['gte']);
        }
    
        if(isset($stock['lte'])){
          $product->where('stock', '<=', $stock['lte']);
        }
      } else if($stock === 'infinite') {
        $product->whereNull('stock');
      } else {
        $product->where('stock', $stock);
      }
    }

    if($published !== null){
      $product->where('published', $published);
    }

    if($sortBy){
      $sortBy = explode(',', $sortBy);

      foreach($sortBy as $sort){
        $field = substr($sort, 1);
        $product->orderByRaw("ISNULL($field), $field " . ($sort[0] === '-' ? 'DESC' : 'ASC'));
      }
    }

    if($select){
      $select = explode(',', $select);
      if(!in_array('id', $select)){
        array_unshift($select, 'id');
      }

      if(!in_array('thumbnail_id', $select)){
        array_unshift($select, 'thumbnail_id');
      }

      $product->select($select);
    }
    
    return response_json($product->paginate($limit, page: $page)); 
  }

  public function show(int $product_id){
    $select = $this->input->get('select');

    $product = Product::query();

    if($this->input->get('relations')){
      $product->with($this->input->get('relations'));
    }

    if($select){
      $select = explode(',', $select);
      if(!in_array('id', $select)){
        array_unshift($select, 'id');
      }

      if(!in_array('thumbnail_id', $select)){
        array_unshift($select, 'thumbnail_id');
      }

      $product->select($select);
    }

    $product = $product->find($product_id);

    if(!$product){
      return response_json(['status' => 'Not Found'], 404);
    }

    response_json($product);
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
      [
        'field' => 'slug',
        'label' => 'Slug',
        'rules' => 'required|callback_value_is_unique[products.slug]',
        'errors' => [
          'required' => 'Please provide a URL slug',
          'value_is_unique' => 'Slug already used by another product'
        ]
      ]
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
    $product->slug = $post['slug'];
    $product->category_id = $post['category_id'];
    $product->price = $post['price'];
    $product->stock = $post['stock'] ?? null;
    $product->detail = $post['detail'] ?: null;
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
    $product = Product::find($product_id);

    if(!$product){
      return response_json(['status' => 'Not Found'], 404);
    }

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
      [
        'field' => 'slug',
        'label' => 'Slug',
        'rules' => "required|callback_value_is_unique[products.slug.{$product_id}]",
        'errors' => [
          'required' => 'Please provide a URL slug',
          'value_is_unique' => 'Slug already used by another product'
        ]
      ]
    ]);

    if(!$this->form_validation->run()){
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    DB::connection()->beginTransaction();

    $product->name = $post['name'];
    $product->slug = $post['slug'];
    $product->category_id = $post['category_id'];
    $product->price = $post['price'];
    $product->stock = $post['stock'] ?? null;
    $product->detail = $post['detail'] ?: null;
    $product->published = $post['published'] ?: false;

    $product->save();

    Tag::upsert(
      array_map(fn($x) => ['name' => $x], $post['tags']),
      ['name']
    );

    $tags = Tag::whereIn('name', $post['tags'])->get();

    $product->tags()->sync($tags);

    DB::connection()->commit();
  }

  public function destroy(int $product_id){
    $product = Product::with('images')->find($product_id);

    if(!$product){
      return response_json(['status' => 'Not Found'], 404);
    }

    if($product->published){
      return response_json([
        'status' => 'Bad Request',
        'message' => 'Cannot delete published product'
      ], 400);
    }

    DB::connection()->beginTransaction();

    foreach($product->images as $image){
      if(file_exists(STORAGE_PATH . "/public/products/{$image->filename}")){
        unlink(STORAGE_PATH . "/public/products/{$image->filename}");
      }
    }
    
    $product->delete();

    DB::connection()->commit();
  }
}
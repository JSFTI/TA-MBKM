<?php

use App\Models\Product;
use App\Traits\CustomValidator;

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController{
  use CustomValidator;

  public function index(){

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

    $product = new Product();

    $product->name = $post['name'];
    $product->category_id = $post['category_id'];
    $product->price = $post['price'];
    $product->stock = $post['stock'] ?: null;
    $product->published = $post['published'] ?: false;

    $product->save();

    response_json($product);
  }

  public function update(int $product_id){

  }

  public function destroy(int $product_id){

  }
}
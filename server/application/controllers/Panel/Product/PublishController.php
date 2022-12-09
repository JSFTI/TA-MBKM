<?php

use App\Models\Product;

defined('BASEPATH') OR exit('No direct script access allowed');

class PublishController extends CI_Controller{
  public function index(string $verb, int $product_id){
    $product = Product::find($product_id, ['id', 'published']);
    if(!$product){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $product->published = $verb === 'update';
    $product->save();

    http_response_code(204);
  }
}
?>
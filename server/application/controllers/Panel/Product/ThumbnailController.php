<?php

use App\Models\Product;
use App\Traits\CustomValidator;

defined('BASEPATH') OR exit('No direct script access allowed');

class ThumbnailController extends CI_Controller{
  use CustomValidator;

  public function update(int $product_id){
    if(!auth()->permissions->contains('publish-product')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }
    
    $product = Product::find($product_id, ['id', 'thumbnail_id']);
    if(!$product){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $post = get_input_json(TRUE);

    $this->form_validation->set_data($post);

    $this->form_validation->set_rules(
      'thumbnail_id',
      'Thumbnail ID',
      'callback_exists[product_images.id]',
    );

    if(!$this->form_validation->run()){
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->form_validation->error_array()
      ], 422);
      return;
    }

    $product->thumbnail_id = $post['thumbnail_id'] ?: null;
    $product->save();

    http_response_code(204);
  }
}
?>
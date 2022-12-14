<?php

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Capsule\Manager as DB;

defined('BASEPATH') OR exit('No direct script access allowed');

class ImageController extends CI_Controller{
  public function index(int $product_id){
    $product = Product::find($product_id, ['id']);
    
    if(!$product){
      return response_json(['status' => 'Not Found'], 404);
    }

    $images = ProductImage::where('product_id', $product_id)->get();

    return response_json($images);
  }

  public function create(int $product_id){
    $product = Product::find($product_id, ['id']);
    
    if(!$product){
      return response_json(['status' => 'Not Found'], 404);
    }

    $this->load->library('upload', [
      'upload_path' => realpath(STORAGE_PATH . '/public/products'),
      'allowed_types' => 'gif|jpg|png|jpeg|webp',
      'max_size' => 8192,
    ]);

    if(!$this->upload->do_multi_upload("images")) {
      response_json([
        'status' => 'Unprocessable Entity',
        'errors' => $this->upload->error_msg
      ], 422);
      return;
    }

    $uploadedFiles = $this->upload->get_multi_upload_data();
    $images = [];
    
    foreach($uploadedFiles as $file){
      $image = new ProductImage();
      $image->product_id = $product_id;
      $image->filename = $file['file_name'];
      $image->url = base_url("/storage/products/{$file['file_name']}");
      $image->save();

      $images[] = $image;
    }

    response_json($images);
  }

  public function edit(int $product_id){
    if(!auth()->permissions->contains('crud-product')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $product = Product::find($product_id, ['id']);
    
    if(!$product){
      return response_json(['status' => 'Not Found'], 404);
    }
    
    $images = get_input_json();
    
    DB::connection()->beginTransaction(); 

    if(is_array($images)){
      for($i = 0; $i < count($images); $i++){
        ProductImage::where('id', $images[$i]->id)->update([
          'priority' => $images[$i]->priority
        ]);
      }
    }

    DB::connection()->commit(); 
    
    http_response_code(204);
  }

  public function destroy(int $image_id){
    if(!auth()->permissions->contains('crud-product')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $image = ProductImage::find($image_id, ['id', 'filename']);

    if(!$image){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    if(file_exists(STORAGE_PATH . "/public/products/{$image->filename}")){
      unlink(STORAGE_PATH . "/public/products/{$image->filename}");
    }

    $image->delete();

    http_response_code(204);
  }
}
?>
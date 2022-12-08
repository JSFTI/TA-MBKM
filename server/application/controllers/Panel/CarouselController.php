<?php

use App\Models\Carousel;

defined('BASEPATH') OR exit('No direct script access allowed');

class CarouselController extends CI_Controller{
  public function index(){
    $carousels = Carousel::all();

    return response_json($carousels);
  }

  public function create(){
    $this->load->library('upload', [
      'upload_path' => realpath(STORAGE_PATH . '/public/carousels'),
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
    $carousels = [];
    
    foreach($uploadedFiles as $file){
      $carousel = new Carousel();
      $carousel->filename = $file['file_name'];
      $carousel->url = base_url("/storage/carousels/{$file['file_name']}");
      $carousel->save();

      $carousels[] = $carousel;
    }

    response_json($carousels);
  }

  public function destroy(int $carousel_id){
    $carousel = Carousel::find($carousel_id, ['id', 'filename']);

    if(!$carousel){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    if(file_exists(PUBLIC_PATH . "/carousels/{$carousel->filename}")){
      unlink(PUBLIC_PATH . "/carousels/{$carousel->filename}");
    }

    $carousel->delete();

    http_response_code(204);
  }
}
?>
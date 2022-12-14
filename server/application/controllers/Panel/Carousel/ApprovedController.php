<?php

use App\Models\Carousel;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;

defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovedController extends CI_Controller{
  public function update(int $carousel_id){
    if(!auth()->permissions->contains('approve-carousel')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $carousel = Carousel::find($carousel_id, ['id', 'approved']);

    if(!$carousel){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $carousel->approved = true;
    $carousel->approved_at = Carbon::now()->format('Y-m-d H:i:s');
    $carousel->priority = null;
    $carousel->save();

    response_json($carousel);
  }

  public function destroy(int $carousel_id){
    if(!auth()->permissions->contains('approve-carousel')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }

    $carousel = Carousel::find($carousel_id, ['id', 'approved']);

    if(!$carousel){
      response_json(['status' => 'Not Found'], 404);
      return;
    }

    $carousel->approved = false;
    $carousel->approved_at = null;
    $carousel->priority = null;
    $carousel->save();

    response_json($carousel);
  }

  public function updateOrder(){
    if(!auth()->permissions->contains('approve-carousel')){
      response_json(['status' => 'Forbidden'], 403);
      return;
    }
    
    $carousels = get_input_json();

    DB::connection()->beginTransaction(); 

    if(is_array($carousels)){
      for($i = 0; $i < count($carousels); $i++){
        Carousel::where('id', $carousels[$i]->id)->update([
          'priority' => $carousels[$i]->priority
        ]);
      }
    }

    DB::connection()->commit(); 
    
    http_response_code(204);
  }
}
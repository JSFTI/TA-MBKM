<?php

use App\Models\Carousel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovedController extends CI_Controller{
  public function update(int $carousel_id){
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
    $carousels = get_input_json();

    $this->db->trans_begin();

    if(is_array($carousels)){
      for($i = 0; $i < count($carousels); $i++){
        Carousel::where('id', $carousels[$i]->id)->update([
          'priority' => $carousels[$i]->priority
        ]);
      }
    }

    $this->db->trans_commit();
    
    http_response_code(204);
  }
}
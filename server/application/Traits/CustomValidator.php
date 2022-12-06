<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;

defined('BASEPATH') OR exit('No direct script access allowed');

trait CustomValidator{
  public function value_is_unique($value, $params){
    $params = explode('.', $params);
    $table = $params[0];
    $column = $params[1];
    $id = isset($params[2]) ? $params[2] : null;
    
    $row = DB::table($table)
      ->select(['id', $column])
      ->where($column, $value)
      ->first();
    
    if(!$row || ($id && $row->id == $id)){
      return true;
    }

    $this->form_validation->set_message('value_is_unique', "$column must be unique");
    return false;
  }

  public function exists($value, $param){
    if($value !== 0 && !$value){
      return true;
    }

    $explode = explode('.', $param);
    $table = $explode[0];
    $column = $explode[1];

    $count = $this->db->where($column, $value)->from($table)->count_all_results();

    if($count > 0){
      return true;
    }

    $this->form_validation->set_message('exists', "Value '$value' does not exist in database");
    return false;
  }

  public function after_date_field($value, $param){
    if($value !== 0 && !$value){
      return true;
    }

    $fieldValue = $this->form_validation->validation_data[$param];

    if(!is_date_format_iso($fieldValue)){
      return true;
    }

    $fieldDate = new Carbon($fieldValue);
    $value = new Carbon($value);

    if($value->gte($fieldDate)){
      return true;
    }

    $this->form_validation->set_message('after_date_field', "This date must be set after $param");
    return false;
  }

  public function valid_date($value){
    if(!is_date_format_iso($value)){
      $this->form_validation->set_message('valid_date', "Invalid date format. Use ISO YYYY-MM-DD");
      return false;
    }

    return true;
  }

  public function match_current_password($value){
    $user = auth();
    
    $userPassword = User::find($user->id, ['password'])->password;

    if(password_verify($value, $userPassword)){
      return true;
    }

    $this->form_validation->set_message('match_current_password', "Incorrect password");
    return false;
  }

  public function required_if_exists(string $value, $otherField){
    $otherField = $this->form_validation->validation_data[$otherField];

    if(!$otherField || strlen($value) > 0){
      return true;
    }

    $this->form_validation->set_message('required_if_exists', "Value required");
    return false;
  }
  
  public function optional_match(string $value, $otherField){
    $otherField = $this->form_validation->validation_data[$otherField];

    if(!$otherField || $value === $otherField){
      return true;
    }

    $this->form_validation->set_message('required_if_exists', "Value does not match $otherField");
    return false;
  }
}
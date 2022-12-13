<?php

use App\Models\Product;
use Faker\Factory;

defined('BASEPATH') OR exit('No direct script access allowed');

class Seeders extends CI_Controller{
  private $faker;

  public function __construct(){
    parent::__construct();
    $this->faker = Factory::create();
  }

  public function products(){
    if(!is_cli()){
      return;
    }
    
    $fakeNames = collect([
      'Arka T-Shirt', 'Arka Shirt', 'Arka Pin', 'Arka Pen', 'Arka Keychain',
      'Arka Tumbler', 'Arka Sticker'
    ]);

    for($i = 0; $i < 200; $i++){
      $product = new Product();
      $product->category_id = 2;
      $product->slug = $this->faker->slug();
      $product->name = $fakeNames->random();
      $product->price = round(random_int(10000, 200000), -3);
      $product->detail = $this->faker->paragraph();
      $product->stock = random_int(0, 10);
      $product->published = true;
      $product->save();
    }
  }
}

?>
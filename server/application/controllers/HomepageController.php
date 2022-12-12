<?php

use App\Models\Carousel;
use App\Models\Product;

defined('BASEPATH') OR exit('No direct script access allowed');

class HomepageController extends CI_Controller {
	public function index(){
		$carousels = Carousel::where('approved', 1)->orderBy('priority', 'ASC')->get();
		$products = Product::where('published', true)
			->with(['thumbnail', 'images'])
			->orderByRaw('RAND()')
			->limit(12)
			->get();

		$this->template->load('index', 'Welcome to Arkastore', compact('carousels', 'products'), meta: [
			'description' => 'An online shop that sells merchandise and applications.'
		]);
	}
}

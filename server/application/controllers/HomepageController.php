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
			->limit(12);

		$apps = (clone $products)
			->whereHas('category', fn($q) => $q->where('name', 'Application'))
			->get();
		$merchandises = (clone $products)
			->whereHas('category', fn($q) => $q->where('name', 'Merchandise'))
			->get();

		$this->template->load('index', 'Welcome to Arkastore', compact('carousels', 'apps', 'merchandises'), meta: [
			'description' => 'An online shop that sells merchandise and applications.'
		]);
	}

	public function notFound(){
		$this->template->show_404();
	}
}

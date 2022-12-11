<?php

use App\Models\Carousel;

defined('BASEPATH') OR exit('No direct script access allowed');

class HomepageController extends CI_Controller {
	public function index(){
		$carousels = Carousel::where('approved', 1)->get();

		$this->template->load('index', 'Welcome to Arkastore', [
			'carousels' => $carousels
		], meta: [
			'description' => 'An online shop that sells merchandise and applications.'
		]);
	}
}

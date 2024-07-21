<?php
	class Pages extends Controller {
		public function __construct() {
		}

		public function index() {
			$data = [
				'title' => 'Posts',
				'description' => 'Simple social network built on the Posts PHP framework'
			];

			$this->view('pages/index', $data);
		}

		public function home() {
			//if(isLoggedIn()) {
				return redirect('posts');
			//}

			//redirect('pages');
		}

		public function about($z = null, $y = null) {
			$data = [
				'title' => 'About Us',
				'description' => 'App to share posts with other users'
			];

			$this->view('pages/about', $data);
		}
	}
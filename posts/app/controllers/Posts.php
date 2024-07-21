<?php
	class Posts extends Controller {
		private $postModel;

		public function __construct() {
			//if(!isLoggedIn()) {
			//	return redirect('users/login');
			//}

			$this->postModel = $this->model('Post');
		}

		public function index() {
			$this->view('posts/index', ['posts' => $this->postModel->getPosts()]);
		}

		public function show($id = null) {
			if(isset($id))
			{
				$post = $this->postModel->getPostById($id);
				if($post) {
					// Load View
					return $this->view('posts/show', ['post' => $post, 'user' => $this->model('user')->getUserById($post->user_id)]);
				}
			}
			redirect('posts');
		}
		
		public function add() {
			if(!isLoggedIn()) {
				return redirect('users/login');
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Process form

				// Sanitize POST data
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				// Init data
				$data = [
					'title' => trim($_POST['title']),
					'body' => trim($_POST['body']),
					'user_id' => $_SESSION['user_id'],
					'title_err' => '',
					'body_err' => ''
				];

				// Validate title
				if(empty($data['title'])) {
					$data['title_err'] = 'Please enter title';
				}

				// Validate body
				if(empty($data['body'])) {
					$data['body_err'] = 'Please enter body text';
				}

				// Make sure no errors
				if(empty($data['title_err']) && empty($data['body_err'])) {
					// Validated
					if($this->postModel->addPost($data)) {
						flash('post_message', 'Post Added!');
						redirect('posts');
					} else {
						die('Something went wrong');
					}
				} else {
					// Load view with errors
					$this->view('posts/add', $data);
				}
			} else {
				// Init data
				$data = [
					'title' => '',
					'body' => '',
					'title_err' => '',
					'body_err' => ''
				];

				// Load View
				$this->view('posts/add', $data);
			}
		}

		public function edit($id = null) {
			if(!isLoggedIn()) {
				return redirect('users/login');
			}
			
			// Check if id is set
			if(!isset($id)) {
				return redirect('posts');
			}

			$post = $this->postModel->getPostById($id);
			// Check if post exists
			if(!$post) {
				return redirect('posts');
			}

			// Check for owner
			if($post->user_id != $_SESSION['user_id']) {
				return redirect('posts');
			}

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Process form

				// Sanitize POST data
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				// Init data
				$data = [
					'id' => $id,
					'title' => trim($_POST['title']),
					'body' => trim($_POST['body']),
					'title_err' => '',
					'body_err' => ''
				];

				// Validate title
				if(empty($data['title'])) {
					$data['title_err'] = 'Please enter title';
				}

				// Validate body
				if(empty($data['body'])) {
					$data['body_err'] = 'Please enter body text';
				}

				// Make sure no errors
				if(empty($data['title_err']) && empty($data['body_err'])) {
					// Validated
					if($this->postModel->updatePost($data)) {
						flash('post_message', 'Post Updated!');
						redirect('posts');
					} else {
						die('Something went wrong');
					}
				} else {
					// Load view with errors
					$this->view('posts/edit', $data);
				}
			} else {
				// Init data
				$data = [
					'id' => $id,
					'title' => $post->title,
					'body' => $post->body,
					'title_err' => '',
					'body_err' => ''
				];

				// Load View
				$this->view('posts/edit', $data);
			}
		}

		public function delete($id = null) {
			if(!isLoggedIn()) {
				return redirect('users/login');
			}
			
			if(isset($id))
			{
				$post = $this->postModel->getPostById($id);
				// Check if post exists
				if(!$post) {
					return redirect('posts');
				}

				// Check for owner
				if($post->user_id != $_SESSION['user_id']) {
					return redirect('posts');
				}

				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					if($this->postModel->deletePost($id)) {
						flash('post_message', 'Post Deleted!');
					} else {
						die('Something went wrong');
					}
				}
			}
			redirect('posts');
		}
	}
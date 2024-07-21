<?php
	class Users extends Controller {
		private $userModel;

		public function __construct() {
			$this->userModel = $this->model('User');
		}

		private static function isLoggedIn() {
			return isset($_SESSION['user_id']);
		}

		public function index() {
			if(self::isLoggedIn()) {
				redirect('pages/index');
			} else {
				redirect('users/login');
			}
		}

		public function register() {
			// Check if user is already logged in
			if(self::isLoggedIn()) {
				return redirect('pages/index');
			}

			// Check for POST method
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Process form

				// Sanitize POST data
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				// Init data
				$data = [
					'name' => trim($_POST['name']),
					'email' => trim($_POST['email']),
					'password' => trim($_POST['password']),
					'confirm_password' => trim($_POST['confirm_password']),
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				];

				// Validate Name
				if(empty($data['name']))
				{
					$data['name_err'] = 'Please enter name';
				}

				// Validate Email
				if(empty($data['email']))
				{
					$data['email_err'] = 'Please enter email';
				} else {
					// Check email
					if($this->userModel->findUserByEmail($data['email']))
						$data['email_err'] = 'This email is already taken';	
				}

				// Validate Password
				if(empty($data['password']))
				{
					$data['password_err'] = 'Please enter password';
				} elseif(strlen($data['password']) < 6) {
					$data['password_err'] = 'Please must be at least 6 characters';
				}

				// Validate Confirm Password
				if(empty($data['confirm_password']))
				{
					$data['confirm_password_err'] = 'Please confirm password';
				} else {
					if($data['password'] != $data['confirm_password']) {
						$data['confirm_password_err'] = 'Passwords do not match';
					}
				}

				// Make sure errors are empty
				if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
					// Validated

					// Hash password
					$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

					// Register User
					if($this->userModel->register($data)) {
						flash('register_success', 'You are registered and can log in');
						redirect('users/login');
					} else {
						die('Something went wrong');
					}
				}
				else {
					$this->view('users/register', $data);
				}
			} else {
				// Init data
				$data = [
					'name' => '',
					'email' => '',
					'password' => '',
					'confirm_password' => '',
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				];

				// Load View
				$this->view('users/register', $data);
			}
		}

		public function login() {
			// Check if user is already logged in
			if(self::isLoggedIn()) {
				return redirect('pages/index');
			}
			
			// Check for POST method
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Process form
				// Sanitize POST data
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

				// Init data
				$data = [
					'email' => trim($_POST['email']),
					'password' => trim($_POST['password']),
					'email_err' => '',
					'password_err' => ''
				];

				// Validate Email
				if(empty($data['email']))
				{
					$data['email_err'] = 'Please enter email';
				} else {
					// Check for email
					if(!$this->userModel->findUserByEmail($data['email'])) {
						$data['email_err'] = 'No user found';
					}
				}

				// Validate Password
				if(empty($data['password']))
				{
					$data['password_err'] = 'Please enter password';
				}

				// Make sure errors are empty
				if(empty($data['email_err']) && empty($data['password_err'])) {
					// Validated
					// Check and set logged in user
					$loggedInUser = $this->userModel->login($data['email'], $data['password']);
					if($loggedInUser)
					{
						// Create Session
						$this->createUserSession($loggedInUser);
					} else {
						$data['password_err'] = 'Password incorrect';
						$this->view('users/login', $data);
					}
				} else {
					$this->view('users/login', $data);
				}
			} else {
				// Init data
				$data = [
					'email' => '',
					'password' => '',
					'email_err' => '',
					'password_err' => ''
				];

				// Load View
				$this->view('users/login', $data);
			}
		}
		
		public function createUserSession($user = null) {
			// we can also use "$user instanceof stdClass" instead of "is_object($user)"
			// We can also change it be a private function since Core class won't allow calling a non-callable function (private)
			if(isset($user) && is_object($user) && !self::isLoggedIn()) {
				$_SESSION['user_id'] = $user->id;
				$_SESSION['user_name'] = $user->name;
				$_SESSION['user_email'] = $user->email;
				redirect('posts');
			} else {
				redirect('pages/index');
			}
		}

		public function logout() {
			if(self::isLoggedIn()) {
				unset($_SESSION['user_id']);
				unset($_SESSION['user_name']);
				unset($_SESSION['user_email']);
				session_destroy();
			}
			redirect('users/login');
		}
	}
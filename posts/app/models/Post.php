<?php
	class Post {
		private $db;

		public function __construct() {
			$this->db = new Database;
		}

		public function getPosts() {
			$this->db->query('SELECT *, posts.id as postId, users.id as userId, posts.created_at as postTime FROM posts, users WHERE posts.user_id = users.id ORDER BY posts.created_at DESC');

			return $this->db->resultSet();
		}

		public function addPost($data) {
			$this->db->query('INSERT into posts (title, body, user_id) values(:title, :body, :user_id)');
			// Bind values
			$this->db->bind(':title', $data['title']);
			$this->db->bind(':body', $data['body']);
			$this->db->bind(':user_id', $data['user_id']);

			// Execute
			return $this->db->execute();
		}

		// Update post
		public function updatePost($data) {
			$this->db->query('UPDATE posts SET title = :title, body = :body WHERE id =:id');
			// Bind values
			$this->db->bind(':id', $data['id']);
			$this->db->bind(':title', $data['title']);
			$this->db->bind(':body', $data['body']);

			// Execute
			return $this->db->execute();
		}

		// Update post
		public function deletePost($id) {
			$this->db->query('DELETE from posts WHERE id =:id');
			// Bind values
			$this->db->bind(':id', $id);

			// Execute
			return $this->db->execute();
		}

		// Get post by id
		public function getPostById($id) {
			$this->db->query('SELECT * FROM posts WHERE id = :id');
			// Bind values
			$this->db->bind(':id', $id);

			$row = $this->db->single();
			// Check row
			if($this->db->rowCount() > 0) {
				return $row;
			} else {
				return false;
			}
		}
	}
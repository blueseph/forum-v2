<?php

	class Topic {

		public $id;
		public $creator;
		public $last_post;
		public $subject;
		public $posts = array();
		public $last_post_edited;

		private function editTime($datetime) {

			date_default_timezone_set('America/New_York');
			$sqldate = strtotime($datetime);
			$sqldate -= (3600*4);
			$now = time();
			$difference = $now - $sqldate;

			if ($difference > 86400) { 
				$date = date('M d', $sqldate);
			} else { 
				if ($difference < 59) { 
					$date = $difference." second";
				} elseif ($difference < 3599) {
					$date = floor($difference/60)." minute";
				} elseif ($difference < 86499) {
					$date = floor($difference/3600)." hour";
				}
				if (strtok($date, " ")[0] > 1) //if the first part of the date is not one, add an s for plural
					$date .= "s";
				}
			return $date;
		}

		private function getTopic($id) {

			global $conn;

			$query = "SELECT 
					  topic_creator_id, last_post, subject 
					  FROM topics 
					  WHERE id = ?";

			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $id);
			$stmt->execute();

			$results = $stmt->get_result();

			if ($results->num_rows != 0) {

				while ($row = $results->fetch_assoc()) {
					$creator = new User($row['topic_creator_id']);
					$last_post = $row['last_post'];
					$subject = $row['subject'];
				}

				return array($creator, $last_post, $subject);
			}

			$results->free();
		}

		private function getPosts($id) {

			global $conn;

			$query = "SELECT id, topic_id, post_content, post_time, post_creator_id, edited, deleted 
					  FROM posts 
					  WHERE topic_id = ? 
					  ORDER BY id 
					  ASC";

			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $id);
			$stmt->execute();

			$results = $stmt->get_result();

			while ($row = $results->fetch_assoc()) {

				$this->posts[count($this->posts)] = new Post($row);
			}

			$results->free();
		}

		function __construct($id) { // User.Class.php REQUIRED

			$this->id = $id;
			$topic_array = $this->getTopic($id);
			$this->creator = $topic_array[0];
			$this->last_post = $topic_array[1];
			$this->subject = $topic_array[2];
			$this->getPosts($id);
			$this->last_post_edited = $this->editTime($this->last_post);
		}

		public function __toString() { //allows array_unique() to compare objects
			return strval($this->id);
		}

		public function display($filter) {


			$topic_name = $this->subject; //for the header

			if (count($this->posts) > 0) {

				include_once "../headers/show-msg-header.php";

				echo "<div class='post-container col-md-12 col-sm-12 col-xs-12'>
						<ul>";

				foreach ($this->posts as $post) {
					if ($filter === false) {
						$post->display(false);
					} else {
						if ($post->creator->id == $filter) {
							$post->display(true);
						}
					}
				}

				echo "</ul>
						</div>";

				include_once "quickpost.php";
				include_once "../footers/show-msg-footer.php";

			} else {

				header('Location: 404.php');
				die();

			}

		}

		public function topicUpdate($timestamp, $filter) {

			global $conn;
			$id = $this->id;

			$update_query = "SELECT id, topic_id, post_content, post_time, post_creator_id, edited, deleted 
					  		 FROM posts 
					  		 WHERE topic_id = '$id' AND post_time > '$timestamp'
					  		 ORDER BY id 
					  	 	 ASC";

			$update_results = $conn->query($update_query);

			if ($update_results->num_rows > 0) {

				while ($new_post = $update_results->fetch_assoc()) {

					$post = new Post($new_post);

					if ($filter > 0) {
						if ($post->creator->id == $filter) {
							$post->display(true);
						}
					} else {
						$post->display(false);
					}
				}
			}
		}

		public function addPost($post_content) { 

			global $self; //user information
			global $conn; 

			//add post to posts table

			$current_time = date('Y-m-d H:i:s');
			$post_creator_id = $self->id;
			$topic_id = $this->id;

			$post_query = "INSERT INTO posts(post_creator_id, post_time, topic_id, post_content) 
						   VALUES (?, ?, ?, ?)";

			$post_stmt = $conn->prepare($post_query);
			$post_stmt->bind_param('isis', $post_creator_id, $current_time, $topic_id, $post_content);
			$post_stmt->execute();

			//update last_post in topic

			$topic_query = "UPDATE topics SET last_post='$current_time' WHERE id='$topic_id'";
			$topic_stmt = $conn->query($topic_query);

		}

	}

?>
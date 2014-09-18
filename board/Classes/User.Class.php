<?php

	class User {
		
		public $name;
		public $id;
		public $level;

		private function getUser($user_id) {

			global $conn;

			$query = "SELECT username 
					  FROM members 
					  WHERE id = ?";

			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $user_id);
			$stmt->execute();

			$result = $stmt->get_result();

			if ($result->num_rows === 0) {

			}

			while ($row = $result->fetch_assoc()) {
				return $row['username'];
			}

			$result->free();
		}

		function __construct($id) {

			$this->name = $this->getUser($id);
			$this->id = $id;

		}

		public function __toString() { //allows array_unique() to compare objects
			return strval($this->id);
		}

		public function userPage() {
			
		}

	}

?>
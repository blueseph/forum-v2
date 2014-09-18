<?php

	// needs to be re-done

	if (!$_POST['username'] && !$_POST['email'] && !$_POST['password'] && !$_POST['cpass'] ) {

		$message = "Please fill out all fields.";

		$results = ["success" => False, "message" => $message];
		$results = json_encode($results);
		echo $results;

	} else {

		$message = '';

		if ($_POST['password'] != $_POST['cpass']) { //passwords dont match

			$message .= "Passwords don't match<br>";

		}

		if (strlen($_POST['username'])<3) { //name must be at least 3 characters

			$message .= "Please use a username with at least 3 characters.<br>";

		}
		
		if (strlen($_POST['username'])>20) { //name must be no longer than 20 characters

			$message .= "Please use a username with at least 3 characters.<br>";

		}

		if (strlen($_POST['password'])<7) { //password must be at least 6 characters

			$message .= "Passwords must be longer than 6 characters.<br>";

		}

		if (preg_match("~[^a-zA-Z0-9_ -']~", $_POST['username'])) { //username contains unusuable characters

			$message .= "Username contains unusable characters.<br>";

		}

		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

			$message .= "Please enter a valid email.<br>";

		}

		if ($message != '' ) { //stuff happened

				$results = ["success" => False, "message" => $message];
				$results = json_encode($results);
				echo $results;

		} else  {

			include_once "dbconnect.php";

			$name = $conn->escape_string($_POST['username']);
			$email = $conn->escape_string($_POST['email']);
			$password = $conn->escape_string($_POST['password']);

			//check for duplicate ids/emails

			//names
			$name_query = "SELECT username FROM members WHERE username='$name'";
			$name_results = $conn->query($name_query);
			$name_rows = $name_results->num_rows;

			//emails
			$email_query = "SELECT email FROM members WHERE email='$email'";
			$email_results = $conn->query($email_query);
			$email_rows = $email_results->num_rows;

			if ($email_rows != 0) { //email already in database

				$message = "Email already exists";

				$results = ["success" => False, "message" => $message];
				$results = json_encode($results);
				echo $results;

			} else {

				if ($name_rows != 0) { //name already in database

				$message = "Username already exists";

				$results = ["success" => False, "message" => $message];
				$results = json_encode($results);
				echo $results;

				} else { //otherwise, register user

						//hash password using bcrpyt
							//bcrypt needs a cost. default to 11
							$options = array('cost' => 11);
							$hash = password_hash($password, PASSWORD_BCRYPT, $options);

						$register_query = "INSERT INTO members(username, email, password) VALUES ('$name', '$email', '$hash')";
						$conn->query($register_query) or die("Fatal Error. Please try to register again: ".mysql_error());

						$message = "Registration successful. <a href='index.php'>Login</a>";

						$results = ["success" => True, "message" => $message];
						$results = json_encode($results);
						echo $results;

					}


				}

			$name_results->free();
			$email_results->free();
			$conn->close();

		}

	}

?>
<?php

	session_start();

	if (!isset($_SESSION['id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {

		header('Location: index.php');

	} else { 

		include_once "../dbconnect.php";
		include_once "Classes/User.Class.php";
		include_once "Classes/Post.Class.php";
		include_once "Classes/Topic.Class.php";
		include_once "Classes/Board.Class.php";

		$self = new User($_SESSION['id']);

		if (!isset($_REQUEST['topic_id'])) { // user not coming from inside a topic [has no referral id]. user wants to create a topic

			// create topic 

			$subject = $_POST['subject'];
			$content = $_POST['message'];

			$board = new Board();
			$board->fillBoard();
			echo $board->addTopic($subject, $content); // returns topic id for redirection

		} else {
				
			$topic_id = $_POST['topic_id'];
			$post_content = $_POST['message'];

			$topic = new Topic($topic_id);
			$topic->addPost($post_content);

		}

		$conn->close();


	}


?>
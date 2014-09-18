<?php
	session_start();
	include_once "../dbconnect.php";
	include_once "Classes/User.Class.php";
	include_once "Classes/Post.Class.php";
	include_once "Classes/Topic.Class.php";
	include_once "Classes/Board.Class.php";

	if (!isset($_SESSION['id'])){ //user not logged in

		header('Location: ../index.php');

	} else { // user is logged in

		$self = new User($_SESSION['id']);

		$board = new Board();
		$board->fillBoard();
		$board->createTopicList();

	}

?>
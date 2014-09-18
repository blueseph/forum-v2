<?php
	session_start();

	if (!isset($_SESSION['id']) || !isset($_GET['u']))) {

		header('Location: topiclist.php');

	} else { 

		include_once "../dbconnect.php";
		include_once "Classes/User.Class.php";
		include_once "Classes/Post.Class.php";
		include_once "Classes/Topic.Class.php";
		include_once "Classes/Board.Class.php";

		$self = new User($_SESSION['id']);

		$board = new Board();

		

}
<?php
	session_start();

	if (!isset($_SESSION['id']) || $_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET['search_terms'])) {

		header('Location: topiclist.php');

	} else { //use get vs post to ensure back doesn't give an error page. not a security risk since none of the terms are sql queried.

		include_once "../dbconnect.php";
		include_once "Classes/User.Class.php";
		include_once "Classes/Post.Class.php";
		include_once "Classes/Topic.Class.php";
		include_once "Classes/Board.Class.php";

		$self = new User($_SESSION['id']);
		$board = new Board();
		$board->fillBoard();

		$search = $_GET['search_terms'];
		$search = htmlspecialchars($search); //prevents html from displaying
		$terms = explode(" ", $search); //splits search string into individual terms

		include_once "../headers/search-header.php";
		echo "<h2>Searching for <strong>$search</strong></h2>";
		echo "<p>Only the topic title and first post are searched</p>";
		echo "<br>";
		$board->search($terms);
		include_once "../footers/search-footer.php";

	}

?>
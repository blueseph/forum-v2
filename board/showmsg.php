<?php
	session_start();
	include_once "../dbconnect.php";
	include_once "Classes/User.Class.php";
	include_once "Classes/Post.Class.php";
	include_once "Classes/Topic.Class.php";

	if (!isset($_SESSION['id'])){ //user not logged in

		header('Location: topiclist.php');

	} else { // user is logged in

		$self = new User($_SESSION['id']);

		$topic_id = $_GET['topic_id'];
		$topic = new Topic($topic_id);

		(isset($_GET['u']) ? $filter = $_GET['u'] : $filter = false);
		$topic->display($filter);

	}
	

?>
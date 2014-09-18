<?php

	session_start();
	include_once "../dbconnect.php";
	include_once "Classes/User.Class.php";
	include_once "Classes/Post.Class.php";
	include_once "Classes/Topic.Class.php";

	if (!isset($_SESSION['id']) || $_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['topic_id']) ||  !isset($_POST['timestamp']) || !isset($_POST['filtered'])) {

		header('Location: index.php');

	} else { // valid request

		$timestamp = $_POST['timestamp'];
		$topic_id = $_POST['topic_id'];
		$filtered = $_POST['filtered'];

		$topic = new Topic($topic_id);
		$topic->topicUpdate($timestamp, $filtered);
	}

?>
<?php
	session_start();
	include_once "../dbconnect.php";
	include_once "Classes/User.Class.php";
	include_once "Classes/Post.Class.php";
	include_once "Classes/Topic.Class.php";

	$self = new User($_SESSION['id']);

	include_once "../headers/error-header.php";

	?>

		<div id="error-header">
			<h1>404</h1>
		</div>

		<div id="error-content">
		<p>Sorry, this page does not exist. Please try heading back and give it another go.</p>

		<p>Still having the same issue? Contact me and let me know!</p>
		</div>

	<?php

	include_once"../footers/error-footer.php";
?>
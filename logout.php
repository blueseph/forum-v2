<?php

	session_start();
	session_destroy();
	setcookie('ip', '', time()-86400);

	header("location: index.php");
?>
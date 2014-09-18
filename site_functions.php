<?php

	function giveError($error_string) {

		include_once "board_header.php";
		echo "$error_string";
		include_once "footer.php";

	}

	function redirect($url) { //looks nicer

		header("location: $url");

	}

	function redirectIn($time, $url) {

		header("refresh:$time; url=$url");

	}

?>
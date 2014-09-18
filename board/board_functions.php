<?php

	//TODO prepared statements
	
	include_once "../site_functions.php";

	function postMsg($topic_id, $current_time, $post_creator_id, $post_content) {

		global $conn;

		$post_content = htmlspecialchars($post_content); //prevents tags from showing
		$post_content = $conn->escape_string($post_content); //escapes all special characters, preventing sql injection

		$post_query = "INSERT INTO posts(post_creator_id, post_time, topic_id, post_content) VALUES ('$post_creator_id', '$current_time', '$topic_id', '$post_content')";
		$post_result = $conn->query($post_query);

		// make sure "last post" column is accurate

		$topic_query = "UPDATE topics SET last_post='$current_time' WHERE id='$topic_id'";
		$topic_result = $conn->query($topic_query);

	}

	function createTopic($current_time, $topic_creator_id, $subject) {

		global $conn;

		$topic_query = "INSERT INTO topics(topic_creator_id, last_post, subject) VALUES ('$topic_creator_id', '$current_time', '$subject')";
		$topic_result = $conn->query($topic_query);

	}

	function editPost($edit_post_content, $edit_post_time, $post_id, $post_creator_id, $post_time, $topic_id, $post_content) {

		global $conn;

		$edit_post_content = htmlspecialchars($edit_post_content); //prevents tags from showing
		$edit_post_content = $conn->escape_string($edit_post_content); //escapes all special characters, preventing sql injection

		//backup old post in edited_posts
		$backup_post_query = "INSERT INTO edited_posts(post_creator_id, post_time, topic_id, post_content, post_id) VALUES ('$post_creator_id', '$post_time', '$topic_id', '$post_content', '$post_id')";
		$conn->query($backup_post_query);

		//update post and time and set edited to true
		$topic_update_post_query = "UPDATE posts SET post_content='$edit_post_content' WHERE id='$post_id'";
		$conn->query($topic_update_post_query);

		$topic_update_time_query = "UPDATE posts SET post_time='$edit_post_time' WHERE id='$post_id'";
		$conn->query($topic_update_time_query);

		$topic_update_edit = "UPDATE posts SET edited=edited+1 WHERE id='$post_id'";
		$conn->query($topic_update_edit);

	}

	function generateUserArray() { 

		global $conn;

		$user_query = "SELECT * FROM members";
		$user_results = $conn->query($user_query);

		$user_array = array();

		while ($row = $user_results->fetch_assoc()) { //makes an array where the key is the username, and the value is the id

			$user_array[$row['username']] = $row['id'];

		}

		return $user_array;

	}

	function matchUserIdToUsername($poster_id, $user_array) {

		global $conn;

		foreach ($user_array as $username => $id) {

			if ($poster_id == $id) {

				return $username;

			}

		}

	}

	function formatTime($datetime) {

		date_default_timezone_set('America/New_York');
		$sqldate = strtotime($datetime);
		$sqldate -= (3600*4);
		$now = time();
		$difference = $now - $sqldate;

		if ($difference > 86400) { //has been more than one day

			$date = date('M d', $sqldate);

		} else { //shorter than a day

			if ($difference < 59) { //within the minute

				$date = $difference;

				if ($date == 1) {

					$date .= " second";

				} else {

					$date .= " seconds";

				}

			} elseif ($difference < 3599) {

				$date = floor($difference/60);

				if ($date == 1) {

					$date .= " minute";

				} else {

					$date .= " minutes";

				}

			} elseif ($difference < 86499) {

				$date = floor($difference/3600);

				if ($date == 1) {

					$date .= " hour";

				} else {

					$date .= " hours";

				}

			}

		}

		return $date;

	}

	function assemblePostHeader($poster, $poster_id, $post_date, $details, $edit, $filtered) { 

		$header = array();

		//username and profile

		$header['poster'] = "<a href='profile.php?id=$poster_id'>$poster</a>";

		// date

		$header['date'] = "$post_date";

		// details if not in message.php

		if ($details['exist']==1) {

			if ($filtered['exist']==1) {

				$header['filter'] = "<a href=showmsg.php?topic_id=".$filtered['topic_id'].">Unfilter</a>";

			} else {

				$header['filter'] = "<a href=showmsg.php?topic_id=".$filtered['topic_id']."&u=".$filtered['pc_id'].">Filter</a>";

			}

			if ($edit['exist']==1) {

				$word = 'edits';

				if ($edit['times']==1) {
					$word = 'edit';
				}

				$header['details'] = "<a href='message.php?id=".$details['id']."&topic_id=".$details['topic_id']."&r=0'><abbr title='".$edit['times']." $word'>Details</abbr></a>";

			} else {

			$header['details'] = "<a href='message.php?id=".$details['id']."&topic_id=".$details['topic_id']."'>Details</a>";

			}

		}

		//end

		return $header;

	}

	function assemblePost ($header, $post_content) {

		include_once "bbcode.php";

		global $parser;

		$content = nl2br($post_content);

		$parser->parse($content);

		?>

		<li class="clearfix">
			<div class="post-header clearfix">
				<div class="post-creator col-md-2 col-xs-4">
					<b><?php echo $header['poster']; ?></b>
				</div>
				<div class="post-date col-md-2 text-left col-xs-3 pull-right">
					<?php echo $header['date']; ?>
				</div>
				<div class="post-filter col-md-1 col-xs-2 pull-right text-center">
					<?php echo $header['filter']; ?>
				</div>
				<div class="post-details col-md-1 col-xs-2 pull-right text-right">
					<?php echo $header['details']; ?>
				</div>
			</div>
			<div class="post-content col-md-12 col-xs-12">
				<?php echo $parser->getAsHtml(); ?>
			</div>
		</li>
		<?php
	}

	function displayTopicForm($action, $post) {

		echo "<form class= 'pure-form pure-form-stacked' method='post' action='$action'>";
		echo "<fieldset>";
		echo "<input type='text' name='subject' placeholder='Topic Title'>";
		echo "<br>";
		echo "<textarea name='message' placeholder='Message'>$post</textarea>";
		echo "<br>";
		echo "<button class='pure-button pure-button-primary'>Create Topic</button>";
		echo "</fieldset>";
		echo "</form>";

	}

	function displayEditPostForm($action, $post, $id) {

		echo "<form class= 'pure-form pure-form-stacked' method='post' action='$action'>";
		echo "<fieldset>";
		echo "<textarea name='post_content' placeholder='Message'>$post</textarea>";
		echo "<br>";
		echo "<input type='hidden' name='post_id' value='$id'>";
		echo "<button class='pure-button pure-button-primary'>Submit Revision</button>";
		echo "</fieldset>";
		echo "</form>";

	}

	function grabTopicTeaser($topicid) {
		global $conn;
	}

?>
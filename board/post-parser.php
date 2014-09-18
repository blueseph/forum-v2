<?php
	session_start();

	if (!isset($_SESSION['id']) || $_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['message'])) {

		header('Location: topiclist.php');

	} else { 

		$self = new User($_SESSION['id']);

		include_once "../dbconnect.php";
		include_once "bbcode.php";

		$message = $_POST['message'];
		$message = nl2br($message);
		$parser->parse($message);

		$user = $self->id;

		?>
		<div class="post-container col-md-12 col-sm-12 col-xs-12">
			<ul>
				<li class="clearfix">
					<div class="post-header clearfix">
						<div class="post-creator col-md-2 col-xs-4">
							<b><a href="profile.php?id=<?php echo $_SESSION['id']?>"> <?php echo $user; ?></a></b>
						</div>
						<div class="post-date col-md-2 text-left col-xs-3 pull-right"></div>
						<div class="post-filter col-md-1 col-xs-2 pull-right text-center"></div>
						<div class="post-details col-md-1 col-xs-2 pull-right text-right"></div>
					</div>
					<div class="post-content col-md-12 col-xs-12"><?php echo $parser->getAsHtml();?></div>
				</li>
			</ul>
		</div>
		<?php

	}

?>

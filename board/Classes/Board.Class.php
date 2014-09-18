<?php

class Board {
	
	public $topics = array();
	public $users = array();

	//  **
	//  **
	//  **	
	//  ** TOPICS 
	//  **
	//  **
	//  **

	private function getTopics() {

		global $conn;

		$topic_query = "SELECT id, topic_creator_id, last_post, subject FROM topics ORDER BY last_post DESC";
		$topic_result = $conn->query($topic_query);

		while ($topic = $topic_result->fetch_assoc()) {

			$this->topics[count($this->topics)] = new Topic($topic['id']);

		}

		$topic_result->free();
	}

		public function displayTopics($array=false, $max_topics=0) {
		?>

		<div class="topic-list-container">
			<ul>

			<?php 

				($array !== false) ?: $array = $this->topics;

				if (count($array) > 0) {

					foreach ($array as $topic) { //begin while

						$tooltip = $topic->posts[0]->content; //first post as tooltip

						?>

						<li>
							<div class="topic-name col-md-7 col-lg-7 col-sm-7 col-xs-12">
								<a href="showmsg.php?topic_id=<?php echo $topic->id?>" data-toggle="tooltip" data-placement="right" title='<?php echo $tooltip ?>'><?php echo $topic->subject ?></a>
							</div>
							<div class="topic-creator col-md-2 col-lg-2 col-sm-2 col-xs-5 text-right">
								<p><?php echo $topic->creator->name ?></p>
							</div>
							<div class="topic-time col-md-2 col-sm-2 col-lg-2 col-xs-5 text-right">
								<p data-toggle="tooltip" data-placement="right" title="<?php echo $topic->last_post ?>"><?php echo $topic->last_post_edited ?></p>
							</div>
						</li>

					<?php
					} // end while
				} else {
					?>
					<li>
						<div class="topic-name text-center col-md-12 col-lg-12 col-sm-12 col-xs-12" style='padding-bottom: 9px'> No topics found </div>
						<div class="topic-creator col-md-2 col-lg-2 col-sm-2 col-xs-5 text-right"></div>
						<div class="topic-time col-md-2 col-sm-2 col-lg-2 col-xs-5 text-right"></div>
					</li>
					<?php
				}// end if
			?>
			</ul>
		</div>
		<?php
	}

	public function createTopicList() {

		include_once "../headers/topic-list-header.php";
		?>
		<!-- Modal Button -->
		<div class="row">
			<div class="create-topic-button col-md-2 col-md-offset-10 col-lg-2 col-lg-offset-10" data-toggle="modal" data-target=".create-topic-modal"><i class="fa fa-plus-square"></i>Create Topic</div>
		</div>
		<?php 
			$this->displayTopics();
		?>
		<div class="modal fade create-topic-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			    	<h4 class="modal-title">Create New Topic</h4>
			    </div>
			    <div class="modal-body">
			    	<form class="form-horizontal" role="form">
			    		<div class="form-group">
			    			<input type="text" class="form-control" id="subject" placeholder="Topic Title">
			    			<div id="preview-results-subject" class="hidden"></div>
			    		</div>
			    		<div class="form-group">
			    			<textarea class="form-control" rows="10" id="topic-message" placeholder="Post Information"></textarea>
			    		   	<div id="preview-results-message" class="hidden"></div>
			    		</div>
			    </div>
			    <div class="modal-footer">
        			<button type="button" id="edit" class="btn btn-default hidden">Edit Post</button>
		       		<button type="button" id="preview" data-loading-text="Generating Preview..." class="btn btn-default">Preview Post</button>
			    	<button type="button" id="create-topic" data-loading-text="Creating Topic..." class="btn btn-primary">Create Topic</button>
			    </div>
		    </div>
		  </div>
		</div>
		<?php
		include_once "../footers/topic-list-footer.php";
	}

	public function addTopic($subject, $content) {

		global $self;
		global $conn;

		//create topic

		$topic_creator_id = $self->id;

		$topic_query = "INSERT INTO topics(topic_creator_id, subject) 
						VALUES (?, ?)";
		$topic_stmt = $conn->prepare($topic_query);
		$topic_stmt->bind_param('is', $topic_creator_id, $subject);
		$topic_stmt->execute();

		$topic_id = $topic_stmt->insert_id;

		//add post to topic

		$topic = new Topic($topic_id);

		$topic->addPost($content);

		return $topic_id;
	}


	//  **
	//  **
	//  **	
	//  ** USERS 
	//  **
	//  **
	//  **

	public function getUsers() {

		global $conn;

		$query = "SELECT id FROM members";
		$result = $conn->query($query);

		while ($user = $result->fetch_assoc()) {

			$this->users[count($this->users)] = new User($user['id']);

		}

		$result->free();
	}

	public function showUsers() {

		include_once "../headers/userlist-header.php";

		?>
		<div class="userlist-container clearfix">
			<ul>

		<?php

		foreach ($this->users as $user) {

			?>
			<li class="col-md-4 col-lg-4 col-xs-4 text-center"> <a href="users.php?u=<?php echo $user->id; ?>"><?php echo $user->name; ?></a></li>
			<?php

		}

		?>
			</ul>
		</div>
		<?php

		include_once "../footers/userlist-footer.php";

	}

	public function searchUsers($username) {

		
		
	}

	//  **
	//  **
	//  **	
	//  ** POSTS 
	//  **
	//  **
	//  **

	private function displayPosts($posts_array) { //these are orphaned posts with no topic

		if (count($posts_array) > 0) {

			?>
			<div class='post-container col-md-12 col-sm-12 col-xs-12'>
				<ul>
			<?php

			foreach ($posts_array as $post) {

				$post->display(false, true); //not filtered, but require context;

			}

			?>
				</ul>
			</div>
			<?php

		} else  {

			?>
			<div class="topic-list-container">
				<ul>
					<li>
						<div class="topic-name text-center col-md-12 col-lg-12 col-sm-12 col-xs-12" style='padding-bottom: 9px'> No posts found </div>
						<div class="topic-creator col-md-2 col-lg-2 col-sm-2 col-xs-5 text-right"></div>
						<div class="topic-time col-md-2 col-sm-2 col-lg-2 col-xs-5 text-right"></div>
					</li>
				</ul>
			</div>
			<?php

		}

	}

	//  **
	//  **
	//  **	
	//  ** SEARCH
	//  **
	//  **
	//  **


	public function search($terms) {

		global $self;

		$topic_results = array();
		$post_results = array();

		foreach ($this->topics as $topic) {

			foreach ($terms as $term) {

				(strpos($topic->subject, $term) === false ) ?: $topic_results[] = $topic;
				(strpos($topic->posts[0]->content, $term) === false ) ?: $post_results[] = $topic->posts[0];
			}

		}

		//check for duplicates

		$topic_results = array_unique($topic_results);
		$post_results = array_unique($post_results);

		//topic 

		echo "<h4>Topics</h4>";
		$this->displayTopics($topic_results);

		//posts 

		echo "<h4>Posts</h4>";
		$this->displayPosts($post_results);


	}

	//  **
	//  **
	//  **	
	//  ** MISC
	//  **
	//  **
	//  **

	public function fillBoard() {

		$this->getTopics();
		$this->getUsers();

	}

}

?>

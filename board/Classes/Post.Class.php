<?php

	class Post {
		
		public $content;
		public $time;
		public $timestamp_edited;
		public $creator;
		public $id;
		public $topic_id;
		public $deleted;
		public $edits;

		private function editTime($datetime) {

			date_default_timezone_set('America/New_York');
			$sqldate = strtotime($datetime);
			$sqldate -= (3600*4);
			$now = time();
			$difference = $now - $sqldate;

			if ($difference > 86400) { 
				$date = date('M d', $sqldate);
			} else { 
				if ($difference < 59) { 
					$date = $difference." second";
				} elseif ($difference < 3599) {
					$date = floor($difference/60)." minute";
				} elseif ($difference < 86499) {
					$date = floor($difference/3600)." hour";
				}
				if (strtok($date, " ")[0] > 1) //if the first part of the date is not one, add an s for plural
					$date .= "s";
				}
			return $date;
			}

		function __construct($sql_post) { //MUST INCLUDE User.Class.php

			$this->id = $sql_post['id'];
			$this->topic_id = $sql_post['topic_id'];
			$this->content = $sql_post['post_content'];
			$this->time = $sql_post['post_time'];
			$this->timestamp_edited = $this->editTime($this->time);
			$this->creator = new User($sql_post['post_creator_id']);
			$this->edits = $sql_post['edited'];
			$this->deleted = $sql_post['deleted'];

		}

		public function __toString() { //allows array_unique() to compare objects
			return strval($this->id);
		}

		public function display($filtered, $search=false) { 

			include_once "bbcode.php";

			$poster = "<a href='profile.php?id=".$this->creator->id."'>".$this->creator->name."</a>";
			$date = $this->timestamp_edited;
			$time = $this->time;
			$id = $this->id;

			$extended_detail = ($this->edits>0) ? "&r=0><abbr title='".$this->edits." time(s)'>" : "'>";
			$details = "<a href='message.php?id=".$this->id."&topic_id=".$this->topic_id.$extended_detail."Details</abbr></a>";

			$extended_filter = ($filtered) ? "#$id>Unfilter" : "&u=".$this->creator->id.">Filter";
			$filter = "<a href=showmsg.php?topic_id=".$this->topic_id.$extended_filter."</a>";

			$context = "<a href=showmsg.php?topic_id=".$this->topic_id."#".$this->id.">Context</a>";

			global $parser;
			$message = nl2br($this->content);
			$parser->parse($message);

			//echo out the actual post
			?>
				<li class="clearfix" id="<?php echo $id; ?>">
					<div class="post-header clearfix">
						<div class="post-creator col-md-2 col-xs-4">
							<b><?php echo $poster; ?></b>
						</div>
						<div class="post-date col-md-2 text-left col-xs-3 pull-right">
							<?php echo $date; ?><span class="hidden timestamp" ><?php echo $time ?></span>
						</div>
						<div class="post-filter col-md-1 col-xs-2 pull-right text-center">
							<?php echo ($search !== true) ? $filter : $context; ?>
						</div>
						<div class="post-details col-md-1 col-xs-2 pull-right text-right">
							<?php echo $details; ?>
						</div>
					</div>
					<div class="post-content col-md-12 col-xs-12">
						<?php echo $parser->getAsHtml(); ?>
					</div>
				</li>
			<?php
			

		}

	}

?>
$(document).ready( function() {

	//get information for postupdate.php
	var urlSplit = url.split('=')[1];
	var topicID = urlSplit.split('&')[0];
	var filtered;

	(url.indexOf('&u=') > -1 ) ? filtered = url.split('&u=')[1] : filtered = false;

	function update_topic() {
		var timestamp = $('ul').find('.timestamp').last().text();
		var result = $.post("postupdate.php", {topic_id: topicID, timestamp: timestamp, filtered: filtered})
		result.done(function(data) { //returns the formatted topic reply
			if (data != '') {
				$('.post-container').find('ul').append(data);
			}
		});
	}

	// open connection with websocket daemon
	var server = "ws://eeatc.com/boards/php/board/websocketd.php";
	websocket = new WebSocket(server);

	websocket.onmessage = function(data) { // message will be topic id 

		if (data == topicID) {
			update_topic();
		}
		
	}


});
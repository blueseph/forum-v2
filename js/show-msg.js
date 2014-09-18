var url = window.location.href;

$(document).ready( function() {

	var urlSplit = url.split('=')[1];
	var topicID = urlSplit.split('&')[0];
	var filtered;

	(url.indexOf('&u=') > -1 ) ? filtered = url.split('&u=')[1] : filtered = false;

	function update_topic() {
		var timestamp = $('ul').find('.timestamp').last().text();
		var result = $.post("postupdate.php", {topic_id: topicID, timestamp: timestamp, filtered: filtered})
		result.done(function(data) {
			if (data != '') {
				$('.post-container').find('ul').append(data);
			}
		});
	}

	setInterval( function() {
		update_topic();
	}, 5000);

});
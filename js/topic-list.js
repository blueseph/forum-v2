$(document).ready(function () {

 	$('.modal').on('click', '#preview', function(event) {

 		event.preventDefault();

 		var subject = $('#subject').val();
 		var tc = $('#username').text();

 		var generateTitleDiv = function(parent) {
 			parent.append('\
 				<div class="topic-list-container">\
 					<ul>\
 						<li>\
 							<div class="topic-name col-md-7 col-lg-7 col-sm-7 col-xs-12">\
 								<a href="#">' + subject + '</a>\
 							</div>\
 							<div class="topic-creator col-md-2 col-lg-2 col-sm-2 col-xs-5 text-right">\
 								<p>' + tc + '</p>\
 							</div>\
 							<div class="topic-time col-md-2 col-sm-2 col-lg-2 col-xs-5 text-right">\
 								<p>Now</p>\
 							</div>\
 						</li>\
 					</ul>\
 				</div>\
 				<hr>');
 		};

 		var message = $('textarea').val();
    	post = $.post('post-parser.php', {message: message});
    	var btn = $(this)
    	btn.button('loading')
    	post.done(function(data) {
	   		btn.button('reset');

	   		//topic title
	   		$('#subject').addClass('hidden');
	   		$('#preview-results-subject').removeClass('hidden');
	   		$('#preview-results-subject').empty();
	   		generateTitleDiv($('#preview-results-subject'));

	   		//textarea 
	   		$('textarea').addClass('hidden');
	   		$('#preview-results-message').removeClass('hidden')
         	$('#preview-results-message').empty();
         	$('#preview-results-message').append(data);

         	//buttons
    		$('#edit').removeClass('hidden');
    		$('#preview').addClass('hidden');
    	});

 	});

   	$('.modal').on('click', '#edit', function(event) {

   		event.preventDefault();

   		//topic title
   		$('#subject').removeClass('hidden');
   		$('#preview-results-subject').addClass('hidden')

   		//textarea
   		$('textarea').removeClass('hidden');
   		$('#preview-results-message').addClass('hidden');

   		//buttons
    	$('#edit').addClass('hidden');
    	$('#preview').removeClass('hidden');

   	});

 	$('.modal').on('click', '#create-topic', function(event) {

 		event.preventDefault();

 		var subject = $('#subject').val();
 		var message = $('#topic-message').val();

 		post = $.post('postmsg.php', {subject: subject, message: message});
 		var btn = $(this)
    	btn.button('loading')
  		post.done( function(data) {
 			btn.button('reset');
 			window.location.replace('showmsg.php?topic_id=' + data);
      $('textarea').val('');
 		});
 		
 	});

});

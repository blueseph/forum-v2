var url = window.location.href;

$(document).ready(function () {

	//grab topicID from URL

	var urlSplit = url.split('=')[1];
	var topicID = urlSplit.split('&')[0];

    var qpi = $('#quickpost-icon-inner');

	var revertEdit = function() {
		$('textarea').removeClass('hidden');
    	$('#preview-post-div').remove();
    	$('#edit').addClass('hidden');
    	$('#preview').removeClass('hidden');
	}

    $('#chat-toggle').click(function() {
       
        $('#chat-box').toggle(200);

        if (qpi.hasClass('fa-terminal')) {
        	qpi.removeClass('fa-terminal');
        	qpi.addClass('fa-caret-down');
        } else {
        	qpi.removeClass('fa-caret-down');
        	qpi.addClass('fa-terminal');
        }
    });

    $('#post-msg').on('click', function() {

    	var message = $('textarea').val();
    	post = $.post('postmsg.php', {topic_id: topicID, message: message});
    	var btn = $(this)
    	btn.button('loading')
    	post.done(function(data) {
    		btn.button('reset');
    		$('#chat-box').toggle();
    		revertEdit();
    		qpi.removeClass('fa-caret-down');
        	qpi.addClass('fa-terminal');
        	$('textarea').val('');
    	});

    });

    $('#preview').on('click', function() {

    	var message = $('textarea').val();
    	post = $.post('post-parser.php', {message: message});
    	post.done(function(data) {
    		//create a div that's identical to a regular post and remove the textarea

    		var ta = $('textarea');
    		var prependString = '<div id="preview-post-div"></div>';
    		console.log(data);

    		ta.addClass('hidden');
    		ta.parent().before(prependString);
    		$('#preview-post-div').append(data);
    		$('#edit').removeClass('hidden');
    		$('#preview').addClass('hidden');
    	});

    });

    $('#edit').on('click', function() {

    	//remove everything and return textarea to normal
    	revertEdit();

    });

    $(window).scroll(function() { //determines if footer is visible. if so, set quickpost icon 65 pixels above footer (55 px footer). otherwise, set 10px above bottom.

	    if ($(window).scrollTop() + $(window).height() > $('footer').offset().top) { 
			if ($('#quickpost-container').hasClass('quickpost-fixed-bottom-10')) {
				$('#quickpost-container').removeClass('quickpost-fixed-bottom-10');
				$('#quickpost-container').addClass('quickpost-fixed-bottom-65')
			};
		} else {
			if ($('#quickpost-container').hasClass('quickpost-fixed-bottom-65')) {
				$('#quickpost-container').removeClass('quickpost-fixed-bottom-65');
				$('#quickpost-container').addClass('quickpost-fixed-bottom-10');
			};
		};
	});
   
});
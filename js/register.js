$(document).ready( function() {

	$('form').submit(function (event) {

		event.preventDefault(); //stop form from sending

		var form = $('form');
		var user = form.find("#username").val();
		var email = form.find('#email').val();
		var pass = form.find("#password").val();
		var cpass = form.find('#confirm-password').val();
		var post = $.post('registration.php', {username: user, password: pass, cpass:cpass, email: email});

		//remove the login button and replace it with a spinner

		var misc = $('form').find('#misc')

		misc.find('button').remove();
		misc.append('<i class="fa fa-spinner fa-spin pull-right"></i>');

		//change to ensure dismissing error doesnt remove div

		post.done( function (data) {

			$('#result').empty();
			$('#result').removeClass();

			// add the login button back
			misc.find('i').remove();
			misc.append('<button type="submit" class="btn btn-primary btn-sm pull-right">Register</button>');

			data = JSON.parse(data);

			if (data["success"]) {
				$('#result').addClass('alert alert-success alert-dismissible');
				$('#result').prepend('<button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>');
			} else {
				$('#result').addClass('alert alert-danger alert-dismissible');
				$('#result').prepend('<button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>');
			}
			$('#result').append(data['message']);

		});

	});

	$('#result').on('click', 'button', function() {

	    $(this).parent().addClass('hidden');

	});

});
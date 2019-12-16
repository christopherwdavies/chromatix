// /alert( 'Hi Chris3' );

console.log('Trial #3');

$(function(){
    // Get the form.
    var form = $('#ajax-push-notification');

    // Get the messages div.
    var formMessages = $('#message');
    var formTitle = $('#title');

    //update notification title as user types input
    $("#title").on("keyup change", function() {
       $("#notification-title").text(this.value);
    });

    //update notification message as user types input
    $("#message").on("keyup change", function() {
       $("#notification-message").text(this.value);
    });




     //Fill content as user selects an app
    $('.users-apps').click(function(event) {
		console.log('you clicked on an app');
		$("#appname").text($(this).find("#app-name").text());
		$("#serverkey").text($(this).find("#app-server-key").text());

		$("#selected-app").text($(this).find("#app-name").text());
		$("#company-name-input").text($(this).find("#app-name").text());

        $('.users-apps').removeClass("selected");
		$(this).addClass("selected");

		console.log($(this).find("#app-name").text());
		console.log($(this).find("#app-server-key").text());
	});



    // TODO: The rest of the code will go here...
    // Set up an event listener for the contact form.
    $(form).submit(function(event) {
        // Stop the browser from submitting the form.
        event.preventDefault();
        // TODO
        // Serialize the form data.
        var formData = $(form).serialize();

        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData
        })
        .done(function(response) {
		    // Make sure that the formMessages div has the 'success' class.
		    $("#form-messages").removeClass('error');
		    $("#form-messages").addClass('success');

		    // Set the message text.
		    $("#form-messages").text('Congratulations, message sent!');
		    console.log(response);

		    // Clear the form.
		    $('#title').val('');
		    $('#message').val('');
		    $('#url').val('');
		});
    });
});
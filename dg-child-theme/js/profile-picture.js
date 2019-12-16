/**
 *
 *	Update profile Pictures
 *	Update cover images
 *
 */
 function dg_save_meta( meta_key, data ) {
	var ajaxurl = variables.admin;
	var user_id = variables.user;
    if (!event) {
    	event = window.event;
    }
    var el = (event.target || event.srcElement); 
    var data = {
        action: 	'dg_save_meta_ajax',
        user_id: 	user_id,
        data: 	 	data,
        meta_key: 	meta_key
	    };
    jQuery.post(ajaxurl, data, function(response) {
        if ( response == 1 ) {
        	showDialog('Saved succesfully', 'Success');
        }
    });
}

(function($) {

$(document).ready( function() {
	
	/**
	 *
	 *	Update profile picture
	 *
	 */
	$( '#profile-picture' ).on( 'click', function( event ) {

		var file_frame; // Reset var

		event.preventDefault();
		event.stopPropagation();

        // if the file_frame has already been created, just reuse it
		if ( file_frame ) {
			file_frame.open();
			return;
		} 

		file_frame = wp.media.frames.file_frame = wp.media({

			title: 'Select your profile picture',

			button: {
				text: 'Set as profile picture',
			},
			library: {
		        type: [ 'image' ]
		    },
			multiple: false

		});

		file_frame.on( 'select', function() {

			// Get image
			attachment = file_frame.state().get('selection').first().toJSON();

			// Set example on picture
			$( '#profile-picture' ).css("background-image", "url("+attachment.url+")"); 

			//send off info to save
			dg_save_meta( 'dg_profile_picture', attachment.id );

		});

		file_frame.open();

	});

	/**
	 *
	 *	Update banner image
	 *
	 */
	$( '.edit-cover-image' ).on( 'click', function( event ) {

		var file_frame; // Reset var

		event.preventDefault();
		event.stopPropagation();

        // if the file_frame has already been created, just reuse it
		if ( file_frame ) {
			file_frame.open();
			return;
		} 

		file_frame = wp.media.frames.file_frame = wp.media({

			title: 'Select your cover image',
			button: {
				text: 'Set as cover image',
			},
			library: {
		        type: [ 'image' ]
		    },
			multiple: false

		});

		file_frame.on( 'select', function() {

			// Get image
			attachment = file_frame.state().get('selection').first().toJSON();

			// Set example on picture
			$( '.cover-image' ).css("background-image", "url("+attachment.url+")"); 

			//send off info to save
			dg_save_meta( 'dg_cover_image', attachment.id );

		});

		file_frame.open();

	});

});

})(jQuery);
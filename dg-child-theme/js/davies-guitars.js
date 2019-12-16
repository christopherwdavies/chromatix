// Notifications messages
function showDialog( set_text, set_title = null, optional_parms = null ) {
	jQuery("#dialog-modal").html(set_text);
	jQuery("#dialog-modal").dialog({
		title: set_title,
		show: { duration: 200 },
		position: { my: "left bottom", at: "left bottom", of: window},
		buttons: [{
			      text: "Close",
			      click: function() {
			        jQuery( this ).dialog( "close" );
			      }
			    }
			  ]
	});
}

// Points popups
function points_popup( points ) {
	var pointsClass = 'points-' + points;
	var now = Date.now();
	var identifier = now.toString();
	var pointsHTML = '<div class="points-popup '+identifier+'" style="display: none;"><span class="points"></span></div>';
	jQuery('#points-popup-div').append(pointsHTML);
	jQuery('.' + identifier + ' .points').html('+' + points);
	jQuery('.points-popup.' + identifier).addClass('points-popup-animation '+pointsClass);
	jQuery('.points-popup.' + identifier).delay( 500 ).fadeIn(500, function() {
		jQuery('.points-popup.' + identifier).fadeOut(4000, function() {
			jQuery('.' + identifier).remove();
		});
	});
}
jQuery(document).ready(function($) {
	// Create overlay when creating account
	$('form.register').on('submit', function() {
	   var button = $('form.register button');
	   button.text('Processing');
	   $('div#registration-loading').fadeToggle();
	});
	// close chat when clicking on close button
	$('#close-chat-button, #open-chat-button').click(function() {
		$('.fixed-chat-wrapper').fadeToggle();
	});
});

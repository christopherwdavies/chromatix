jQuery( document ).ready(function() {

	// accordion function on click
	jQuery('.show-more-button').click(read_more);

	console.log( jQuery('.product-description-show-more').outerHeight() );

	if ( jQuery('.product-description-show-more').outerHeight() < 400 ) {
		console.log('Less than 400');
		jQuery('span.white-gradient-hide-overflow').addClass('opacity-hide');
		jQuery('.show-more-button').addClass('opacity-hide');

	} else {
		console.log('greater than 400');
	}

});


function read_more() {
	if ( jQuery('.product-description-show-more').hasClass('max-height-container') ) {
		// Going to show more
		jQuery('.product-description-show-more').removeClass('max-height-container');
		jQuery('.show-more-button').html('Show Less <i class="fas fa-chevron-up"></i>');
		jQuery('.white-gradient-hide-overflow').addClass('opacity-hide');
	} else {
		// Going to show less
		jQuery('.product-description-show-more').addClass('max-height-container');
		jQuery('.show-more-button').html('Show More <i class="fas fa-chevron-down"></i>');
		jQuery('.white-gradient-hide-overflow').removeClass('opacity-hide');
	}
}
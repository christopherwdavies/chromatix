<?php
/**
 *	SEO Functions
 *	@depends Yoast SEO
 *
 **/

// Update description
add_filter( 'wpseo_metadesc', 'cd_modify_seo_description', 10, 1 );
function cd_modify_seo_description( $description ) {

	global $post;

	$meta_descript	= get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );

	// No meta description has been set, lets update
	if ( $meta_descript == '' ) {

		/**
		 *	Load variables
		 */
		$brand 			= get_the_terms( $post->ID, 'pa_brand');
		$tone_type		= get_the_terms( $post->ID, 'pa_tone-type');
		$tone_name		= get_the_title( $post->ID );

		// Look at taxonomy
		$queried_object = get_queried_object();

		if ( isset($queried_object->name) ) {

			$tax_name		= $queried_object->name;

		} else {

			$tax_name = '';

		}

		/**
		 *
		 *			Single post types
		 *		
		 *			* Guitar Tones
		 *			* Products
		 *			* Guitar Tabs
		 *			* Posts
		 *			* Pages 
		 *
		 */

		/**
		 *	@type Product (Guitar Tones)
		 */
		if ( is_product() ) {

			$description = $tone_name . '. Find the perfect guitar tone with our Guitar Tone Explorer. Thousands of '.$tone_type[0]->name.' tones ready to compare.';
			return $description;

		} 

		/**
		 *	@type Product (Guitar Tones)
		 *	@deprecated No longer user this post type. Scheduled for deletion
		 */
		if ( is_singular( 'guitar_tone' ) ) {

			$description = $tone_name . '. Find the perfect guitar tone with our Guitar Tone Explorer. Thousands of guitar tones ready to compare.';
			return $description;

		} 

		/**
		 *	@type Guitar Tab
		 */
		if ( is_singular( 'guitar_tab' ) ) {

			$tabType	 			= get_post_field('tab_type', $post->ID);
			$pageTitle 				= get_the_title();
			$terms 					= get_the_terms( $post->ID , 'artist' ); 
			$artist					= $terms[0]->name;
			$tabName 				= get_post_field('tab_name', $post->ID);

			$description = 'Learn '.$tabName.' by '.$artist.' easily with our online guitar tab player. '.$pageTitle.'. Explore thousands of guitar pro tabs, tones and much more.';
			return $description;

		}

		/**
		 *	@type Posts - Just return normal description
		 */
		if ( is_singular( 'post' ) ) {

			return $description;

		}

		/**
		 *	@type Pages - Just return normal description
		 */
		if ( is_singular( 'page' ) ) {

			return $description;

		}

		/**
		 *
		 *			Archives & Taxonomies
		 *
		 *			* Categories - NA
		 *			* Product Categories - Manually
		 *			* Product Tags - NA
		 *			* Brands (pa_brand) - Done
		 *			* Product compatability (pa_compatability) - Done
		 *			* Guitar Amp (pa_guitar-amp) - Done
		 *			* Tone Type (pa_tone-type) - Done
		 *			@deprecated Artists
		 *			@deprecated Develoeprs
		 *
		 */

 		// Brand pages
		if ( is_tax('pa_brand') || is_tax('pa_compatibility') ) {

			$description = 'Explore and compare '.$tax_name.' products. Davies Guitars is the ultimate destination for heavy metal guitarists. Find your sound.';
			return $description;

		}

		// Amp Tones category
		if ( is_tax('pa_guitar-amp') ) {

			$description = 'Explore and compare '.$tax_name.' tones. Davies Guitars is the ultimate destination for heavy metal guitarists. Find your sound.';
			return $description;

		}

		/**
		 *	Fallback
		 */
		return $description;

	} else {

		// Return the manually written meta description if there is one
		return $meta_descript;

	}

}

/**
 *		Update title... not really doing anything now
 */
//add_filter('wpseo_title', 'cd_modify_seo_title');

function cd_modify_seo_title($title) {

    return $title;

}
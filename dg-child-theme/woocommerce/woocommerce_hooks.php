<?php

/*
*
*	@ Visual Hook Guide THEGEM
*
*

		Main product page template

		do_action('thegem_woocommerce_single_product_left');
		do_action('thegem_woocommerce_single_product_right');
		do_action('thegem_woocommerce_single_product_bottom');

	@ The Woocommerce Actions and Filters


		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
		remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

		add_action( 'woocommerce_after_shop_loop', 'thegem_woocommerce_after_shop_content', 15);
		add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 15 );
		add_action( 'woocommerce_after_shop_loop', 'woocommerce_product_archive_description', 15 );

		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
		add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_content', 4);
		add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_start', 11);
		add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 15);
		add_action('woocommerce_before_shop_loop', 'woocommerce_breadcrumb', 20);
		add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_product_per_page_select', 30);
		add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 40);
		add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_end', 45);

		remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
		remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
		add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		add_action('woocommerce_shop_loop_item_labels', 'woocommerce_show_product_loop_sale_flash', 5);
		add_action('woocommerce_shop_loop_item_labels', 'thegem_woocommerce_show_product_loop_featured_flash', 10);
		add_action('woocommerce_shop_loop_item_labels', 'thegem_woocommerce_show_product_loop_out_of_stock_flash', 10);
		add_action('woocommerce_shop_loop_item_image', 'woocommerce_template_loop_product_thumbnail', 10);
		add_action('woocommerce_shop_loop_item_image', 'thegem_woocommerce_template_loop_product_hover_thumbnail', 15);
		add_action('woocommerce_shop_loop_item_image', 'thegem_woocommerce_template_loop_product_quick_view', 40);
		add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

		add_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
		if(function_exists('thegem_is_plugin_active') && !thegem_get_option('catalog_view') && defined( 'YITH_WCWL' )) {
			add_action('woocommerce_after_shop_loop_item', create_function('', 'echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );'), 20);
		}

		add_action('thegem_woocommerce_single_product_left', 'thegem_woocommerce_single_product_gallery', 5);
		add_action('thegem_woocommerce_single_product_left', 'thegem_socials_sharing', 10);
		add_action('thegem_woocommerce_single_product_left', 'woocommerce_template_single_meta', 15);

		add_action('thegem_woocommerce_single_product_quick_view_left', 'thegem_woocommerce_single_product_quick_view_gallery', 5);

		add_action('thegem_woocommerce_single_product_right', 'thegem_woocommerce_back_to_shop_button', 5);
		add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_title', 10);
		add_action('thegem_woocommerce_single_product_right', 'woocommerce_breadcrumb', 15);
		add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_rating', 20);
		add_action('thegem_woocommerce_single_product_right', 'thegem_woocommerce_rating_separator', 25);
		add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_price', 30);
		add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_excerpt', 35);
		add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_add_to_cart', 45);
		add_action('thegem_woocommerce_single_product_right', 'thegem_woocommerce_size_guide', 50);

		add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_title', 10);
		add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_rating', 20);
		add_action('thegem_woocommerce_single_product_quick_view_right', 'thegem_woocommerce_rating_separator', 25);
		add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_price', 30);
		add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_excerpt', 35);
		add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_add_to_cart', 45);
		add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_meta', 55);
		add_action('thegem_woocommerce_single_product_quick_view_bottom', 'thegem_product_quick_view_navigation', 10);

		if(function_exists('thegem_is_plugin_active') && thegem_is_plugin_active('yith-woocommerce-wishlist/init.php')) {
			add_action('thegem_woocommerce_after_add_to_cart_button', 'thegem_yith_wcwl_add_to_wishlist_button');
		}

		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
		add_action( 'woocommerce_single_variation', 'thegem_woocommerce_single_variation_add_to_cart_button', 20 );

		add_action('thegem_woocommerce_single_product_bottom', 'woocommerce_output_product_data_tabs', 5);
		add_action('thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_navigation', 10);
		add_action('thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_page_content', 15);

		add_action('thegem_woocommerce_after_single_product', 'woocommerce_output_related_products', 5);

		remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
		add_action( 'woocommerce_shop_loop_subcategory_title', 'thegem_woocommerce_template_loop_category_title', 10 );



	@ Woocommerce shop loop hooks and Filters


		// These are actions you can unhook/remove!
		 
		add_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		 
		add_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
		add_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
		 
		add_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		 
		add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 ); 
		 
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 ); 
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		 
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		 
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		 
		add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		 
		add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
		 
		add_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );


*
*
*	@ Product object
*
*
	// Get Product ID
		 
		$product->get_id(); (fixes the error: "Notice: id was called incorrectly. Product properties should not be accessed directly")
		 
	// Get Product General Info
		 
		$product->get_type();
		$product->get_name();
		$product->get_slug();
		$product->get_date_created();
		$product->get_date_modified();
		$product->get_status();
		$product->get_featured();
		$product->get_catalog_visibility();
		$product->get_description();
		$product->get_short_description();
		$product->get_sku();
		$product->get_menu_order();
		$product->get_virtual();
		get_permalink( $product->get_id() );
		 
	// Get Product Prices
		 
		$product->get_price();
		$product->get_regular_price();
		$product->get_sale_price();
		$product->get_date_on_sale_from();
		$product->get_date_on_sale_to();
		$product->get_total_sales();
		 
	// Get Product Tax, Shipping & Stock
		 
		$product->get_tax_status();
		$product->get_tax_class();
		$product->get_manage_stock();
		$product->get_stock_quantity();
		$product->get_stock_status();
		$product->get_backorders();
		$product->get_sold_individually();
		$product->get_purchase_note();
		$product->get_shipping_class_id();
		 
	// Get Product Dimensions
		 
		$product->get_weight();
		$product->get_length();
		$product->get_width();
		$product->get_height();
		$product->get_dimensions();
		 
	// Get Linked Products
		 
		$product->get_upsell_ids();
		$product->get_cross_sell_ids();
		$product->get_parent_id();
		 
	// Get Product Variations
		 
		$product->get_attributes();
		$product->get_default_attributes();
		 
	// Get Product Taxonomies
		 
		$product->get_categories();
		$product->get_category_ids();
		$product->get_tag_ids();
		 
	// Get Product Downloads
		 
		$product->get_downloads();
		$product->get_download_expiry();
		$product->get_downloadable();
		$product->get_download_limit();
		 
	// Get Product Images
		 
		$product->get_image_id();
		$product->get_image();
		$product->get_gallery_image_ids();
		 
	// Get Product Reviews
		 
		$product->get_reviews_allowed();
		$product->get_rating_counts();
		$product->get_average_rating();
		$product->get_review_count();
*
**/

/*
**
**	Add visit profile button to account edit area.
**
*/
add_action('woocommerce_before_edit_account_form', 'dg_user_profile_button');

function dg_user_profile_button($user_id = null) {


	if ($user_id == null) {
		$user_id = get_current_user_id();
	}

	$user_url = get_author_posts_url( $user_id );

	?>
		<a class="gem-button small visit-profile grey" href="<?php echo $user_url ?>"><i class="fas fa-user"></i> Visit My Profile</a>
	<?php

}

// add_action('init', 'dg_test_new_user_email');
add_shortcode('test_new_user_email', 'dg_test_new_user_email');
function dg_test_new_user_email() {

	$wc = new WC_Emails();
	$wc->customer_new_account(5);

}

// WooCommerce Emails - Kill default inline styling
add_filter( 'woocommerce_email_style_inline_tags', 'kill_defaults', 20 );
add_filter( 'woocommerce_email_style_inline_h1_tag', 'kill_defaults', 20 );
add_filter( 'woocommerce_email_style_inline_h2_tag', 'kill_defaults', 20 );
add_filter( 'woocommerce_email_style_inline_h3_tag', 'kill_defaults', 20 );
add_filter( 'woocommerce_email_style_inline_a_tag', 'kill_defaults', 20 );
add_filter( 'woocommerce_email_style_inline_img_tag', 'kill_defaults', 20 );

function kill_defaults($tags) {
    return array();
}

add_filter('woocommerce_email_style_inline_h2_tag', 'my_email_style_inline_h2_tag', 30);
add_filter('woocommerce_email_style_inline_h3_tag', 'my_email_style_inline_h3_tag', 30);
add_filter('woocommerce_email_style_inline_ul_tag', 'my_email_style_inline_ul_tag', 30);
add_filter('woocommerce_email_style_inline_li_tag', 'my_email_style_inline_li_tag', 30);

function my_email_style_inline_tags($tags) {
  if (!in_array('h2', $tags)) $tags[] = 'h2';
  if (!in_array('h3', $tags)) $tags[] = 'h3';
  if (!in_array('ul', $tags)) $tags[] = 'ul';
  if (!in_array('li', $tags)) $tags[] = 'li';
  return $tags;
}

add_filter('woocommerce_email_style_inline_tags', 'my_email_style_inline_tags', 30);

function my_email_style_inline_h2_tag($styles) {
  $styles['font-size'] = '1em';
  $styles['font-family'] = 'Arial, sans-serif';
  $styles['font-weight'] = 'bold';
  return $styles;
}

function my_email_style_inline_ul_tag($styles) {
  $styles['margin-left'] = '1em';
  $styles['padding-left'] = '0';
  $styles['list-style'] = 'none';
  return $styles;
}

function my_email_style_inline_h3_tag($styles) {
  $styles['font-size'] = '1em';
  $styles['margin-bottom'] = '0';
  $styles['font-weight'] = 'normal';
  $styles['font-family'] = 'Arial, sans-serif';
  return $styles;
}

function my_email_style_inline_li_tag($styles) {
  $styles['margin-left'] = '0';
  return $styles;
}
// end killing defaults


// prevent user profile redirecting
remove_action( 'template_redirect', 'wc_disable_author_archives_for_customers' );

/**
 * Send "New User Registration" email to admins when new customer is created on WooCommerce.
 * 
 * @param int $id New customer ID.
 */
add_action( 'woocommerce_created_customer', 'my_wc_customer_created_notification' );
function my_wc_customer_created_notification( $id ) {
	wp_new_user_notification( $id, null, 'admin' );
}

/**
  *
  *
  *	Print woocom error notices to footer
  *
  */
add_action('wp_footer', 'dg_print_error_notices');
function dg_print_error_notices() {

	$result = wc_print_notices(TRUE); 

	if ( ! isset($result) && ! empty($result) ) {
		return;
	}

	?>

	<div class="wp-footer-error-notices"><?php echo $result; ?></div>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.wp-footer-error-notices').click(function() {
				jQuery('.wp-footer-error-notices').fadeToggle(200);
			});
		});
	</script>

	<?php

}

/*
**
**	Redirect to account page if there are validation errors
**
*/
/*add_action( 'woocommerce_register_post', 'dg_wooc_validate_extra_register_fields', 200, 3 );
function dg_wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

	dg_debug('Validation errors: ' . $validation_errors);

	if ( isset( $validation_errors ) && ! empty( $validation_errors ) ) {

		dg_debug('Validation errors: ' . $validation_errors);
		wp_redirect('/my-account/');
		// add_filter( 'woocommerce_registration_redirect', 'dg_custom_user_redirect_if_validation_errors', 150, 1 );

	}
    return $validation_errors;
}
*/

function dg_custom_user_redirect_if_validation_errors() {

	$redirect = '/my-account/';
	return $redirect;


}


// From to pricing
// Creates from - to in product variation prices
add_filter('woocommerce_variable_price_html', 'custom_variation_price', 10, 2);
function custom_variation_price( $price, $product ) { 
	$price = '';
	$price .= wc_price($product->get_price()); 
	return $price;
}

// Function that dumps all custom fields
/*function cd_dump_custom_fields() {
	$getPostCustom = get_post_custom(); // Get all the data 

    foreach($getPostCustom as $name => $value) {
        echo "<strong>".$name."</strong>"."  =>  ";
        foreach($value as $nameAr=>$valueAr) {
                echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo $nameAr."  =>  ";
                echo var_dump($valueAr);
        }
        		echo "<br /><br />";
    }
}*/
/*

		@loop for attributes
		@ cd_attributes is attribute object for
		@ boolean is whether or not to link to attribute
		@ true by default means to use link

*/
function attribute_loop( $cd_attributes, $link_bool = true ) {

	$i = 0;
	foreach ( $cd_attributes as $attribute ) {
		if ( isset($attribute->term_id) ) {

			$url_slug = '/explore/brand/'.$attribute->slug.'/';
			$url_slug = get_term_link($attribute->slug, $attribute->taxonomy);

			// If there is only one value just store the result.
			if ($i == 0) {
				$result = '<a href="'.$url_slug.'">'.$attribute->name.'</a>';
				if ($link_bool == false) {
					$result = $attribute->name;
				}
			} else {
				$result = '<a href="'.$url_slug.'">'.$attribute->name.'</a>' . ', ' .  $result;
				if ($link_bool == false) {
					$result = $attribute->name . ', ' .  $result;
				}
			}
		}

	  	$i++;
	}

	// Clean results
	if ( $result == ',' || $result == null ) {
		$result = '';
	}

	// Return result
	return $result;
}

/*

		@ Generates an adio player with wp audio shortcode

*/
function generate_audio_player( $audioSample ) {
	// Audio Player
	if ($audioSample) {
		$attr = array(
			'src'      => $audioSample,
			'loop'     => '',
			'autoplay' => '',
			'preload'  => 'none'
		);

		$audioPlayer =
		'<div class="audio-sample module-container">'.
			'<div class="title-h3">Audio Sample</div>'.
			wp_audio_shortcode( $attr ).
		'</div>';
	} else {
		$audioPlayer = '';
	}

	return $audioPlayer;
}

/*

		@ Ajax safe audio palyer - doesn't run a shortcode. Might need to be imrpvoed though.

*/

function ajax_safe_audio_player( $audioSample, $bool = false ) {

	if ($audioSample) {
		if ($bool == true) {
			return '<audio class="mejs__player" src="'.$audioSample.'" type="audio/mp3" controls="controls" preload="none">Your browser does not support the audio element.</audio>';
		} else {
			return '<audio class="mejs__player" src="'.$audioSample.'" type="audio/mp3" controls="controls">Your browser does not support the audio element.</audio>';
		}
	} else {
		return '';
	}
}

/*

		@ Generates a video embeds
		@ video links = array of video id's

*/
function generate_video_embed( $videoLinks ) {

	$result = '';

	foreach ( $videoLinks as $video ) {
		if ( $video == '' ) {
			continue;
		}
		$result = '<iframe class="youtube-video" width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.$video.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'.$result;
	}

	return $result;
}

/*

		@ let's look at whats available

*/
/*function cd_wp_dump_data() {

	// Get global
	global $post;
	global $product;

	// Custom FIelds
    $number_of_profiles 		= get_post_meta( $post->ID, 'number_of_profiles', true );			// Number of profiles
	$download_link 				= get_post_meta( $post->ID, 'download_link', true );				// If downloadable from our 
	$audio_sample 				= get_post_meta( $post->ID, 'audio_sample', true );					// Audio sample
	$purchase_link 				= get_post_meta( $post->ID, 'purchase_link', true );				// Where to buy
	$video_links_del			= get_post_meta( $post->ID, 'video_links', true );					// Video examples
	$video_links	 			= explode( ",", $video_links_del);

	$link_to_developer_website 	= get_post_meta( $post->ID, 'link_to_developer_website', true );	// Link to developers website
	$hits_wc_version			= get_post_meta( $post->ID, 'pageview', true );						// Number of hits
	$hits_functions_version		= get_post_meta( $post->ID, 'post_views_hits', true );				// Number of hits

	// Product Attributes
	$brand 						= get_the_terms( $post->ID, 'pa_brand');
	$compatibility 				= get_the_terms( $post->ID, 'pa_compatability');
	$guitar_tone				= get_the_terms( $post->ID, 'pa_guitar-tones');
	$tone_type					= get_the_terms( $post->ID, 'pa_tone-type');

	// Meta info
	$product_type				= $product->get_type();

	if ( current_user_can('administrator') ) {
		// Print / test data
		echo '<h2>Custom Fields</h2>';
		echo '<p><strong>Number Of Profiles: </strong>' .$number_of_profiles.'</p>';
		echo '<p><strong>Download Link: </strong>' .$download_link.'</p>';
		echo '<p><strong>Audio Sample: </strong>' .$audio_sample.'</p>';
		echo '<p><strong>Purchase Link: </strong>' .$purchase_link.'</p>';
		echo '<p><strong>Video Link 1: </strong>' .$video_links[0].'</p>';
		echo '<p><strong>Link To Dev: </strong>' .$link_to_developer_website.'</p>';
		echo '<p><strong>Product Type: </strong>' .$product_type.'</p>';

		echo '<h2>Attribute Fields</h2>';

		echo '<p><strong>Brand: </strong><span>' . attribute_loop($brand) . '</span></p>';
		echo '<p><strong>Compatability: </strong><span>' . attribute_loop($compatibility) . '</span></p>';
		echo '<p><strong>Guitar Tones: </strong><span>' . attribute_loop($guitar_tones).'</span></p>';
		echo '<p><strong>Tone Type: </strong><span>' . attribute_loop($tone_type).'</span></p>';

		print_r($brand);
	}
	
	// Dump all custom fields
	// echo cd_dump_custom_fields();
}*/
//add_action('thegem_woocommerce_after_single_product', 'cd_wp_dump_data', 20);


/*

		@ Add search form to archives and Product pages

*/
add_action( 'cd_wp_under_header', 'cd_wc_archive_search', 10 );
function cd_wc_archive_search() {

	?>
	<div class="gradient-border"></div>
	<div id="woocom-subheader">
		<div class="container">
			<div class="archive-search search-only">
				<?php echo do_shortcode('[searchandfilter id="22491"]') ?>
			</div>
		</div>
	</div>
	<div class="search-filter-active">
		<div class="container">
			<?php echo do_shortcode('[cd-active-filter]'); ?>
		</div>
	</div>
	<?php
}
// add_action( 'cd_wp_under_header', 'cd_wc_promotion_banner' );
function cd_wc_promotion_banner() {
	if ( is_user_logged_in() ) {
		$string = '/my-account/dashboard/new-product/';
	} else {
		$string = '/become-seller/';
	}
	echo '<div class="promo-banner sell-your-tones">
			<div class="container">
				<a href="'.$string.'" class="col-md-12">
					Sell Your Own Tones
					<span class="">Click Here</span>
				</a>
			</div>
		</div>';
}


/*

		@ add filter for shop/archive pages.

*/
function cd_mobile_filter() {
	if ( is_shop() || is_product_category() || is_tax() ) { ?>
		<div class="mobile-filter-container">
			<div class="container">
				<div class="toggle-mobile-filter exit-button mobile-filter-exit"><i class="fas fa-times"></i></div>
				<div class="title-h1">Filter Results</div>
				<div class="filter-results"><?php echo do_shortcode('[cd-active-filter]'); ?></div>
				<div class="mobile-filter sidebar"><?php echo do_shortcode('[searchandfilter id="22491"]'); ?></div>
			</div>
		</div>

		<div class="gem-button mobile-filter button toggle-mobile-filter"><i class="fas fa-filter"></i>Filter</div>
		<script type="text/javascript">
			jQuery( ".toggle-mobile-filter" ).click(function() {   
				if( jQuery('.mobile-filter-container').hasClass("show")) {
			        jQuery('.mobile-filter-container').removeClass( "show" );
			    }
			    else {
			       	jQuery('.mobile-filter-container').addClass( "show" );
			    }
			});
			jQuery(document).scroll(function(e){
				var scrollAmount = jQuery(window).scrollTop();
				if (scrollAmount > 300) {
					if( jQuery('.mobile-filter.button').hasClass("show")) {
				        // jQuery('.mobile-filter-container').removeClass( "show" );
				    }
				    else {
				       	jQuery('.mobile-filter.button').addClass( "show" );
				    }
				} else {
				if( jQuery('.mobile-filter.button').hasClass("show")) {
				        jQuery('.mobile-filter.button').removeClass( "show" );
				    }
				}
			});                                     
		</script>
		<?php
	}
}
add_action('wp_footer', 'cd_mobile_filter');

/*

		@ Add some meta info to product catalogue items

*/
function cd_wc_product_catalogue_meta() {

	// Get global
	global $post;

	// Gather variables
	$brand_raw 				= get_the_terms( $post->ID, 'pa_brand');
	if ( $brand_raw && ! is_wp_error($brand_raw) ) { 
		$brand = attribute_loop($brand_raw, false);
	};

	$tone_type				= attribute_loop( get_the_terms( $post->ID, 'pa_tone-type'), false );
    $number_of_profiles 	= get_post_meta( $post->ID, 'number_of_profiles', true );

    // Show store name if listed by user
    if ( is_merchant_listing() ) {
		$brand = get_store_url('name');
	}

	// Clean profiles no. response
    if ($number_of_profiles == 1) {
    	$number_of_profiles = $number_of_profiles.' Tone';
    } elseif($number_of_profiles >= 1 ) {
    	$number_of_profiles = $number_of_profiles. ' Tones';
    }

    // Store in array for loop
    $attributes 			= array($brand, $tone_type, $number_of_profiles);

    // Loop through each value
	foreach( $attributes as $attribute ) {
		if ( $attribute == '' || $attribute == ' Tones' || $attribute == 'Tones' ) {
			continue;
		}
		// Remove plural in this case
		if ($attribute == 'Kemper Profiles') {
			$attribute = 'Kemper Profile';
		} elseif ($attribute == 'Axe FX Tones') {
			$attribute = 'Axe FX Tone';
		}
		// Print result
		echo '<span class="product-meta-attribute">'.$attribute.'</span>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'cd_wc_product_catalogue_meta', 8 );

/*

		@ Filter price to say free if it is 0

*/
function cd_wc_price_free( $price, $product ){
	if ( '' === $product->get_price() || 0 == $product->get_price() ) {
	    $price = '<span class="woocommerce-Price-amount amount free">Free</span>';
	}
	return $price;
}
add_filter( 'woocommerce_get_price_html', 'cd_wc_price_free', 100, 2 );


/*

		@ Add videos / description columns

*/
function cd_product_columns_info() {

	// Get globals
	global $post, $product;

	$video_links_del 	= get_post_meta( $post->ID, 'video_links', true );
	$video_links 		= explode( ",", $video_links_del);
	$description 		= $product->get_description();

	if ($description == '') {
		$description = 'That\'s awkward, things are looking a bit quiet in the description field...';
	}

	?>
	<div class="row">
		<?php if ($video_links_del == '') { ?>
			<div class="col-md-12 col-sm-12 product-description">
				<h2 class="show-more-description-title">Description</h2>
				<div class="product-description-show-more max-height-container">
					<div class="inner-text-container">
						<?php echo $description ?>
					</div>
				</div>
				<span class="white-gradient-hide-overflow"></span>
				<span class="show-more-button title-h4">Show More <i class="fas fa-chevron-down"></i></span>
			</div>
		<?php } else { ?>
		<div class="col-md-6 col-sm-12 product-video">
			<?php echo generate_video_embed($video_links);?>
		</div>
		<div class="col-md-6 col-sm-12 product-description">
			<h2 class="show-more-description-title">Description</h2>
			<div class="product-description-show-more max-height-container">
				<div class="inner-text-container">
					<?php echo $description ?>
				</div>
			</div>
			<span class="white-gradient-hide-overflow"></span>
			<span class="show-more-button title-h4">Show More <i class="fas fa-chevron-down"></i></span>
		</div>
	</div>

	<?php }
}
add_action( 'thegem_woocommerce_single_product_bottom', 'cd_product_columns_info', 1 );


/*

	Load js file only on product pages

*/
function cd_wp_load_js_products() {
	if (is_product()) {
		wp_register_script( 'product-page-js', get_stylesheet_directory_uri() . '/woocommerce/product_page.js' );
		wp_enqueue_script( 'product-page-js');
	}
}
add_action('wp_enqueue_scripts', 'cd_wp_load_js_products');

/*

		Add reviews to bottom of product page

*/
// add_action( 'thegem_woocommerce_after_single_product', 'comments_template', 2 );


/*

	@add category under title || Priority

*/
function cd_wc_product_subtitle() {

	global $post;

	$tone_type					= get_the_terms( $post->ID, 'pa_tone-type');
	$brand   					= get_the_terms( $post->ID, 'pa_brand');
	$author 					= $post->post_author;

	if ($brand) {
		$brand_link = '<span class="brand title-h3 subtitle-attr">'.attribute_loop($brand).'</span>';
	} else {
		$brand_link = '';
	}
	if ($tone_type) {
		$tone_link = '<span class="tone-type title-h3 subtitle-attr second-attr">'.attribute_loop($tone_type).'</span>';
	} else {
		$tone_link = '';
	}

	if ( is_merchant_listing() ) {
		// daviesguitars.io/store/davies-guitars/
		$brand_link = '<span class="brand title-h3 subtitle-attr merchant-link">'.get_store_url().'</span>';
	}
	echo '<p class="product-subtitle">'.$brand_link.$tone_link.'</p>';

}
add_action('thegem_woocommerce_single_product_right', 'cd_wc_product_subtitle', 12);

/*

		@ Modify product page tabs

*/
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 200 );

function woo_remove_product_tabs( $tabs ) {


	global $post;

	$comments_count = wp_count_comments( $post->ID );
	$comments_no = $comments_count->total_comments;

    //unset( $tabs['description'] );      			// Remove the description tab
    //unset( $tabs['reviews'] ); 					// Remove the reviews tab
    //unset( $tabs['additional_information'] );  	// Remove the additional information tab
    unset( $tabs['seller'] );  						// Remove the additional information tab
    unset( $tabs['more_seller_product'] );  		// Remove the additional information tab
    unset( $tabs['shipping'] );  					// Remove the additional information tab

    // Reorder Tabs
    $tabs['additional_information']['priority'] = 5;
    $tabs['similar_products']['priority'] = 15;
    $tabs['reviews']['priority'] = 25;

    //Rename titles
    $tabs['reviews']['title'] = 'Discussions & Reviews ('.$comments_no.')';		// Rename the reviews tab

    return $tabs;
}


/*

	@add audio sample before cart button


*/

function cd_wc_audio_sample() {

	global $post;
	$audio_sample = get_post_meta( $post->ID, 'audio_sample', true );

	if ($audio_sample) {
		echo '<div class="product-audio-sample"><h4>Audio Sample</h4>'.generate_audio_player($audio_sample).'</div>';		
	}
}
function cd_wc_audio_sample_shop_loop() {
	
	global $post;
	$audio_sample = get_post_meta( $post->ID, 'audio_sample', true );

	if ($audio_sample) {
		echo '<div class="product-audio-sample"><div class="styled-subtitle">Audio Sample</div>'.ajax_safe_audio_player($audio_sample, true).'</div>';
	}
}
// These have all been updated to use the traditional audio element. Much safer and lightweight.
add_action('woocommerce_after_shop_loop_item', 'cd_wc_audio_sample_shop_loop', 25); // add to shop loop - ajax safe version
add_action('thegem_woocommerce_single_product_right', 'cd_wc_audio_sample_shop_loop', 47); // add to product
add_action('thegem_woocommerce_single_product_quick_view_right', 'cd_wc_audio_sample_shop_loop', 50); // add to quick view

/*

		Filter cart button text

*/
add_filter( 'woocommerce_product_single_add_to_cart_text', 'cd_wc_external_product_button', 10, 2 );
function cd_wc_external_product_button( $button_text, $product ) {
    
    if ( 'external' === $product->get_type() ) {
        // enter the default text for external products
        return $product->button_text ? $product->button_text : 'Visit Website';
    }

    return $button_text;
}
/*

		@create new product image spot
		!!! Need to set default placeholder image

*/
function cd_wp_guitar_tone_gallery() {
	global $post, $product;

	$image 		= $product->get_image_id();
	$imageURL 	= wp_get_attachment_url( $image );

	$test = $product->get_attribute('pa_brand');

	if ( ! empty($test) ) {
		$brand 		= get_the_terms( $post->ID, 'pa_brand');
		$attr_slug	= get_term_link($brand[0]->slug, $brand[0]->taxonomy);
		$attr_name  = $brand[0]->name;
	}


	echo '<div class="custom-product-image round" style="background-image: url(\''.$imageURL.'\')"></div>';

	if ($attr_slug && $attr_name) {
		echo '<div class="brand-attr"><a class="brand-letter-link" href="'.$attr_slug.'"><span class="brand-first-letter">'.$attr_name[0].'</span></a><span class="brand-name">'.$attr_name.'</span></div>';
	} elseif( is_merchant_listing() ) {
		echo '<div class="brand-attr"><a class="brand-letter-link" href="'.get_store_url('url').'"><span class="brand-first-letter">'.get_store_url('name')[0].'</span></a><span class="brand-name">'.get_store_url('name').'</span></div>'; 
	}
}

add_action('thegem_woocommerce_single_product_left', 'cd_wp_guitar_tone_gallery', 2);


/*

		@ Download button for anything that has a download link custom field.
	
*/
function cd_wc_download_link() {

	global $post;

	$download_link 		= get_post_meta( $post->ID, 'download_link', true );
	if ($download_link) {
	?>
		<div class="tab-button-wrapper">
			<form method="get" action="<?php echo $download_link;?>" class="download-form">
			   <button class="download-guitar-tab gem-button gem-button-size-large gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" type="submit">Get This Tone</button>
			</form>
		</div>
	<?php }
}
add_action('thegem_woocommerce_single_product_right', 'cd_wc_download_link', 35); // add to product


/*

		@ add new product tab

*/
function cd_wp_more_similar_tones( $tabs ) {

	global $post;
	$tone_type				= attribute_loop( get_the_terms( $post->ID, 'pa_tone-type'), false );

    // Adds the new tab
    $tabs['similar_products'] = array(
        'title'     => __( 'More ' .$tone_type, 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'cd_wp_tone_tab_content'
    );
    return $tabs;
}
// Print tab content
function cd_wp_tone_tab_content() {
	global $post;

	$tone_type = get_the_terms( $post->ID, 'pa_tone-type');
	$tone_type_slug = $tone_type[0]->slug;
	?>
		<div class="products row inline-row columns-4 related-tones">
			<?php

				$args = array(
					'post_type' => 'product',
					'posts_per_page' => 4,
					// 'no_found_rows'	=> true,
					'tax_query' => array(
						array(
							'taxonomy' 	=> 'pa_tone-type',
							'field'		=> 'slug',
							'terms'    	=> $tone_type_slug,
						),
					),
				);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						wc_get_template_part( 'content', 'product' );
						// echo $tone_type;
					endwhile;
				} else {
					echo __( 'No products found' );
				}

				wp_reset_postdata();

			?>
		</div><!--/.products-->
	<?php
}
add_filter( 'woocommerce_product_tabs', 'cd_wp_more_similar_tones' );

/*

		Chagnne extenral link to open new tab

*/
remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
add_action( 'woocommerce_external_add_to_cart', 'dg_external_add_to_cart', 30 );
function dg_external_add_to_cart(){

    global $product;

    if ( ! $product->add_to_cart_url() ) {
        return;
    }

    $product_url = $product->add_to_cart_url();
    $button_text = $product->single_add_to_cart_text();

    do_action( 'woocommerce_before_add_to_cart_button' ); ?>
    
    <p class="cart">
        <a href="<?php echo esc_url( $product_url ); ?>" target="_blank" rel="nofollow" class="external-product-button gem-button gem-button-size-small gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left single_add_to_cart_button button alt"><?php echo esc_html( $button_text ); ?></a>
    </p>
    <?php do_action( 'woocommerce_after_add_to_cart_button' );
}


/*

		Remove actions under if statements

*/
add_action( 'init',  'cd_wc_remove_actions' );
function cd_wc_remove_actions() {



	// Remove the tabs section on WC
	// remove_action('thegem_woocommerce_single_product_bottom', 'woocommerce_output_product_data_tabs', 5);

	// Reomove thegem filter on description tab
	// remove_filter('woocommerce_product_tabs', 'thegem_product_tabs', 11);

	// Remove cart button on shop page
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Remove shitty navigation arrows
	remove_action('thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_navigation', 10);

	// Remove the small title
	remove_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_title', 10);

	// Remove sku and category from under product image
	remove_action('thegem_woocommerce_single_product_left', 'woocommerce_template_single_meta', 15);

	// Remove product description from bottom area + add a dump of product data here
	remove_action('thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_page_content', 15);

	// Remove normal product gallery - do if statement to check if it contains category id (guitar tones)
	remove_action('thegem_woocommerce_single_product_left', 'thegem_woocommerce_single_product_gallery', 5);

	// Remove related products
	remove_action('thegem_woocommerce_after_single_product', 'woocommerce_output_related_products', 5);

	// Remove meta info from quick view
	remove_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_meta', 55);

	if (is_product()) {

		global $post;
		$id = $post->ID;

		// Removed add to cart under certain conditions
		$download_link = get_post_meta( $id, 'download_link', true );

		if ($download_link != '') {
			remove_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_add_to_cart', 45);
			add_action('thegem_woocommerce_single_product_right', 'cd_wc_download_link', 60);
		}
	}

}


/**
 * @snippet       Simplify Checkout if Only Virtual Products
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=78351
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.5.4
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_filter( 'woocommerce_checkout_fields' , 'bbloomer_simplify_checkout_virtual' );
 
function bbloomer_simplify_checkout_virtual( $fields ) {
    
   $only_virtual = true;
    
   foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      // Check if there are non-virtual products
      if ( ! $cart_item['data']->is_virtual() ) $only_virtual = false;   
   }
     
    if( $only_virtual ) {
       unset($fields['billing']['billing_company']);
       unset($fields['billing']['billing_address_1']);
       unset($fields['billing']['billing_address_2']);
       unset($fields['billing']['billing_city']);
       unset($fields['billing']['billing_postcode']);
       unset($fields['billing']['billing_country']);
       unset($fields['billing']['billing_state']);
       unset($fields['billing']['billing_phone']);
       add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
     }
     
     return $fields;
}


/*
 *
 *	Add trust seals to checkout
 *
 **/
add_action('woocommerce_after_checkout_form', 'dg_satisfaction_guarantee_woocom_checkout');
function dg_satisfaction_guarantee_woocom_checkout() {
?>
	<p class="trust-section"><i class="fas fa-check"></i>100% Secure Payment Gateway<i class="fas fa-check"></i>30 Day Money Back Policy<i class="fas fa-check"></i>Satisfaction Guarantee</p>
	<div class="ssl-secure-checkout">
		<img src="https://daviesguitars.io/wp-content/uploads/2019/08/ssl-secure-checkout-trust-seal.png">
		<img src="https://daviesguitars.io/wp-content/uploads/2019/10/images.png">
		<img src="https://daviesguitars.io/wp-content/uploads/2019/10/ssl-circle.png">
		<img src="https://daviesguitars.io/wp-content/uploads/2019/10/safe_checkout1.png">
	</div>
<?php
}


/**
 * @snippet       WooCommerce: Check if Product ID is in the Order
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=17395
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.3
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
    
add_action( 'dg_thankyou_page_under_title', 'bbloomer_check_order_product_id');
function bbloomer_check_order_product_id( $order_id ){

	$order = wc_get_order( $order_id );
	$items = $order->get_items(); 

	$string  = '<p class="subtitle" style="color: white !important;">Congratulations ' . $order->get_billing_first_name() . ', you now have full access to all of our heavy metal guitar exercises.<p>';
	$string .= '<p>You can either download the PDF files and Guitar pro files using the links below, or access all <a href="/metal-guitar-exercises/">exercises</a> online.<br>';
	$string .= 'These downloads will also be available in the <a href="/my-account/downloads/">downloads</a> section of your account, forever.</p>';
	$string .= '<h3>Enjoy!</h3>';

	foreach ( $items as $item_id => $item ) {

	   $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

	      if ( $product_id == 24471 ) {

	        echo $string;

	    }

	}

}
/**
 *
 * Filter thankyou page title
 *
 *
*/
add_filter( 'woocommerce_thankyou_order_received_text', 'misha_thank_you_title2', 20, 2 );
function misha_thank_you_title2( $thank_you_title, $order ){
 
 	return 'Your order has been successful ' . $order->get_billing_first_name();
 
}
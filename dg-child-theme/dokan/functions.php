<?php
    //plugin is activated

function cd_dokan_edit_product_url( $product_id ) {
/*    if ( get_post_field( 'post_status', $product_id ) == 'publish' ) {
        return trailingslashit( get_permalink( $product_id ) ) . 'edit/';
    }*/

    $new_product_url = dokan_get_navigation_url( 'products' );

    return add_query_arg( array(
        'product_id' => $product_id,
        'action'     => 'edit',
    ), $new_product_url );
}
// Change page title
add_filter( 'wp_title', 'cd_change_wp_title', 50, 2 );
function cd_change_wp_title( $title ) {
    $url = $_SERVER['REQUEST_URI'];

    // Change title for add new page.
    if ($url == '/my-account/dashboard/new-product/') {
        $title = 'Create New Listing';
    }
    return $title;
}
/*

    @ 
    @ Args

    #name       = Return name only
    #url        = Return URL only
    #undefined  = Return fully built URL + name in anchor tag.

*/
function get_store_url($args = ''){

    global $product;
    $seller = get_post_field( 'post_author', $product->get_id());
    $author = get_user_by( 'id', $seller );
    $store_info = dokan_get_store_info( $author->ID );

    if ($args == 'name') {
        return $store_info['store_name'];
    } elseif ($args == 'url') {
        return dokan_get_store_url( $author->ID );
    } else { 
/*      ?>
        </a>
        <?php*/

        if ( !empty( $store_info['store_name'] ) ) { ?>
            <?php return '<a href="'.dokan_get_store_url( $author->ID ).'">'.$store_info['store_name'].'</a>'; ?>
        <?php 
        }
    }
}


/*

        @ Login/out registratoin form shortcode

*/
add_shortcode('loginout','cd_loginout_shortcode');
function cd_loginout_shortcode() {

    ob_start();

    if (! is_user_logged_in()) {
        // User isn't logged in
        echo do_shortcode('[woocommerce_my_account]');
        echo '<p>Already registered? <a href="/my-account/">Log in here</a>.';
    } elseif( is_user_logged_in() && ! dokan_is_seller_enabled( get_current_user_id()) ) {
        // Logged in but not enabled for selling
        echo do_shortcode('[dokan-customer-migration]');
    } else {
        echo '<div class="title-h3">You\'re ready to sell!</div><a class="gem-button" href="/my-account/dashboard/new-product/">Create Listing</a>';
    }

    return ob_get_clean();

    
}

add_action('dokan_dashboard_wrap_start', 'cd_dk_dash_greeting', 10);
function cd_dk_dash_greeting() {
	global $current_user;
    get_currentuserinfo();

    $new_product = '<a href="https://daviesguitars.io/my-account/dashboard/new-product/" class="dokan-btn dokan-btn-theme"><i class="fa fa-briefcase">&nbsp;</i>Add new product</a>';
    $orders = '<a href="https://daviesguitars.io/my-account/dashboard/orders/" class="dokan-btn dokan-btn-theme"><i class="fa fa-shopping-cart"></i> Orders</a>';
    
    echo '<div class="cd-dash-greeting"><div class="title-h2">G\'day ' . $current_user->user_firstname.'!<span class="cd-dash-actions">'.$new_product.$orders.'</span></div></div>';
}


/*

		@add menu item

*/
add_filter( 'dokan_get_dashboard_nav', 'dokan_add_help_menu' );
function dokan_add_help_menu( $urls ) {
    $urls['payment'] = array(
        'title' => __( 'Payment Settings', 'dokan'),
        'icon'  => '<i class="fa fa-user"></i>',
        'url'   => '/my-account/dashboard/settings/payment/',
        'pos'   => 81
    );
    return $urls;
}

/*
        @redirect to 

*/
function cd_redirect_if_not_logged_seller() {
    global $post;

    $page_id = dokan_get_option( 'dashboard', 'dokan_pages' );

    if ( ! $page_id ) {
        return;
    }

    // change redirect, chris
    $redirect = home_url('/my-account/upgrade/');

    if ( is_page( $page_id ) || apply_filters( 'dokan_force_page_redirect', false, $page_id ) ) {
        dokan_redirect_login();
        dokan_redirect_if_not_seller( $redirect );
    }
}

add_action('template_redirect', 'cd_redirect_if_not_logged_seller', 5);

/*

		@ Adding fields to vendor settings page
		@ https://wedevs.com/108107/how-to-add-extra-field-on-vendor-settings-page/

*/



/*
	
		@ Hooks for updating meta info

*/
add_action( 'woocommerce_process_product_meta', 'cd_dk_add_fields_save' );
add_action( 'dokan_process_product_meta', 'cd_dk_add_fields_save' );
add_action( 'dokan_new_product_added', 'cd_dk_add_fields_save' );

function cd_dk_add_fields_save( $post_id ){


    // Update video fields
    $woocommerce_video_links = $_POST['video_links'];
    if( ! empty( $woocommerce_video_links ) ) {
        update_post_meta( $post_id, 'video_links', esc_attr( $woocommerce_video_links ) );
    }

    // Update video fields
    $woocommerce_audio_link = $_POST['audio_link'];
    if( ! empty( $woocommerce_audio_link ) ) {
        update_post_meta( $post_id, 'audio_sample', esc_attr( $woocommerce_audio_link) );
    }

    // Update video fields
    $woocommerce_sku = $_POST['_sku'];
    if( ! empty( $woocommerce_sku ) ) {
        update_post_meta( $post_id, '_sku', esc_attr( $woocommerce_sku ) );
    }

    // Let's add file attachments
    $file_url_array = $_POST['_wc_file_urls'];
    $file_name_array = $_POST['_wc_file_names'];
    if( ! empty( $woocommerce_sku ) ) {
    	process_downloadable_file($post_id, $file_name_array, $file_url_array);
	}

    // Update tone type field
    $woocommerce_tone_type = $_POST['tone_type'];
    if( ! empty( $woocommerce_tone_type ) ) {
		set_attribute_term( $post_id, 'pa_tone-type', $woocommerce_tone_type ); // currently working for single term
    }

    // Update Sounds like guitar amp field
    $woocommerce_sounds_like = $_POST['sounds_like'];
    if( ! empty( $woocommerce_sounds_like ) ) {
		set_attribute_term( $post_id, 'pa_guitar-amp', $woocommerce_sounds_like ); // currently working for single term
    }

    // Update number of tones field
    $woocommerce_number_of_tones = $_POST['number_of_tones'];
    if( ! empty( $woocommerce_number_of_tones ) ) {
        update_post_meta( $post_id, 'number_of_profiles', esc_attr( $woocommerce_number_of_tones ) );
    }

    // Make sure price has a number in it
    $woocommerce_price = $_POST['_regular_price'];
    if ( empty( $woocommerce_price ) ) {
        update_post_meta( $post_id, '_regular_price', wc_format_decimal(0.00) );
        update_post_meta( $post_id, '_price', wc_format_decimal(0.00) );
    }

    // Now let's try manually set the cat
    // This works but not necessary because i just make it happen with jquery on page load
    //$cat_term_id = 545; // 545 = guitar tones
    //wp_set_object_terms( $post_id, $cat_term_id, 'product_cat' );

    // Need if statement to check if it's a guitar tone
    $_downloadable          = 'yes';
    $_virtual          		= 'yes';
    $_download_expiry		= -1;
    $_download_limit 		= -1;
    $_sold_individually 	= 'yes';
    update_post_meta( $post_id, '_downloadable', esc_attr( $_downloadable ) );
    update_post_meta( $post_id, '_virtual', esc_attr( $_virtual ) );
	update_post_meta( $post_id, '_download_expiry', esc_attr( $_download_expiry ) );
	update_post_meta( $post_id, '_download_limit', esc_attr( $_download_limit ) );
	update_post_meta( $post_id, '_sold_individually', esc_attr( $_sold_individually ) );

}


add_action('dokan_after_new_product_content_area', 'set_default_category_add_new_product');
function set_default_category_add_new_product() { 
	?>
		<script type="text/javascript">
			jQuery('select[name="product_cat"]').find('option[value="545"]').attr("selected",true);
		</script>
    <?php
}

/*

	@ Function to set attribute terms

	// Params
	@ $post_id = $post_id object
	@ $tax = attribute taxonomy, e.g. pa_guitar-amp
	@ $term = term name e.g. 5150 iii (not slug)

*/
function set_attribute_term( $post_id, $tax, $term ) {

	$taxonomy 	= $tax; 						// The taxonomy
	$term_name 	= $term; 						// The term "NAME"
	$term_slug 	= sanitize_title($term_name); 	// The term "slug"

	// Check if the term exist and if not it create it (and get the term ID).
	if( ! term_exists( $term_name, $taxonomy ) ){
	    $term_data = wp_insert_term( $term_name, $taxonomy );
	    $term_id   = $term_data['term_id'];
	} else {
	    $term_id   = get_term_by( 'name', $term_name, $taxonomy )->term_id;
	}

	// get an instance of the WC_Product Object
	$product = wc_get_product( $post_id );
	$attributes = (array) $product->get_attributes();

	// 1. If the product attribute is set for the product
	if( array_key_exists( $taxonomy, $attributes ) ) {
	    foreach( $attributes as $key => $attribute ){
	        if( $key == $taxonomy ){
	            $options = (array) $attribute->get_options();
	            $options[] = $term_id;
	            $attribute->set_options($options);
	            $attributes[$key] = $attribute;
	            break;
	        }
	    }
	    $product->set_attributes( $attributes );
	}
	// 2. The product attribute is not set for the product
	else {
	    $attribute = new WC_Product_Attribute();

	    $attribute->set_id( sizeof( $attributes) + 1 );
	    $attribute->set_name( $taxonomy );
	    $attribute->set_options( array( $term_id ) );
	    $attribute->set_position( sizeof( $attributes) + 1 );
	    $attribute->set_visible( true );
	    $attribute->set_variation( false );
	    $attributes[] = $attribute;

	    $product->set_attributes( $attributes );
	}

	$product->save();

	// Append the new term in the product
	if( ! has_term( $term_name, $taxonomy, $post_id ))
    wp_set_object_terms($post_id, $term_slug, $taxonomy, true );

}

function process_downloadable_file($post_id, $file_name_array, $file_url_array) {

    $files         = array();
    $file_names    = isset( $file_name_array ) ? array_map( 'wc_clean', $file_name_array ) : array();
    $file_urls     = isset( $file_url_array ) ? array_map( 'esc_url_raw', array_map( 'trim', $file_url_array ) ) : array();
    $file_url_size = sizeof( $file_urls );

    for ( $ii = 0; $ii < $file_url_size; $ii ++ ) {
        if ( ! empty( $file_urls[ $ii ] ) )
            $files[ md5( $file_urls[ $ii ] ) ] = array(
                'name' => $file_names[ $ii ],
                'file' => $file_urls[ $ii ]
            );
    }

    // grant permission to any newly added files on any existing orders for this product prior to saving
    // do_action( 'woocommerce_process_product_file_download_paths', $postdata['post_id'], $variation_id, $files );

    update_post_meta( $post_id, '_downloadable_files', $files );
}

function guitar_tone_fields() { 

	global $post;

	// Custom FIelds
    $number_of_profiles 		= get_post_meta( $post->ID, 'number_of_profiles', true );			// Number of profiles
	$audio 		 				= get_post_meta( $post->ID, 'audio_sample', true );					// Audio sample
	$video 						= get_post_meta( $post->ID, 'video_links', true );					// Video examples

	// Product Attributes
	$selected_tone_type			= get_the_terms( $post->ID, 'pa_tone-type');
	$selected_sounds_like		= get_the_terms( $post->ID, 'pa_guitar-amp');

	?>
    <div class="dokan-form-group row add-new-product guitar-tone-fields">
    	<div class="cd-extra-fields-1 col-md-6 col-sm-12">

            <div class="audio-link-embed product-field">
                <label for="audio_link" class="dokan-form-label">Audio Sample <?php hover_tip('Upload a short wav/mp3 sample of your guitar tone.'); ?></label>
                <a href="#" class="add-audio-link"><i class="fa fa-plus" aria-hidden="true"></i></a>
                <input class="dokan-form-control" name="audio_link" id="audio_link" type="hidden" placeholder="<?php esc_attr_e( 'audio.wav', 'dokan-lite' ); ?>" value="<?php echo esc_attr( dokan_posted_input( 'audio_link' ) ); ?>">

                <?php if($audio) {
                	echo '<div class="product-audio-embed"><audio class="" src="'.$audio.'" type="audio/mp3" controls="controls"></audio></div>';
                } else {
                	echo '<div class="product-audio-embed">Upload an audio sample</div>';
                } ?>
                <script>
                    
                    jQuery(function($){

                      // Set all variables to be used in scope
                      var frame,
                          metaBox = $('.audio-link-embed'), // Your meta box id here
                          addImgLink = metaBox.find('.add-audio-link'),


                          addImgLink = $('.add-audio-link');
                      
                      // ADD IMAGE LINK
                      addImgLink.on( 'click', function( event ){
                        
                        event.preventDefault();
                        
                        // If the media frame already exists, reopen it.
                        if ( frame ) {
                          frame.open();
                          return;
                        }
                        
                        // Create a new media frame
                        frame = wp.media({
                            title: 'Upload your audio sample',
                            button: {
                                text: 'Insert Audio'
                            },
                            library: {
                                type: [ 'audio']
                            },
                            multiple: false  // Set to true to allow multiple files to be selected
                        });

                        
                        // When an image is selected in the media frame...
                        frame.on( 'select', function() {
                          
                          // Get media attachment details from the frame state
                          var attachment = frame.state().get('selection').first().toJSON();

                          //Chris - try append url to my input box
                          $('#audio_link').val(attachment.url);


                          var audioEmbed = '[audio src="'+attachment.url+'"][/audio]';

                          var audioEmbed = '<audio class="mejs__player" src="'+attachment.url+'" type="audio/mp3" controls="controls"></audio>';

                          jQuery('.product-audio-embed').html(audioEmbed);

                          // Send the attachment URL to our custom image input field.
                          //imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

                          // Send the attachment id to our hidden input
                          //imgIdInput.val( attachment.id );

                          // Hide the add image link
                          //addImgLink.addClass( 'hidden' );

                          // Unhide the remove image link
                          //delImgLink.removeClass( 'hidden' );
                        });

                        // Finally, open the modal on click
                        frame.open();
                      });
                      
                

                    });

                </script>
            </div>
        	<?php if($video) {
        		$vid_value = $video;
        	} else {
        		$vid_value = esc_attr( dokan_posted_input( 'video_links' ) );
        	} ?>
            <div class="video-link-embed product-field">
    			<label for="video_links" class="dokan-form-label">Youtube Video<?php hover_tip('Put the unique YouTube slug in here. A preview will be generated if successful.'); ?></label>
            	<input class="dokan-form-control" name="video_links" id="video_links" type="text" placeholder="<?php esc_attr_e( 'QG1PF8kWpS4', 'dokan-lite' ); ?>" value="<?php echo $vid_value ?>">
            	<?php if($video) {
            		echo '<div id="youtube-video"><iframe class="youtube-video" width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.$video.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            	} else {
            		echo '<div id="youtube-video"></div>';
            	} ?>
                <script>
                    jQuery(function($){
                        jQuery('#video_links').change(function() {
                            var slug = jQuery('#video_links').val();
                            var ytEmbed = '<iframe class="youtube-video" width="560" height="315" src="https://www.youtube-nocookie.com/embed/'+slug+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            jQuery('#youtube-video').html(ytEmbed);
                        });

                    });
                </script>

        	</div>
    	</div>

    	<div class="cd-extra-fields-1 col-md-6 col-sm-12">
    		<div class="tone-type-attribute product-field">
    			<label for="tone_type" class="dokan-form-label">Tone Type <?php hover_tip('This is the type of format your tone is compatible with.') ?></label>
            	<select class="dokan-form-control" name="tone_type" id="tone_type" type="text">
            		<option value="">Select a tone type</option>
            	<?php // Loop through tone type attributes
            		$tone_type = get_terms( 'pa_tone-type' );
            		foreach( $tone_type as $tone ) {
            			if ($selected_tone_type[0]->name == $tone->name) {
            				echo '<option value="'.$tone->name.'" selected="selected">'.$tone->name.'</option>';            				
            			} else {
            				echo '<option value="'.$tone->name.'">'.$tone->name.'</option>';
            			}
            		}
            	?>
            	</select>
        	</div>
    		<div class="sounds-like-attribute product-field">
    			<label for="sounds_like" class="dokan-form-label">Sounds Like <?php hover_tip('Choose one amp that this tone best describes. If you\'ve uploaded a pack you may still only choose one.') ?></label>
            	<select class="dokan-form-control" name="sounds_like" id="sounds_like" type="text">
            		<option value="">Select an amp</option>
            	<?php // Loop through tone type attributes
            		$tone_amp = get_terms( 'pa_guitar-amp' );
            		foreach( $tone_amp as $tone ) {
            			if ($selected_sounds_like[0]->name == $tone->name) {
            				echo '<option value="'.$tone->name.'" selected="selected">'.$tone->name.'</option>';
            			} else {
            				echo '<option value="'.$tone->name.'">'.$tone->name.'</option>';            				
            			}
            		}
            	?>
            	</select>
        	</div>
        	<?php if($number_of_profiles) {
        		$tone_value = $number_of_profiles;
        	} else {
        		$tone_value = esc_attr( dokan_posted_input( 'number_of_tones' ) );
        	} ?>
            <div class="number-of-tones-attribute product-field">
                <label for="number_of_tones" class="dokan-form-label">Number of Profiles<?php hover_tip('This is the number of presets/tones/banks included in this package.'); ?></label>
                <input class="dokan-form-control" name="number_of_tones" id="number_of_tones" type="number" placeholder="<?php esc_attr_e( '1', 'dokan-lite' ); ?>" value="<?php echo $tone_value; ?>">
            </div>

    	</div>
    </div>


<?php }
add_action('dokan_product_edit_after_main', 'guitar_tone_fields', 5);
add_action('dokan_new_product_after_product_tags', 'guitar_tone_fields');
<?php 
/**
 *
 *	Pentagram Loading Icon
 *
 */
add_shortcode('dg-loading', 'dg_loading');

function dg_loading() {

	ob_start(); ?>
	<div class="pentagram-wrapper">
		<div class="pentagram swell">
			<div class="one"></div>
			<div class="two"></div>
			<div class="three"></div>
			<div class="four"></div>
			<div class="five"></div>
		</div>
	</div>
	<?php return ob_get_clean();
}
/**
 *
 *	Add to cart button
 *	
 *
 **/
add_shortcode( 'cart-button', 'dg_cart_shortcode' );
function dg_cart_shortcode( $atts ) {

    $atts = shortcode_atts(
    array(
        'id' => '1',
        'text' => 'Add To Cart',
    ), $atts, 'cart-button' );

	$cart_id = 'add-to-cart=' . $atts['id'];
	$link = '/checkout/?' . $cart_id;

	$html = '<a href="'.$link.'" class="gem-button medium">' . $atts['text'] . '</a>';

	return $html;

}

/**
 *
 *	Floating conversion bar
 *
 *
 **/
add_action('wp_footer', 'dg_add_floating_bars');
function dg_add_floating_bars() {

	$active = TRUE;

	// Home Page
	if ( is_page(7019) ) {

		$html = '<input type="text" placeholder="First Name" class="name-text-input small" id="create-account-fname"></input>
				<a class="gem-button small create-account-popup" href="/my-account/">Create Free Account</a>';
		$page_id = 7019;

		if ( is_user_logged_in() ): $active = FALSE; endif;

	}
	// Guitar God Page
	if ( is_page(23188) ) {

		$html = '
				<img src="https://daviesguitars.io/wp-content/uploads/2019/10/white-pentagram.png" style="max-width: 40px; float:left;"></img>
				<a class="gem-button medium small" href="/become-guitar-god/confirm/?level=1" style="float: right;">Join Now</a>
				';
		$page_id = 23188;

	}

	if ( isset($html) && isset($page_id) && $active == TRUE ) {

		dg_floating_bar($html, $page_id);
		
	}

}

function dg_floating_bar($html, $page_id) {

	ob_start();

	if ( is_page($page_id) ) : ?>

	<style>

		.floating-bar {
		    position: fixed;
		    bottom: 0px;
		    width: 100%;
		    background: black;
		    z-index: 99999;
		    box-shadow: 0px -5px 5px 0px #00000021;
		    opacity: 0;
		    visibility: hidden;
		    transition: all .3s ease-in-out;
		}
		.floating-bar.show {
			opacity: 1;
			visibility: visible;
		}
		input.small {
		    padding: 7.5px 10px !important;
		}
		.floating-bar input, .floating-bar a.gem-button.small {
		    margin: 0px !important;
		    margin-bottom: 10px !important;
		}
		.floating-bar {
		    padding: 10px;
		    padding-bottom: 0px;
		}
	</style>

	<div class="create-account-bar floating-bar">
		<div class="input-wrapper">
			<?php echo $html ?>
		</div>
	</div>

	<script type="text/javascript">
		jQuery(document).scroll(function(e){
			var scrollAmount = jQuery(window).scrollTop();
			var containHeight = jQuery('.floating-bar').height();
			
			if (scrollAmount > 500) {
				if( jQuery('.create-account-bar').hasClass("show")) {
			        // jQuery('.mobile-filter-container').removeClass( "show" );
			    }
			    else {
			       	jQuery('.create-account-bar').addClass( "show" );
			       	jQuery('#open-chat-button').css('margin-bottom', containHeight);
			    }
			} else {
			if ( jQuery('.create-account-bar').hasClass("show")) {
			        jQuery('.create-account-bar').removeClass( "show" );
			        jQuery('#open-chat-button').css('margin-bottom', '0px');
			    }
			}
		});                                     
	</script>


	<?php endif;

	echo ob_get_clean();
}

// Link card
add_shortcode('anchor-card', 'dg_anchor_card');
function dg_anchor_card( $atts ) {
	ob_start();

		// Define attributes and their defaults
    extract( shortcode_atts( array (
        'link' 		=> '',
        'text' 		=> '',
        'icon' 		=> ''
    ), $atts ) );

	$anchor 	= $atts['link'];
	$text 		= $atts['text'];
	$icon 		= $atts['icon'];

	?>

	<a class="dg-icon-anchor large" href="<?php echo $anchor ?>">
		<div class="vertical-wrapper">
			<div class="dg-icon"><?php echo $icon ?></div>
			<div class="dg-title"><p class=""><?php echo $text ?></p></div>
		</div>
	</a>

	<?php
	return ob_get_clean();
}

// Shortcode to user profile
add_shortcode('user-profile-link', 'dg_user_profile_link');
function dg_user_profile_link() {
	$user 				= wp_get_current_user();
	$user_id 			= $user->ID;
	$user_url 			= get_author_posts_url( $user_id );

	return $user_url;
}


// Login and redirect to same page
add_shortcode('dg-login', 'login_shortcode_redirect');
function login_shortcode_redirect() {

	return site_url('/wp-login.php?redirect_to=' . get_permalink());

}
// Register and redirect to same page
add_shortcode('dg-register','registration_shortcode_redirect');
function registration_shortcode_redirect() {
	return site_url('/wp-login.php?action=register&redirect_to=' . get_permalink());
}

// Promotional banner for tabs
add_action( 'dg_tabs_before_content', 'dg_do_promotional_banner'); 		// add it to tabs area
add_action( 'cd_wp_under_header', 'dg_do_promotional_banner', 100); 	// add it to the woocommerce listings

function dg_do_promotional_banner() {

	echo do_shortcode('[promotional-banner]');

}

add_shortcode('promotional-banner', 'dg_promotional_banner');	// create shortcode
function dg_promotional_banner() {

	return;

	$exercise_pages = dg_get_exercise_pages();

	// Defaults
	$url 			= '/';
	$text 			= 'Join our network of heavy metal guitarists, click here to learn more about our features';
	$objective 		= 'Create Account';
	$button_text 	= 'Learn More';
	$class 			= '';
	$target 		= '';

	// Exercise pages
	if ( is_page( $exercise_pages ) ) {

		if ( dg_is_user_guitar_god() ) {

			// User is guitar god

			return;

			$url = '/learning-dashboard/';
			$text = 'Congratulations, as a Guitar God you have full unrestricted access to all content';
			$objective = 'Guitar Exercises Page - Visit Learning Dashboard';
			$class = '';
			$button_text = 'Visit Learning Dashboard';
			$target = 'target="_blank"';

		} elseif ( is_user_logged_in() ) {

			// User isn't guitar god

			$url = '/become-guitar-god/';
			$text = 'Gain full access to all of our metal exercises & lessons with a Guitar God membership';
			$objective = 'Guitar Exercises Page - Become A Guitar God';
			$class = '';
			$button_text = 'Become A God';
			$target = 'target="_blank"';

		} else {

			// User isn't logged in

			$url = '/my-account/';
			$text = 'Join our network of heavy metal guitarists, click here to learn more about our features';
			$objective = 'Guitar Exercises Page - Create Account';
			$class = 'create-account-popup exercises-page';
			$button_text = 'Create Free Account';

		}

	} else {

		return;

	}

	$output = '<div class="promo-banner '.$class.'" data-objective="'.$objective.'">
			<div class="container">
				<a href="'.$url.'" class="col-md-12" '.$target.'>
					<p>'.$text.'</p>
					<span class="gem-button">'.$button_text.'</span>
				</a>
			</div>
		</div>';

	return $output;
}

add_shortcode('global-search', 'cd_global_search');
function cd_global_search() { 

	ob_start();

	?>

	<style>
		.global-search .cd-search-query {
		    width: 100% !important;
		}
		.search-overlay .cd-search-query, #home-search .cd-search-query {
			padding: 25px !important;
			font-size: 24px;
		}
		.search-overlay span.cd-search-submit-icon, #home-search span.cd-search-submit-icon {
			font-size: 36px !important;
			padding: 16px !important;
		}
		.global-search .search-wrapper {
		    position: relative;
		}
		span.cd-search-submit-icon {
		    position: absolute;
		    top: 0px;
		    right: 0px;
		    font-size: 24px;
		    padding: 12px 24px;
		    line-height: initial;
		    color: #262626;
		    cursor: pointer;
		}
		.global-search {
		    position: relative;
		    max-width: 1200px;
		    margin: auto;
		    display: block;
		}
		.search-in-dropdown {
		    box-shadow: 0px 2px 2px 0px #00000012;
		    border: solid 1px #eaeaea;
		    border-top: none;
		}
		.search-in-dropdown .search-in {
		    padding: 5px 25px;
		    border-bottom: solid 1px #eaeaea;
		    cursor: pointer;
		    background: white;
		}
		.search-in-dropdown .search-in:hover {
		    background-color: #eaeaea;
		}
		span.search-in-type {
		    font-weight: 700;
		    font-family: 'Kiona';
		    margin-left: 25px;
		    color: #262626;
		}
		span.search-query-text {
		    font-style: italic;
		    font-size: 24px;
		    font-weight: 300;
		}
		.search-in-dropdown {
		    position: absolute;
		    width: 100%;
		    transition: all .1s ease-in-out;
		    opacity: 1;
		    display:block;
		}
		.search-in-dropdown.hide {
		    opacity: 0;
		    display: none;
		}
		span.search-in-type i {
		    margin-left: 5px;
		    vertical-align: middle;
		}
	</style>

	<form class="global-search" method="post" action="">
		<div class="search-wrapper">
			<input class="cd-search-query" type="text" placeholder="Searching in Guitar Tones & Tabs" autocomplete="off">
			<span class="cd-search-submit-icon"><i class="fas fa-search"></i></span>
		</div>
		<div class="search-in-dropdown hide">
			<div class="search-in" data-search-type="tones"><span class="search-query-text"></span><span class="search-in-type">In Tones<i class="fas fa-search"></i></span></div>
			<div class="search-in" data-search-type="tabs"><span class="search-query-text"></span><span class="search-in-type">In Tabs<i class="fas fa-search"></i></span></div>
		</div>
		<input class="cd-search-submit" type="submit" style="display:none;">
	</form>


	<script type="text/javascript">
		
		var action, search_type, query, enc_space;

		// Build queries when user starts typing + show suggestions box
		jQuery('.cd-search-query').keyup(function() {
			var string = jQuery('.cd-search-query').val();

			if (string.length > 0) {
				jQuery('.search-query-text').html('"'+string+'"');
				jQuery('.search-in-dropdown').removeClass('hide');
			} else {
				jQuery('.search-in-dropdown').addClass('hide');
			}

			build_search_query();
		});

		jQuery('.search-in').click(function() {
			build_search_query(jQuery(this));
			//jQuery('.global-search').submit();
			change_url();
		});


		jQuery('.cd-search-submit-icon').click(function() {
			build_search_query();
			// jQuery('.global-search').submit();
			change_url();
			// Perform the submission
    		
		});

		function build_search_query(thisObj) {

			// Update vars
			if (thisObj) {
				console.log('This Object Detected');
				search_type = jQuery(thisObj).data('search-type');
			} else {
				console.log('No this object passed');
				search_type = 'tones'; //Default search to tones
			}

			console.log('Search type: ' + search_type);
			
			str 		= jQuery('.cd-search-query').val();
			enc_space 	= '%20';
			query 		= str.replace(/ /g, enc_space);

			// Build tones search type
			if (search_type == 'tones') {
				action = 'https://daviesguitars.io/explore/?_sf_s='+query+'&_sfm__price=0+500';
			}

			// Build tabs search type
			if (search_type == 'tabs') {
				action = 'https://daviesguitars.io/guitar-tabs/search/?_sf_s='+query;
			}

			jQuery('.global-search').attr('action', action);
		}

		function change_url() {
			var url = jQuery('.global-search').attr('action');
			window.location.href = url;
		}

	</script>

<?php 

	return ob_get_clean();

}

add_shortcode('home-search', 'cd_home_search');
function cd_home_search() { 

	ob_start();

	?>

	<form id="home-search" class="global-search" method="post" action="">
		<div class="search-wrapper">
			<input id="home-search-query" class="cd-search-query" type="text" placeholder="Searching in Guitar Tones & Tabs" autocomplete="off">
			<span id="home-submit-icon" class="cd-search-submit-icon"><i class="fas fa-search"></i></span>
		</div>
		<div id="home-search-in-dropdown" class="search-in-dropdown hide">
			<div class="search-in home-search-in" data-search-type="tones"><span class="search-query-text home-search-query-text"></span><span class="search-in-type">In Tones<i class="fas fa-search"></i></span></div>
			<div class="search-in home-search-in" data-search-type="tabs"><span class="search-query-text home-search-query-text"></span><span class="search-in-type">In Tabs<i class="fas fa-search"></i></span></div>
		</div>
		<input class="cd-search-submit" type="submit" style="display:none;">
	</form>


	<script type="text/javascript">
		// tones: https://daviesguitars.io/explore/?_sf_s=test%20search&_sfm__price=0+500
		// tabs: https://daviesguitars.io/guitar-tabs/search/?_sf_s=test%20search
		
		var action, search_type, query, enc_space;

		// Build queries when user starts typing + show suggestions box
		jQuery('#home-search-query').keyup(function() {
			var string = jQuery('#home-search-query').val();

			if (string.length > 0) {
				jQuery('.home-search-query-text').html('"'+string+'"');
				jQuery('#home-search-in-dropdown').removeClass('hide');
			} else {
				jQuery('#home-search-in-dropdown').addClass('hide');
			}

			build_search_query();
		});

		jQuery('.home-search-in').click(function() {
			build_search_query(jQuery(this));
			//jQuery('#home-search').submit();
			change_url();
		});

		jQuery('#home-submit-icon').click(function() {
			build_search_query();
			//jQuery('#home-search').submit();
			change_url();
		});

		function build_search_query(thisObj) {

			// Update vars
			if (thisObj) {
				console.log('This Object Detected');
				search_type = jQuery(thisObj).data('search-type');
			} else {
				console.log('No this object passed');
				search_type = 'tones'; //Default search to tones
			}

			console.log('Search type: ' + search_type);
			
			str 		= jQuery('#home-search-query').val();
			enc_space 	= '%20';
			query 		= str.replace(/ /g, enc_space);

			// Build tones search type
			if (search_type == 'tones') {
				action = 'https://daviesguitars.io/explore/?_sf_s='+query+'&_sfm__price=0+500';
			}

			// Build tabs search type
			if (search_type == 'tabs') {
				action = 'https://daviesguitars.io/guitar-tabs/search/?_sf_s='+query;
			}

			jQuery('#home-search').attr('action', action);
		}

		function change_url() {
			var url = jQuery('#home-search').attr('action');
			window.location.href = url;
		}

	</script>

<?php 
	
	return ob_get_clean();

}


add_shortcode( 'artist_guitar_tabs', 'generate_list_of_artists' );
function generate_list_of_artists() {

	ob_start();

	// Gather artists from artist taxonomy
	$artists = get_terms('artist'); ?>

	<div class="row">
	<?php
	$counter = 1;


	foreach ($artists as $artist) {

		$id = $artist->term_id;
		$termArray = get_term_meta($artist->term_id);
		$imageThumbnail = '';

		if ( ! empty($termArray['featured_image'][0]) ) {

			$imageID = $termArray['featured_image'][0];
			$imageThumbnail = wp_get_attachment_image_src($imageID, 'medium')[0];

		}

		// Create First letter place holder if no image has been added for artist
		if ($imageThumbnail == '') {

			$firstCharacter = $artist->name[0];

		} else {

			$firstCharacter = '';

		}

		//Gather tabs from each artist category
		$artist_query= new WP_Query( array(
	    'post_type' => 'guitar_tab',
	    'tax_query' => array(
	        array(
	            'taxonomy' => 'artist',
	            'terms' => $artist,
	            'field' => 'slug'
		        	)
		    	)
			)
		);

		$numberOfPosts = $artist_query->found_posts;

		?>

	    <div class="wpb_column vc_column_container vc_col-sm-3 artist-id-<?php echo $id ;?> artist-tabs-link">
			<div class="vc_column-inner clearfix">
				<a href="<?php echo get_term_link($artist);?>">
					<div class="artist-image" style="background-image: url('<?php echo $imageThumbnail ;?>')"><?php echo $firstCharacter; ?></div>
					<h2 class="entry-title artist-title"><?php echo $artist->name; ?></h2>
					<p><?php echo $numberOfPosts; ?> Tabs By <?php echo $artist->name; ?></p>
				</a>
			</div>
		</div>

		<?php

		if ($counter % 4 == 0) {
			echo '</div><div class="row">';
		}

		wp_reset_postdata();

		$counter++;
	} //End foreach

	return ob_get_clean();
}


add_shortcode( 'list_of_developers', 'generate_list_of_developers' );
function generate_list_of_developers() {

	ob_start();

	// Gather artists from artist taxonomy
	$artists = get_terms('developer'); ?>

	<div class="row">
	<?php

	$counter = 1;
	foreach ($artists as $artist) {

		$id = $artist->term_id;
		$termArray = get_term_meta($artist->term_id);
		$imageID = $termArray['featured_image'][0];
		$imageThumbnail = wp_get_attachment_image_src($imageID, 'medium')[0];

		// Create First letter place holder if no image has been added for artist
		if ($imageThumbnail == '') {
			$firstCharacter = $artist->name[0];
		} else {
			$firstCharacter = '';
		}

		//Gather tabs from each artist category
		$developer_query = new WP_Query( array(
	    'post_type' => 'guitar_tone',
	    'tax_query' => array(
	        array(
	            'taxonomy' => 'developer',
	            'terms' => $artist,
	            'field' => 'slug'
		        	)
		    	)
			)
		);
		$numberOfPosts = $developer_query->found_posts;


		?>
		
	    <div class="wpb_column vc_column_container vc_col-sm-3 artist-id-<?php echo $id ;?> artist-tabs-link">
			<div class="vc_column-inner clearfix">
				<a href="<?php echo get_term_link($artist);?>">
					<div class="artist-image" style="background-image: url('<?php echo $imageThumbnail ;?>')"><?php echo $firstCharacter; ?></div>
					<h2 class="entry-title artist-title"><?php echo $artist->name; ?></h2>
					<p><?php echo $numberOfPosts; ?> Tones By <?php echo $artist->name; ?></p>
				</a>
			</div>
		</div>

		<?php
		if ($counter % 4 == 0) {
			echo '</div><div class="row">';
		}
		$counter++;
		wp_reset_postdata();

	} //End foreach

	return ob_get_clean();
}

// Generates this shortcode: [artist_guitar_tabs]



/*
**
**		@ Description: Shortcode that loops through guitar tones
**		@ Post Type: Guitar Tone
**
*/
add_shortcode( 'guitar_tones_list', 'generate_list_of_tones' );
function generate_list_of_tones( $atts ) {

	ob_start();

	?>
	<script type="text/javascript"> 
		//Adds hover class when hovering over product items
		jQuery(document).ready(function() {
		    jQuery("li.guitar-tab-list").hover(
		        function () {
		            jQuery(this).addClass('hover');
		        }, 
		        function () {
		            jQuery(this).removeClass('hover');
		        }
		    );
		});
	</script>

	<?php

	// Define attributes and their defaults
    extract( shortcode_atts( array (
        'tone_type' 		=> '',
        'posts' 			=> 45
    ), $atts ) );

    // 
    $tones_query = new WP_Query(array(
        'post_type' 		=> 	'guitar_tone',
        'posts_per_page' 	=> 	$posts,
        'meta_key'			=> 	'tone_type',
        'meta_value'		=>	$tone_type,
        'orderby'			=> 'rand'

    ));

    //Build template query
    $queryString = $_GET['template'];
	if ($queryString == 'grid') {
		$templateClass = 'grid-template';
	} elseif ($queryString == 'list') {
		$templateClass = 'list-template';
	} else {
		// set default
		$templateClass = 'grid-template';

	}


    echo '<h3 class="query-string" style="text-align:center;">'. $tones_query->found_posts.' tones found</h3>';

?>

<a href="?template=grid" class="listing-template-icon"><i class="fas fa-th"></i></a>
<a href="?template=list" class="listing-template-icon"><i class="fas fa-th-list"></i></a>
<!-- pagination -->
<?php chris_pagination($tones_query); ?>


<?php if($templateClass == 'grid-template') {echo '<div class="row">';} //End row ?>
<?php $counter = 1; //counter for row breaks

    while ($tones_query->have_posts()) {
        $tones_query->the_post();

        if ($templateClass == 'grid-template') { 
	        ?>
		    <div class="wpb_column vc_column_container vc_col-md-4 vc_col-sm-12">
				<div class="vc_column-inner clearfix">
		        	<div class="guitar-tab-container-wrapper <?php echo $templateClass; ?>">
		        		<?php get_template_part( 'content-loop-guitar-tones', 'content-loop-guitar-tones' ); ?>
		        	</div>
		    	</div>
		    </div>

		   	<?php
	        if ($counter % 3 == 0) {
				echo '</div><div class="row">';
        	}
			$counter++;

		    
        } elseif ($templateClass == 'list-template') {
        	?>
    		<div class="guitar-tab-container-wrapper <?php echo $templateClass; ?>">
        		<?php get_template_part( 'content-loop-guitar-tones', 'content-loop-guitar-tones' ); ?>
        	</div> 
        	<?php


		}
    } // End while loop

    wp_reset_postdata(); ?>

<!-- pagination -->
<?php chris_pagination($tones_query); ?>

	<?php

	return ob_get_clean();

}



/*
**
**		@ Description: Shortcode that loops through guitar tones (in product format) - used for saved tones
**		@ Post Type: Product ()Guitar Tone
**
*/
add_shortcode('saved-tones', 'dg_generate_list_of_saved_tones_products');
function dg_generate_list_of_saved_tones_products() {

	ob_start();

	?>

	<script type="text/javascript"> 
		//Adds hover class when hovering over product items
		jQuery(document).ready(function() {
		    jQuery("li.guitar-tab-list").hover(
		        function () {
		            jQuery(this).addClass('hover');
		        }, 
		        function () {
		            jQuery(this).removeClass('hover');
		        }
		    );
		});
	</script>

	    <?php

	    	$user_id 		= get_current_user_id();
	    	$meta_key 		= 'dg_saved_guitar_tone';
	    	$templateClass 	= 'list-template';
	    	$raw_tones   	= get_user_meta( $user_id, $meta_key, FALSE );
	    	$saved_tones 	= array_values($raw_tones[0]);
	    	$post_ids 		= array();

	    	foreach($saved_tones as $tone) {

	    		$post_ids[] = (int)$tone;

	    	}

	        $args = array(

	            'post_type' => 'product',
	            'posts_per_page' => 24,
	            'post__in'=> $post_ids

	        );

	        if ( empty( $post_ids ) ) {

	        	// If they havent saved any tones
				echo '<p class="subtitle" style="text-align:center;">You haven\'t saved any tones yet. Go save some in our <a href="/explore/category/guitar-tone/">tone explorer</a>.</p>';

	        } else {

		        $saved_tones_loop = new WP_Query( $args );

		        if ( $saved_tones_loop->have_posts() ) {

		        	echo '<div class="button-wrapper"><a class="gem-button" href="/explore/category/guitar-tone/" target="_blank" style="float: right;">Find More Tones</a></div>';

		        	chris_pagination($saved_tones_loop);

		        	echo '<div class="products row inline-row columns-4 item-animation-move-up">';

		            while ( $saved_tones_loop->have_posts() ) : $saved_tones_loop->the_post();

		                wc_get_template_part( 'content', 'product' );

		            endwhile;

		            echo '</div>';

		            chris_pagination($saved_tones_loop);

		        } else {

		        	// Fallback measure
					echo '<p class="subtitle" style="text-align:center;" >You haven\'t saved any tones yet. Go save some in our <a href="/explore/category/guitar-tone/">tone explorer</a>.</p>';

		        }

	        }

	        wp_reset_postdata();

	    ?>

<?php

	return ob_get_clean();

}


/*
**
**		@ Description: Shortcode that loops through guitar tabs saved to account met data
**		@ Post Type: guitar_tab
**		@ Default: 'saved', 'in-progress', 'completed'
**		@ EG: [saved-tabs type="in-progress"]
*/
add_shortcode('saved-tabs', 'dg_generate_list_of_saved_tabs');
function dg_generate_list_of_saved_tabs( $atts ) {

	ob_start();

	?>

	<script type="text/javascript"> 
		//Adds hover class when hovering over product items
		jQuery(document).ready(function() {
		    jQuery("li.guitar-tab-list").hover(
		        function () {
		            jQuery(this).addClass('hover');
		        }, 
		        function () {
		            jQuery(this).removeClass('hover');
		        }
		    );
		});
	</script>

	    <?php

	    	// Shortcode atts
	    	// Default = 'saved'
	    	// Options = 'saved', 'in-progress', 'complete'
			$atts 			= shortcode_atts( array('type' => 'saved'), $atts, 'saved-tabs' );
			$type 			= $atts['type'];

	    	$user_id 		= get_current_user_id();
	    	$templateClass 	= 'list-template';
	    	$post_ids 		= array();

	    	if ( $type == 'in-progress' ) {

	    		/*
				**	In Progress Tabs
	    		*/

				$meta_key 		= 'dg_tab_progress_status';
				$raw_tabs   	= get_user_meta( $user_id, $meta_key, FALSE );
				$saved_tabs 	= array_values($raw_tabs[0]);
				$i = 0;
				
				foreach ($saved_tabs as $tab) {

					if ($tab['status'] == 'in-progress') {
						$post_ids[] = $tab['id'];
					}

					$i++;
				}

				$string = 'You haven\'t marked any tabs as in progress yet. You can do this on any <a href="/guitar-tabs/">guitar tab</a> page whilst logged in.';
				// error_log('In Progress IDS: '. print_r($post_ids, TRUE));

	    	} elseif( $type == 'complete' ) {

	    		/*
				**	Complete Tabs
	    		*/

				$meta_key 		= 'dg_tab_progress_status';
				$raw_tabs   	= get_user_meta( $user_id, $meta_key, FALSE );
				$saved_tabs 	= array_values($raw_tabs[0]);
				$i = 0;
				
				foreach ($saved_tabs as $tab) {

					if ($tab['status'] == 'complete') {
						$post_ids[] = $tab['id'];
					}

					$i++;
				}

				$string = 'You haven\'t completed any of your tabs yet. You have to mark a tab as complete for it to show up in here.';
				// error_log('Completed IDS: '. print_r($post_ids, TRUE));

	    	} else {

	    		/*
				**	Saved Tabs
	    		*/

		    	$meta_key 		= 'dg_saved_guitar_tab';
		    	$raw_tabs   	= get_user_meta( $user_id, $meta_key, FALSE );
		    	$saved_tabs 	= array_values($raw_tabs[0]);

		    	foreach($saved_tabs as $tab) {
		    		$post_ids[] = (int)$tab;
		    	}

		    	$string = 'You haven\'t saved any guitar tabs yet. Go save some by exploring through the <a href="/guitar-tabs/">guitar tabs</a>.';
		    	// error_log('Saved IDS: '. print_r($post_ids, TRUE));
	    	}


	        $args = array(

	            'post_type' => 'guitar_tab',
	            'posts_per_page' => 24,
	            'post__in'=> $post_ids

	        );

	        if ( empty( $post_ids ) ) {

	        	// If they havent saved any tones
				echo '<p class="subtitle" style="text-align:center;">'.$string.'</p>';

	        } else {

		        $saved_tabs_loop = new WP_Query( $args );

		        if ( $saved_tabs_loop->have_posts() ) {

		        	chris_pagination($saved_tabs_loop);

		            while ( $saved_tabs_loop->have_posts() ) : $saved_tabs_loop->the_post(); ?>

            			<div class="custom-post-container-wrapper">

							<?php get_template_part( 'content-loop-guitar-tabs', 'content-loop-guitar-tabs' ); ?>

						</div>

		            <?php endwhile;

		            chris_pagination($saved_tabs_loop);

		        } else {

		        	// Fallback measure
					echo '<p class="subtitle" style="text-align:center;">'.$string.'</p>';

		        }

	        }

	        wp_reset_postdata();

	    ?>

<?php

	return ob_get_clean();

}


/*
**
**		@ Description: Shortcode that loops through guitar tabs
**		@ Post Type: Guitar Tab
**
*/
add_shortcode( 'guitar_tabs_list', 'generate_list_of_tabs' );
function generate_list_of_tabs() {

	ob_start();

	$list_of_tabs_query = new WP_Query(array(
	    'post_type' 		=> 	'guitar_tab',
	    'posts_per_page' 	=> 	25,
	    'orderby'			=> 'rand'

	));

	if ( $list_of_tabs_query->have_posts() ) {
		echo '<h3 class="query-string" style="text-align:center;">'. $list_of_tabs_query->found_posts.' tabs found</h3>';

		chris_pagination($list_of_tabs_query); ?>

		<script type="text/javascript"> 
			//Adds hover class when hovering over product items
			jQuery(document).ready(function() {
			    jQuery("li.guitar-tab-list").hover(
			        function () {
			            jQuery(this).addClass('hover');
			        }, 
			        function () {
			            jQuery(this).removeClass('hover');
			        }
			    );
			});
		</script>  

		<?php
		while ($list_of_tabs_query->have_posts()) {

			$list_of_tabs_query->the_post(); ?>

			<div class="custom-post-container-wrapper">
				<?php get_template_part( 'content-loop-guitar-tabs', 'content-loop-guitar-tabs' ); ?>
			</div>

			<?php
		}

	    wp_reset_postdata();
		chris_pagination($list_of_tabs_query);

	} else {
		echo "<h2 class='' style='text-align:center;'>Sorry Mate, Couldn't Find Anything!</h2>";
	}

	return ob_get_clean();

}



/*
**
**		@ Description: Shortcode that loops through coupons in members area
**		@ Post Type: Coupons
**
*/
add_shortcode( 'discount-codes', 'partner_promotions' );
function partner_promotions() {

	ob_start();

	$discount_codes_query = new WP_Query(array(
	    'post_type' 		=> 	'coupon',
	    'posts_per_page' 	=> 	50,
	    'orderby'			=> 'rand'

	));

	if ( $discount_codes_query->have_posts() ) {
		echo '<h3 class="query-string" style="text-align:center;">You Have '. $discount_codes_query->found_posts.' Coupons!</h3>';

		// chris_pagination($query); ?>

		<script type="text/javascript"> 
			//Adds hover class when hovering over product items
			jQuery(document).ready(function() {
			    jQuery("li.guitar-tab-list").hover(
			        function () {
			            jQuery(this).addClass('hover');
			        }, 
			        function () {
			            jQuery(this).removeClass('hover');
			        }
			    );
			});
		</script>  


		<div class="row">

		<?php

		$counter = 1;

		while ($discount_codes_query->have_posts()) {

			$discount_codes_query->the_post(); ?>

		    <div class="wpb_column vc_column_container vc_col-md-4 vc_col-sm-12">
				<div class="vc_column-inner clearfix">
		        	<div class="guitar-tab-container-wrapper <?php echo $templateClass; ?>">
		        		<?php get_template_part( 'content-loop-coupons', 'content-loop-coupons' ); ?>
		        	</div>
		    	</div>
		    </div>

		   	<?php
	        if ($counter % 3 == 0) {
				echo '</div><div class="row">';
        	}

			$counter++;

		}

	    wp_reset_postdata();
		// chris_pagination($query);

	} else {
		echo "<h2 class='' style='text-align:center;'>Sorry Mate, Couldn't Find Anything!</h2>";
	}

	return ob_get_clean();

}

// Notifications emails
add_shortcode('practice-reminder-email', 'dg_send_practice_reminders');
function dg_send_practice_reminders() {

	if ( dg_is_user_guitar_god() ) {

		// are allowed
		$button = '<a class="gem-button practicer-reminder-button small grey" target="_blank" href="/my-account/notification-preferences/">Set Practice Reminders</a>';

	} elseif ( is_user_logged_in() ) {

		// logged in, but not allowed
		$button = '<a class="gem-button practicer-reminder-button guitar-god-popup small grey" href="/my-account/notification-preferences/">Set Practice Reminders</a>';

	} elseif ( ! is_user_logged_in() ) {

		// Not logged in
		$button = '<a class="gem-button practicer-reminder-button create-account-popup small grey" href="/my-account/notification-preferences/">Set Practice Reminders</a>';

	}

	return $button;
}

/*Social Subscribe buttons*/
// ?
// add_action('chris_top_bar', 'social_subscribe_buttons_action');
add_action('product-right-of-price', 'social_subscribe_buttons_action');
function social_subscribe_buttons_action() {
	echo do_shortcode('[follow-buttons]');
}

add_shortcode('follow-buttons', 'social_subscribe_buttons');
function social_subscribe_buttons() { 

	ob_start();

	?>

	<div class="facebook-follow">
		<div class="fb-like" data-href="https://www.facebook.com/thedaviesguitars/" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2&appId=856085001195847&autoLogAppEvents=1';
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	</div>

<?php

	return ob_get_clean();

}



// Popup search form generator
add_shortcode('search-form-shortcode', 'search_tools_dropdown');


//Generates input fields for search
function search_tools_dropdown() { 

	ob_start();

	?>
<div id="home-banner-search" class="wpb_text_column wpb_content_element  wpb_animate_when_almost_visible wpb_fadeIn fadeIn wpb_start_animation animated">
	<div class="wpb_wrapper" style="position:relative;">
		<div id="tones-search-form" class="subtitle search-container active tones-search-form search-only" style="text-align: center;"><?php echo do_shortcode('[searchandfilter id="22491"]')?></div>
		<div id="tabs-search-form" class="subtitle search-container tabs-search-form" style="text-align: center;"><?php echo do_shortcode('[searchandfilter id="1960"]')?></div>
		<div id="tools-search-form" class="subtitle search-container tools-search-form" style="text-align: center;">
			<select name="select-tools" onchange="document.location=this.value;">
				<option value="">Select A Tool</option>
				<option value="/online-guitar-amp-simulator/">Guitar Amp</option>
				<option value="/chromatic-online-guitar-tuner/">Tuner</option>
				<option value="/online-metronome/">Metronome</option>
			</select>
		</div>
	</div>
</div>
<?php 

	return ob_get_clean();

} 

// Generates fields for selecting the right search form
add_shortcode('search-selection', 'post_type_selection_search');
function post_type_selection_search() { 

	ob_start();

	?>
<div id="tones-tools-tabs" class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1553335163736">
	<div class="wpb_column vc_column_container vc_col-sm-4 vc_col-xs-4">
		<div class="vc_column-inner">
			<div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element wpb_animate_when_almost_visible wpb_fadeIn fadeIn text-button active tones-button wpb_start_animation animated">
					<div class="wpb_wrapper">
						<h2 style="text-align: center;"><span style="color: #ffffff;">Tones</span></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wpb_column vc_column_container vc_col-sm-4 vc_col-xs-4">
		<div class="vc_column-inner ">
			<div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element wpb_animate_when_almost_visible wpb_fadeIn fadeIn text-button tools-button wpb_start_animation animated" id="tools-button">
					<div class="wpb_wrapper">
						<h2 style="text-align: center;"><span style="color: #ffffff;">Tools</span></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wpb_column vc_column_container vc_col-sm-4 vc_col-xs-4">
		<div class="vc_column-inner ">
			<div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element wpb_animate_when_almost_visible wpb_fadeIn fadeIn text-button tabs-button wpb_start_animation animated" id="tabs-button">
					<div class="wpb_wrapper">
						<h2 style="text-align: center;"><span style="color: #ffffff;">Tabs</span></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
	
	return ob_get_clean();

} 

/*

		@ lets see if we can hook onto search and filter results

*/
add_shortcode('cd-active-filter', 'cd_print_current_filter');
function cd_print_current_filter() {

	ob_start();

	global $searchandfilter;

	$sf_current_query 	= $searchandfilter->get(22491)->current_query();
	$search_term 		= $sf_current_query->get_search_term();

	$sf_object 			= $sf_current_query->get_array();
	// print_r( $sf_object );

	$i = 0;

	if ($search_term !== '') {
		echo '<span class="search-query">Showing results for "<span class="search-term">'.$search_term.'"</span></span>';
		$i++;
	}

	echo '<div class="filter-results">';
	foreach( $sf_object as $filter ) {
		// Skip empty names - this will basically skip price range
		if ($filter['singular_name'] == '') {
			continue;
		}

		$slug = $filter['active_terms'][0]['value'];

		$label 	= '<span class="filter-label '.$slug.'">'.$filter['singular_name'].'</span>';
		$result = '<span class="filter-result '.$slug.'">'.$filter['active_terms'][0]['name'].'</span>';
		$icon 	= '<span class="filter-remove '.$slug.'" value="'.$slug.'"><i class="fas fa-times"></i></span>';

		// Final output of string
		echo '<span class="">'.$label.': '.$result.$icon.'<br>';

		$i++;
	}
	if ($i == 1) {
		echo '<a href="#" class="search-filter-reset" data-search-form-id="22491" data-sf-submit-form="always">Clear</a>'; // Come up with if statement to show this
	} elseif ($i > 1) {
		echo '<a href="#" class="search-filter-reset" data-search-form-id="22491" data-sf-submit-form="always">Clear All</a>'; // Come up with if statement to show this
	}
	echo '</div>';

	?>
		<script type="text/javascript">
			jQuery('.filter-remove').click(function() {

				var filterRemoveValue = jQuery(this).attr('value');

				// Change value to nothing
				jQuery('.searchandfilter ul li select option[value="'+filterRemoveValue+'"]').val('');

				//Submit with new null value
				jQuery('.sidebar li.sf-field-submit input').trigger('click');

			});
		</script>
	<?php

	return ob_get_clean();

}

add_shortcode('lock-content-fill-logged-out-users', 'cd_lock_div_fill_container_logged_out_users');
function cd_lock_div_fill_container_logged_out_users() {

	ob_start();

	if ( ! is_user_logged_in() ) {
		?>
			<div class="lock-content logged-in-popup">
				<div class="inner-wrap">
					<div class="lock-align">
						<i class="fas fa-lock"></i>
						<span>You must be a logged in to access this. Click here to create a free account.</span>
					</div>
				</div>
			</div>
		<?php
	} else {
		?>
			<div class="active-guitar-god"></div>
		<?php
	}

	return ob_get_clean();
}

add_shortcode('lock-content-fill', 'cd_lock_div_fill_container');
function cd_lock_div_fill_container() {

	ob_start();

	if ( rcp_user_has_active_membership() == false ) {
		?>
			<div class="lock-content guitar-god-popup">
				<div class="inner-wrap">
					<div class="lock-align">
						<i class="fas fa-lock"></i>
						<span>You must be a <a href="/become-guitar-god/">Guitar God</a> in order to access this.</span>
					</div>
				</div>
			</div>
		<?php
	} else {
		?>
			<div class="active-guitar-god"></div>
		<?php
	}

	return ob_get_clean();
}

// shortcode for mobile account button
add_shortcode('mobile-account-button', 'dg_mobile_account_button');
function dg_mobile_account_button() {

	if (is_user_logged_in()) {
		$result = '<div class="shiftnav-menu-icon header-account-button display-account-popup"><a><i class="fas fa-user"></i><span class="visuallyhidden">Account</span></a></div>';
	} else {
		$result = '<div class="shiftnav-menu-icon header-account-button "><a href="/my-account/"><i class="fas fa-user"></i><span class="visuallyhidden">Account</span></a></div>';		
	}
	return $result;
}

// shortcode for mobile account button
add_shortcode('mobile-create-account-button', 'dg_mobile_create_account_button');
function dg_mobile_create_account_button() {

	if (is_user_logged_in()) {
		$result = '';
	} else {
		$result = '<div class="shiftnav-menu-icon create-account-notification create-account-popup"><a href="#"><i class="_mi fa fa-bell" aria-hidden="true"></i><span class="visuallyhidden">Create Account</span></a></div>';		
	}
	return $result;
}

// tab switching login / register
add_shortcode( 'dg-login-register', 'dg_login_register_tab_form' );
function dg_login_register_tab_form() {

	ob_start(); 

	?>
 	
	<div class="woocommerce modal-login-register">

	 	<div class="row" id="customer_details_custom">


			<div class="col-xs-12">
	 			<div class="login-tab styled-subtitle modal-tab active">Login</div>
	 			<div class="register-tab styled-subtitle modal-tab">Register</div>
	 		</div>

	 		<div class="modal-content-wrapper">

	 			<!-- Login form -->
		 		<div class="col-sm-12 col-xs-12 modal-login-register-content checkout-login active" id="modal-login">
					<div class="login-form">
						<?php woocommerce_login_form(); ?>
					</div>
			 	</div>

			 	<!-- Registration form -->
			 	<div class="col-sm-12 col-xs-12 modal-login-register-content my-account-signup" id="modal-register" style="display:none;">
					<form method="post" class="woocommerce-form woocommerce-form-register register row" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<p class="form-row form-row-wide col-md-6">
					       <label for="first_name"><?php _e( 'First Name', 'woocommerce' ); ?> <span class="required">*</span></label>
					       <input type="text" class="input-text" name="first_name" id="first_name" value="<?php if (!empty($_POST['first_name'])) { echo esc_attr( wp_unslash($_POST['first_name']));} else { echo '';} ?>" />
				       </p>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-6">
								<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
							</p>

						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-12">
							<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>


						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-12">
								<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
							</p>

						<?php else : ?>

							<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

						<?php endif; ?>

						<p class="col-md-12">
							<?php do_action( 'woocommerce_register_form' ); ?>
						</p>

						<p class="woocommerce-FormRow form-row col-md-12">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
						</p>

						<?php do_action( 'woocommerce_register_form_end' ); ?>

					</form>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		// activate login tab
		jQuery( ".login-tab" ).click(function() {
			// deal with tabs first
		    if ( jQuery('.register-tab').hasClass("active")) {
		        jQuery('.register-tab').removeClass( "active" );
		    }
		    if ( ! jQuery( ".login-tab" ).hasClass( "active" ) ) {
		    	jQuery( ".login-tab" ).addClass( "active" );
		    }


		    //  Now deal with content
		    if( jQuery('#modal-register').hasClass("active")) {
		        jQuery('#modal-register').removeClass( "active" );
		        jQuery('#modal-register').fadeOut(100);

		    }
		    if ( ! jQuery('div#modal-login').hasClass("active")) {
		        jQuery('#modal-login').addClass( "active" );
		        jQuery('#modal-login').delay(100).fadeIn(100);
		    }
		});

		// activate register tab
		jQuery( ".register-tab" ).click(function() {
			// deal with tabs first
		    if ( jQuery('.login-tab').hasClass("active")) {
		        jQuery('.login-tab').removeClass( "active" );
		    }
		    if ( ! jQuery( ".register-tab" ).hasClass( "active" ) ) {
		    	jQuery( ".register-tab" ).addClass( "active" );
		    }

		    //  Now deal with content
		    if( jQuery('#modal-login').hasClass("active")) {
		        jQuery('#modal-login').removeClass( "active" );
		        jQuery('#modal-login').fadeOut(100);
		    }
		    if ( ! jQuery('#modal-register').hasClass("active")) {
		        jQuery('#modal-register').addClass( "active" );
		        jQuery('#modal-register').delay(100).fadeIn(100);
		    }
		});

	</script>

	<?php 

	return ob_get_clean();

}

add_shortcode( 'dg-register-only', 'dg_login_register_only_form' );
function dg_login_register_only_form() {

	ob_start(); 

	?>
 	
	<div class="woocommerce modal-login-register">

	 	<div class="row" id="customer_details_custom">

	 		<div class="modal-content-wrapper">

			 	<!-- Registration form -->
			 	<div class="col-sm-12 col-xs-12 modal-login-register-content my-account-signup" id="register">

			 		<div class="title-h3">Create Free Account</div>

					<form method="post" class="woocommerce-form woocommerce-form-register register row" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<p class="form-row form-row-wide col-md-6">
					       <label for="first_name"><?php _e( 'First Name', 'woocommerce' ); ?> <span class="required">*</span></label>
					       <input type="text" class="input-text" name="first_name" id="first_name" value="<?php if (!empty($_POST['first_name'])) { echo esc_attr( wp_unslash($_POST['first_name']));} else { echo '';} ?>" />
				       </p>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-6">
								<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
							</p>

						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-12">
							<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>


						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-12">
								<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
							</p>

						<?php else : ?>

							<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

						<?php endif; ?>

						<p class="col-md-12">
							<?php do_action( 'woocommerce_register_form' ); ?>
						</p>

						<p class="woocommerce-FormRow form-row col-md-12">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
						</p>

						<?php do_action( 'woocommerce_register_form_end' ); ?>

					</form>
				</div>
			</div>
		</div>
	</div>

	<?php 

	return ob_get_clean();

}

/**
 *
 *		Instant Chat
 *		@link https://kaine.pl/projects/wp-plugins/wise-chat-pro/documentation/shortcode/
 *		@link https://kaine.pl/projects/wp-plugins/wise-chat-pro/documentation/basics/
 *
 */
add_action('wp_footer', 'dg_chat');
function dg_chat() {
	?>

	<div class="fixed-chat-wrapper" style="display: none;">
	<div class="close-wrapper" id="close-chat-button"><i class="fas fa-times"></i>minimize</div>
	<?php echo do_shortcode('[wise-chat window_title="The Underworld (World Chat)" ]'); ?>
	</div>
	<div class="gem-button small" id="open-chat-button"><span class="chat-text">Underworld Chat</span><span class="chat-notifications-icon">0</span></div>

	<?php
}
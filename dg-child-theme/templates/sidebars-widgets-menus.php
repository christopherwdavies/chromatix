<?php
add_action( 'init', 'register_footer_menus' );
function register_footer_menus() {
	register_nav_menus(
		array(
			'footer-menu-1' => __( 'Footer Menu 1' ),
			'footer-menu-2' => __( 'Footer Menu 2' ),
			'footer-menu-3' => __( 'Footer Menu 3' ),
			'footer-menu-4' => __( 'Footer Menu 4' )
		)
	);
}

add_shortcode('footer-menu', 'cd_footer_menu');
function cd_footer_menu() {

	ob_start();

	?>
		<div class="row">
			<div class="col-md-3 col-xs-12">
				<h4>Explore</h4>
				<?php wp_nav_menu(
					array(
						'theme_location' => 'footer-menu-1',
						'menu_id' => 'footer-widget-menu',
						'menu_class' => apply_filters( 
											'footer-menu-item',
											'chris-custom-footer'
										),
						'container' => false
						)
					);
				?>
			</div>
			<div class="col-md-3 col-xs-12">
				<h4>Account</h4>
				<?php wp_nav_menu(
					array(
						'theme_location' => 'footer-menu-2',
						'menu_id' => 'footer-widget-menu',
						'menu_class' => apply_filters( 
											'footer-menu-item',
											'chris-custom-footer'
										),
						'container' => false
						)
					);
				?>
			</div>
			<div class="col-md-3 col-xs-12">
				<h4>Follow Us</h4>
				<ul id="footer-widget-menu">
					<li><a href="https://www.facebook.com/thedaviesguitars/" target="_blank"><i class="fab fa-facebook-f"></i>Facebook</a></li>
					<li><a href="https://www.instagram.com/davies_guitars/" target="_blank"><i class="fab fa-instagram"></i>Instagram</a></li>
					<li><a href="https://www.youtube.com/thedaviesguitars/" target="_blank"><i class="fab fa-youtube"></i>Youtube</a></li>
					<li><a href="mailto:chris@daviesguitars.io" target="_blank"><i class="far fa-envelope"></i>chris@daviesguitars.io</a></li>
				</ul>
			</div>
			<div class="col-md-3 col-xs-12">
				<h4>About Us</h4>
				<p>Davies Guitars is a digital guitar brand built for heavy metal guitarists.</p>
				<p>Our goal is help you become the most god forsaken guitarist on this planet.</p>
				<p class="subtitle" style="color: white;">Stay heavy,<br>Davies Guitars</p>
			</div>
		</div>
	<?php

	return ob_get_clean();
}

/**
 *	Create guitar menu sidebar
 */
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );
function wpdocs_theme_slug_widgets_init() {
	// Guitar sidebar
    register_sidebar( array(
        'name'          => __( 'Guitar Tutorials Sidebar', 'daviesguitars' ),
        'id'            => 'guitar-tutorials-sidebar',
        'description'   => __( 'This is a sitemap menu sidebar for guitar exercises and lessons.', 'daviesguitars' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    	) 
	);
	// account sidebar
	register_sidebar( array(
        'name'          => __( 'User Account Sidebar', 'daviesguitars' ),
        'id'            => 'user-account-sidebar',
        'description'   => __( 'This is the sidebar to be used on all account pages.', 'daviesguitars' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    	) 
	);
}

// Register new menu
add_action( 'init', 'register_account_menus' );
function register_account_menus() {
    register_nav_menus(
        array(
            'account-menu' => __( 'Account Menu' ),
            'guitar-menu' => __( 'Guitar Tutorials Menu' ),
            'under-account-header-menu' => __( 'Under Account Header Nav' )
        )
    );
}

/**
 *
 *  Filter the menu items
 *
 **/
add_filter( 'wp_nav_menu_objects', 'my_dynamic_menu_items' );

function my_dynamic_menu_items( $menu_items ) {

	if ( is_user_logged_in() ) {

	    foreach ( $menu_items as $key => $menu_item ) {

	        if ( 'profile-url' == $menu_item->title ) {

	        	$user 				= wp_get_current_user();
				$user_id 			= $user->ID;
				$user_url 			= get_author_posts_url( $user_id );

	            $menu_item->title = 'My Profile';
				$menu_item->url = $user_url;

	        }

	    }

	}

    return $menu_items;

}

// Search button in header
// add_action('thegem_before_nav_menu', 'add_search_button_to_menu');
function add_search_button_to_menu() {

	$search_icon = '<div id="left-menu-search" class="header-search-button menu-item menu-item-type-custom menu-item-object-custom "><i class="_mi fa fa-search" aria-hidden="true"></i><span class="">Search</span></div>';

	echo $search_icon;
	
}

// Print users name to menu
add_action('top_left_area', 'login_greeting');
function login_greeting() {

	global $current_user;
	echo '<div class="user-greeting"><a href="/my-account">Find Your Sound</a></div>';

}

// Walker
class CSS_Menu_Maker_Walker extends Walker {

  var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul>\n";
  }

  function end_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat("\t", $depth);
    $output .= "$indent</ul>\n";
  }

  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

    global $wp_query;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    $class_names = $value = '';        
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    /* Add active class */
    if(in_array('current-menu-item', $classes)) {
      $classes[] = 'active';
      unset($classes['current-menu-item']);
    }

    /* Check for children */
    $children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
    if (!empty($children)) {
      $classes[] = 'has-sub';
    }

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

    $output .= $indent . '<li' . $id . $value . $class_names .'>';

    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'><span>';
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    $item_output .= '</span></a>';
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  function end_el( &$output, $item, $depth = 0, $args = array() ) {
    $output .= "</li>\n";
  }
}

//Remove native woocommerce nav
remove_action('woocommerce_account_navigation','woocommerce_account_navigation');

//Add to woocommerce nav spot
add_action('woocommerce_account_navigation', 'cd_account_menu_accordion_action', 10);

// Add menu to dokan area 
add_action('cd_override_dokan_menu', 'cd_account_menu_accordion_action');

function cd_account_menu_accordion_action() {

    echo do_shortcode('[account-menu]');

}

add_shortcode('account-menu', 'cd_account_menu_accordion');

function cd_account_menu_accordion() {

        ob_start();

    ?>
        <?php wp_nav_menu(
            array(
                'theme_location' => 'account-menu',
                'menu_id' => 'account-menu',
                'menu_class' => apply_filters( 
                                    'chris-custom-account-menu',
                                    'accordion-menu'
                                ),
                'container_id' => 'cssmenu',
                'walker' => new CSS_Menu_Maker_Walker()
                )
            );
        ?>
        <link rel="stylesheet" id="accordion-cssmenu-styles-css" href="https://daviesguitars.io/wp-content/themes/thegem-child/css/accordion-menu.css?ver=5.2.2" type="text/css" media="all">
        <script type="text/javascript" src="https://daviesguitars.io/wp-content/themes/thegem-child/js/accordion-menu.js?ver=5.2.2"></script>
    <?php // Need to add a visit store link

        return ob_get_clean();

}

add_shortcode('guitar-menu', 'cd_guitar_menu_accordion');
function cd_guitar_menu_accordion() {

    ob_start();

    ?>
        <?php wp_nav_menu(
            array(
                'theme_location' => 'guitar-menu',
                'menu_id' => 'guitar-menu',
                'menu_class' => apply_filters( 
                                    'chris-custom-guitar-menu',
                                    'accordion-menu'
                                ),
                'container_id' => 'cssmenu',
                'walker' => new CSS_Menu_Maker_Walker()
                )
            );
        ?>
        <link rel="stylesheet" id="accordion-cssmenu-styles-css" href="https://daviesguitars.io/wp-content/themes/thegem-child/css/accordion-menu.css?ver=5.2.2" type="text/css" media="all">
        <script type="text/javascript" src="https://daviesguitars.io/wp-content/themes/thegem-child/js/accordion-menu.js?ver=5.2.2"></script>
    <?php // Need to add a visit store link

    return ob_get_clean();

}

// content for account menu
add_shortcode('account-menu-content', 'dg_account_menu_content');
function dg_account_menu_content() {

    ob_start();

    ?>
        <div class="account-menu-content">
            <div class="styled-subtitle">Account Menu</div>
            <div class="account-menu-content-menu">
                <?php 
                     $args = array(
                        'menu'            => 'account-menu',
                        'container'       => 'div',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'menu',
                        'menu_id'         => '',
                        'echo'            => true,
                        'fallback_cb'     => 'wp_page_menu',
                        'before'          => '',
                        'after'           => '',
                        'link_before'     => '',
                        'link_after'      => '',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'item_spacing'    => 'preserve',
                        'depth'           => 0,
                        'walker'          => '',
                        'theme_location'  => '',
                    );
                    wp_nav_menu( $args );
                ?>
            </div>
        </div>
    <?php

    return ob_get_clean();

}
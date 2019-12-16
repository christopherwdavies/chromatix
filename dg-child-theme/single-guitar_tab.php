<?php

$post_id 				= get_the_ID();
$pageTitle 				= get_the_title();
$content 				= get_post_field('post_content', $post_id);
$featuredImage 			= get_the_post_thumbnail();
$backgroundCover		= get_the_post_thumbnail_url();		
$tabName 				= get_post_field('tab_name', $post_id);
$downloadLinkField		= get_post_field('download_link', $post_id);
$downloadLink 			= '/wp-content/uploads/guitar-tabs/songs/'.get_post_field('download_link', $post_id);
$guitarTuning 			= get_post_field('guitar_tuning', $post_id);
$link_to_original_tab 	= get_post_field('link_to_original_tab', $post_id);
$difficulty 			= get_post_field('difficulty', $post_id);
$instruments 			= get_post_field('instruments', $post_id);
$fileFormat 			= get_post_field('file_format', $post_id);
$tabType	 			= get_post_field('tab_type', $post_id);
$hits			 		= get_post_field('post_views_hits', $post_id);
$guitar_tab_html		= get_post_field('tab_html', $post_id);
$terms 					= get_the_terms( $post->ID , 'artist' ); 


foreach ( $terms as $term ) {
	$term_link = get_term_link( $term, 'artist' );
	if( is_wp_error( $term_link ) )
	continue;
	//echo '<h4><a href="' . $term_link . '">' . $term->name . '</a></h4>';
	
	$parentCatLinkOnly 	= $term_link;
	$parentCatLink 		= '<a href="' . $term_link . '">' . $term->name . '</a>';
	$parentCatName 		= $term->name;
	$termArray 			= get_term_meta($term->term_id, 'featured_image', true);

	if (isset($termArray['guid'])) {
		$artistImage = $termArray['guid'];
	} else {
		$artistImage = '';
	}

}

//Set fallbacks if there is no data
if ($artistImage == '' || ! isset($artistImage)) {
	$artistImage = 'https://daviesguitars.io/wp-content/uploads/2019/03/Mayones-Dark.jpg';
}

get_header(); ?>


<div id="main-content" class="main-content" >
	<header class="tab-header guitar-tab" style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.8) ), url('<?php echo $artistImage; ?>');">
		<div class="container">
			<h1 class="guitar-tab-title"><?php echo $pageTitle; ?></h1>
			<p class="under-header-guitar-tab">Check out all tabs by <?php echo $parentCatLink; ?></p>
		</div>
	</header>

	<?php if($guitarTuning !== '') { ?>
		<div class="meta-tab-guitar-tabs">
			<span class="guitar-tuning">Guitar Tuning: <span style="font-weight:800;"><?php echo $guitarTuning;?></span></span>
		</div>
	<?php }?>

	<div id="woocom-subheader">
		<div class="container">
			<?php echo do_shortcode('[searchandfilter id="1960"]'); ?>
		</div>
	</div>

	<?php do_action('dg_tabs_before_content') ; ?>
	
	<div class="block-content">
		<div class="container">
			<div class="panel row">
				<div class="panel-center col-xs-12">

					<?php if (1 == 2) : ?> 
						<!-- Disabled -->
						<div class="col-md-3 col-xs-12" id="guitar-sidebar">
							<?php if ( is_active_sidebar( 'guitar-tutorials-sidebar' ) ) {
							    dynamic_sidebar('guitar-tutorials-sidebar');
							} ?>
						</div>
						<!-- Disabled -->
					<?php endif ?>

					<article id="post-<?php the_ID(); ?>">
						<div class="entry-content post-content">

							<div class="above-tab-content">
								<?php do_action('above_tab_content'); ?>
							</div>

							<div class="tab-content">

								<?php echo $content; ?>

							</div>

							<?php if ($downloadLinkField == '') { ?>
								<?php if($guitar_tab_html !== '') {
									echo '<div class="guitar-tab-html">';
									echo $guitar_tab_html;
									echo '</div>';
								} else { ?>

								<!-- Display iFrame Tab If Tab Isn't Local -->
								<div class="guitar-tab-iframe">
									<iframe src="<?php echo $link_to_original_tab; ?>" id="guitar-tab"></iframe>
								</div>

								<?php } ?>

							<?php } ?>

							<?php if($downloadLinkField !== '') { ?>
								<!-- Create playable guitar tab -->
								<?php echo do_shortcode('[guitar-tab tab="'.$downloadLinkField.'"]') ?>
							<?php } ?>
						</div>
					</article>
					<div class="rating-box">

						<?php echo do_shortcode('[ratemypost]') ;?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- #main-content -->

<?php
get_footer();

<?php

	$thegem_blog_style = isset($thegem_blog_style) ? $thegem_blog_style : 'default';

	$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());

	$params = isset($params) ? $params : array(
		'hide_author' => 0,
		'hide_comments' => 0,
		'hide_date' => 0,
		'hide_likes' => 0,
	);

	$thegem_categories = get_the_category();
	$thegem_categories_list = array();
	foreach($thegem_categories as $thegem_category) {
		$thegem_categories_list[] = '<a href="'.esc_url(get_category_link( $thegem_category->term_id )).'" title="'.esc_attr( sprintf( __( "View all posts in %s", "thegem" ), $thegem_category->name ) ).'">'.$thegem_category->cat_name.'</a>';
	}

	$thegem_classes = array();

	if(is_sticky() && !is_paged()) {
		$thegem_classes = array_merge($thegem_classes, array('sticky', 'default-background'));
	}

	$thegem_featured_content = thegem_get_post_featured_content(get_the_ID());
	if(empty($thegem_featured_content)) {
		$thegem_classes[] = 'no-image';
	}

	$thegem_classes[] = 'item-animations-inited';

	$post_id 				= get_the_ID();
	$guitarTuning 			= get_post_field('guitar_tuning', $post_id);
	$tabType	 			= get_post_field('tab_type', $post_id);
	$pageTitle 				= get_the_title();
	$imageURL 				= get_the_post_thumbnail_url();
	$artistImage			= null;

	// Create First letter place holder if no image has been added for artist
	if ($imageURL == '') {
		$firstCharacter = $pageTitle[0];
	} else {
		$firstCharacter = '';
	}


	$terms = get_the_terms( $post->ID , 'artist' ); 


	foreach ( $terms as $term ) {
		$term_link = get_term_link( $term, 'artist' );
		if( is_wp_error( $term_link ) )
		continue;
		//echo '<h4><a href="' . $term_link . '">' . $term->name . '</a></h4>';
		
		$parentCatLinkOnly = $term_link;
		$parentCatLink = '<a href="' . $term_link . '">' . $term->name . '</a>';
		$parentCatName = $term->name;

		$termArray = get_term_meta($term->term_id, 'featured_image', true);
		if (isset($termArray['guid'])) {
			$artistImage = $termArray['guid'];
		}
		


	} 


?>

<article id="post-<?php the_ID(); ?>" <?php post_class($thegem_classes); ?>>
	<?php if(get_post_format() == 'quote' && $thegem_featured_content) : ?>
		<?php echo $thegem_featured_content; ?>
	<?php else : ?>
	<?php
		if(!is_single() && is_sticky() && !is_paged()) {
			echo '<div class="sticky-label">&#xe61a;</div>';
		}
	?>

	<div class="item-post-container">
		<div class="item-post clearfix">
			<a href="<?php echo get_permalink();?>">
				<li class="guitar-tab-list">
					<div class="col-md-2 col-sm-2 col-xs-12">
						<div class="post-image guitar-tab-image list-image" style="background-image: url('<?php echo $artistImage ;?>')"><?php if ($artistImage == '') {echo $firstCharacter;} ?></div>
					</div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<div class="tab-content-container">
							<?php the_title('<h2 class="entry-title artist-guitar-tab">','</h2>'); ?>
							<div class="guitar-tab-extra-content">
								<?php

									if ( isset(get_post_custom($post_id)['rmp_vote_count'][0]) ) {

										$numberOfRatings = get_post_custom($post_id)['rmp_vote_count'][0];
										$sumOfRatings = get_post_custom($post_id)['rmp_rating_val_sum'][0];

										if ($numberOfRatings > 0) {
											$averageRating = round($sumOfRatings / $numberOfRatings,1);
											echo '<p class="rating"><strong>'.$averageRating.'/5</strong> Out Of '.$numberOfRatings.' Votes</p><br>';
										}

									}

								?>

								<div class="tab-meta-content subtitle">
									<span class="content-artist"><i class="fas fa-music"></i><?php echo $parentCatName;?></span>
									<span class=" content-tab-type"><i class="fas fa-file-alt"></i><?php echo $tabType;?></span>
									<?php if ($guitarTuning) : ?><span class="content-guitar-tuning"><i class="fas fa-microphone"></i><?php echo $guitarTuning;?></span><?php endif; ?>
								</div>

								<?php do_action('guitar_tab_extra_content'); ?>
								
							</div>
						</div>
					</div>
				</li>
			</a>
		</div>
	</div>
	<?php endif; ?>
</article>

<?php
	$post_id 				= get_the_ID();
	$pageTitle 				= get_the_title();
	$imageURL 				= get_the_post_thumbnail_url();
	$toneType				= get_post_field('tone_type', $post_id);
	$price 					= get_post_field('price', $post_id);
	$toneTypeClass			= strtolower(str_replace(' ', '-', $toneType));
	$numberOfProfiles	 	= get_post_field('number_of_profiles', $post_id);
	$videoLink				= get_post_field('video_link', $post_id);
	$audioSample 			= get_post_field('audio_sample', $post_id);
	$currency				= get_post_field('currency', $post_id);

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


	//Set up purchasing variable - Default to USD
	$currencySym = '$';

	//Euros
	if ($currency == 'EUR') { 
		$currencySym = '€';
	}
	// Pounds
	if ($currency == 'GBP') { 
		$currencySym = '£';
	}
	// Build string
	$currencyString = $currencySym.number_format($price,2);

	// Set free or paid response
	if ($price[0] == '' || $price[0] == 0) {
		$freeOrPaid = 'Free';
	} else {
		$freeOrPaid = $currencyString;
	}

	// Set the tone type image
	if ($toneType == 'VST Pedal') {
		// Vst Pedal
		$toneIcon = '/wp-content/uploads/2019/04/VST-Pedal-SVG.svg';
	} elseif ($toneType == 'VST Amp') {
		// VST Amp
		$toneIcon = '/wp-content/uploads/2019/04/VST-Amp-SVG.svg';
	} elseif ($toneType == 'Impulse Loader' || $toneType == 'Impulse Response') {
		// Cab Impulse
		$toneIcon = '/wp-content/uploads/2019/04/Cabinet-Impulse-SVG.svg';
	} elseif ($toneType == 'Kemper Profiler') {
		// Kemper
		$toneIcon = '/wp-content/uploads/2019/04/Kemper-Profiler-SVG.svg';
	}

	// Create First letter place holder if no image has been added for artist
	if ($imageURL == '') {
		$firstCharacter = $pageTitle[0];
	} else {
		$firstCharacter = '';
	}

	// Audio Player
	if ($audioSample) {
		$attr = array(
			'src'      => $audioSample,
			'loop'     => '',
			'autoplay' => '',
			'preload'  => 'none'
		);

		$audioPlayer =
		'<div class="audio-sample module-container" style="margin-top: -30px; background: white;">'.
			wp_audio_shortcode( $attr ).
		'</div>';
	} else {
		$audioPlayer = '';
	}

	// Gather parent taxonomy data
	$terms = get_the_terms( $post->ID , 'developer' ); 
	foreach ( $terms as $term ) {
		$term_link = get_term_link( $term, 'developer' );
		if( is_wp_error( $term_link ) )
		continue;
		$parentCatLinkOnly = $term_link;
		$parentCatLink = '<a href="' . $term_link . '">' . $term->name . '</a>';
		$parentCatName = $term->name;
		$termArray = get_term_meta($term->term_id, 'featured_image', true);
		$artistImage = $termArray['guid'];
	} 
?>
<?php if($templateClass == 'list-template') { ?>
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
							<div class="col-xs-12 col-sm-2 col-md-2 tone-image">
								<div class="post-image guitar-tab-image list-image" style="background-image: url('<?php echo $imageURL ;?>')"><?php if ($imageURL == '') {echo $firstCharacter;} ?></div>
							</div>
							<div class="tab-content-container col-sm-8 col-md-8 col-xs-12">
								<?php the_title('<h2 class="entry-title artist-guitar-tab">','</h2>'); ?>
								<div class="meta-badges">
									<div class="meta-badge free-paid"><?php echo $freeOrPaid; ?></div>
									<div class="meta-badge tonetype"><?php echo $toneType; ?></div>
									<?php if($numberOfProfiles) { ?>
										<div class="meta-badge tonetype"><?php echo $numberOfProfiles; ?> Tones</div>
									<?php } ?> 
									<div class="guitar-tab-extra-content">
										<?php
											$numberOfRatings = get_post_custom($post_id)['rmp_vote_count'][0];
											$sumOfRatings = get_post_custom($post_id)['rmp_rating_val_sum'][0];
											$averageRating = round($sumOfRatings / $numberOfRatings,2);
											if ($numberOfRatings > 0) {
												echo '<p class="rating"><strong>'.$averageRating.'/5</strong> Out Of '.$numberOfRatings.' Votes</p><br>';
											} else {
												// echo '<p>No ratings yet</p><br>';
											}
										?>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12 tone-icon <?php echo $toneTypeClass ?>" style="background-image: url('<?php echo $toneIcon ?>')">
								<span class="tone-type-phrase <?php echo $toneTypeClass ?>"><span><?php echo $toneType ;?></span></span>
							</div>
						</li>
					</a>
					<?php echo $audioPlayer ?>
				</div>
			</div>
			<?php endif; ?>
		</article>
<?php } elseif ($templateClass == 'grid-template') { ?>
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
							<div class="col-xs-12 col-sm-12 col-md-12 tone-image">
								<div class="post-image guitar-tab-image list-image" style="background-image: url('<?php echo $imageURL ;?>')">
									<?php if ($imageURL == '') {echo $firstCharacter;} ?>
								</div>
								<div class="col-xs-12 mini-tone-icon-container">
									<div class="mini-tone-icon tone-icon <?php echo $toneTypeClass ?>" style="background-image: url('<?php echo $toneIcon ?>')">
										<span class="tone-type-phrase <?php echo $toneTypeClass ?>">
											<span><?php echo $toneType ;?></span>
										</span>
									</div>
								</div>
							</div>
							<div class="tab-content-container col-sm-12 col-md-12 col-xs-12">
								<?php the_title('<h2 class="entry-title artist-guitar-tab">','</h2>'); ?>
								<div class="meta-badges">
									<div class="meta-badge free-paid"><?php echo $freeOrPaid; ?></div>
									<div class="meta-badge tonetype"><?php echo $toneType; ?></div>
									<?php if($numberOfProfiles) { ?>
										<div class="meta-badge tonetype"><?php echo $numberOfProfiles; ?> Tones</div>
									<?php } ?> 
									<div class="guitar-tab-extra-content">
										<?php
											$numberOfRatings = get_post_custom($post_id)['rmp_vote_count'][0];
											$sumOfRatings = get_post_custom($post_id)['rmp_rating_val_sum'][0];
											$averageRating = round($sumOfRatings / $numberOfRatings,2);
											if ($numberOfRatings > 0) {
												echo '<p class="rating"><strong>'.$averageRating.'/5</strong> Out Of '.$numberOfRatings.' Votes</p><br>';
											} else {
												// echo '<p>No ratings yet</p><br>';
											}
										?>
									</div>
								</div>
							</div>
<!-- 							<div class="col-md-12 col-sm-12 col-xs-12 tone-icon <?php echo $toneTypeClass ?>" style="background-image: url('<?php echo $toneIcon ?>')">
								<span class="tone-type-phrase <?php echo $toneTypeClass ?>"><span><?php echo $toneType ;?></span></span>
							</div> -->
						</li>
					</a>
					<?php echo $audioPlayer ?>
				</div>
			</div>
			<?php endif; ?>
		</article>
<?php }
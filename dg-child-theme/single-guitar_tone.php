<?php

//Content
$pageTitle 				= get_the_title();
$backgroundCover		= get_the_post_thumbnail_url();	
$content 				= get_post_field('post_content', $post_id);
$videoLink				= get_post_field('video_link', $post_id);
$videoLink2				= get_post_field('video_link_2', $post_id);

//Meta
$downloadLink 			= get_post_field('download_link', $post_id);	
$linkToOriginal			= get_post_field('link_to_original_source', $post_id);
$toneType				= get_post_field('tone_type', $post_id);
$purchaseLink			= get_post_field('purchase_link', $post_id);
$secondDescription 		= get_post_field('second_column_description', $post_id);
$price 					= get_post_field('price', $post_id);
$subtitle 				= get_post_field('subtitle', $post_id);
$audioSample 			= get_post_field('audio_sample', $post_id);
$toneName	 			= get_post_field('tone_name', $post_id);
$numberOfProfiles	 	= get_post_field('number_of_profiles', $post_id);
$hits			 		= get_post_field('post_views_hits', $post_id);
$currency				= get_post_field('currency', $post_id);

//Set up purchasing variable - Default to USD
$currencySym = '$';
$currencyLet = ' USD';

//Euros
if ($currency == 'EUR') { 
	$currencySym = '€';
	$currencyLet = '';
}
// Pounds
if ($currency == 'GBP') { 
	$currencySym = '£';
	$currencyLet = '';
}

// Build string
$currencyString = $currencySym.$price.$currencyLet;

// Check if free or paid
if($price[0] == '' || $price[0] == 0) {
	$freeOrPaid = 'Free';
} else {
	$freeOrPaid = $currencyString;
}


$terms = get_the_terms( $post->ID , 'developer' ); 

foreach ( $terms as $term ) {
	$term_link = get_term_link( $term, 'developer' );
	if( is_wp_error( $term_link ) )
	continue;
	
	$parentCatLinkOnly = $term_link;
	$parentCatLink = '<a href="' . $term_link . '">' . $term->name . '</a>';
	$parentCatName = $term->name;
	$termMeta = get_term_meta($term->term_id);
	$imageID = $termMeta['featured_image'][0];
	$featuredImage = wp_get_attachment_url( $imageID ); //Original Image URL
} 

if ($backgroundCover !== '') {
	$featuredImage = $backgroundCover;
}

/*
Create HTML Blocks
*/
if ($numberOfProfiles) {
	$tonesNoAttr = '<tr><td class="attribute">Number of Tones</td><td class="attr-result">'.$numberOfProfiles.'</td></tr>';
} else {
	$tonesNoAttr = '';
}
//Specs table
$toneSpecsTable = 
	'<table class="specs-table module-container">
		<tbody>
			<tr class="heading"><td colspan="2">Attributes</td></tr>
			<tr><td class="attribute">Tone Type</td>
				<td class="attr-result">'.$toneType.'</td></tr>
			<tr><td class="attribute">Price (MSRP)</td>
				<td class="attr-result">'.$freeOrPaid.'</td></tr>
			<tr><td class="attribute">Developer</td>
				<td class="attr-result">'.$parentCatLink.'</td></tr>'.$tonesNoAttr.'
		</tbody>
	</table>';

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

// Votes Likes
	$numberOfRatings = get_post_custom($post_id)['rmp_vote_count'][0];
	$sumOfRatings = get_post_custom($post_id)['rmp_rating_val_sum'][0];
	$averageRating = round($sumOfRatings / $numberOfRatings,2);

	if ($numberOfRatings > 0) {
		$ratingsResponse =  '<p class="rating"><strong>'.$averageRating.'/5</strong> Out Of '.$numberOfRatings.' Votes</p>';
	} else {
		$ratingsResponse = '<p>Be the first to <a href="#rate-this-tone">rate this tone</a></p>';
	}

//Video Embeds
	$videoEmbed1 = '
	<iframe class="module-container" width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.$videoLink.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

	$videoEmbed2 = '
	<iframe class="module-container" width="560" height="315" src="https://www.youtube-nocookie.com/embed/'.$videoLink2.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

get_header(); ?>

<script>
    jQuery( document ).ready(function() {
        console.log( "document loaded" );
        var text = jQuery('.custom-post-text');
        var textHeight = jQuery(text).outerHeight();
        console.log(textHeight);

        text.append()
    });
</script>

<div id="main-content" class="main-content" >
	<header class="tab-header guitar-tab" style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.8) ), url('<?php echo $featuredImage; ?>');">
		<div class="container">
			<h1 class="guitar-tab-title"><?php echo $pageTitle; ?></h1>
			<p class="under-header-guitar-tab">Check out all tones by <?php echo $parentCatLink; ?></p>
		</div>
	</header>

	<div class="block-content">
		<div class="container">
			<div class="custom-post-search-block col-lg-12 col-md-12 col-sm-12">
				<?php echo do_shortcode('[searchandfilter id="7009"]'); ?>
			</div>

			<div class="panel row" style="padding:inherit;">
				<div class="panel-center col-xs-12"><!--have removed "white-box-container" class -->
					<article id="post-<?php the_ID(); ?>">
						<div class="entry-content post-content">
							<div class="custom-post-content">

								<!-- Content Header -->
								<div class="row heading-section" style="margin-bottom: 50px;">
									<div class="col-md-3">
										<div class="tone-image-circle" style="background-image: url('<?php echo $featuredImage ?>')"></div>
									</div>
									<div class="col-md-9">
										<h2 class="tone-subtitle"><?php echo $pageTitle; ?></h2>
										<div class="votes-likes">
											<?php echo $ratingsResponse . do_shortcode("[follow-buttons]"); ?>
										</div>								
									</div>
								</div>

								<!-- Start Body -->
								<div class="row body-section">
									
									<!-- Two column -->
									<?php if ( strlen($content) > 0 ) { //less than 2000 characters ?>

										<div class="col-md-6 custom-post-text">
											<?php echo $content; ?>
										</div>
										<span id="read-more-button" class="hidden"></span>

										<div class="col-md-6">
											<?php echo $toneSpecsTable ?>
											<?php echo $audioPlayer ?>

											<?php if($videoLink !== '') { 
												echo $videoEmbed1;
											}?>

											<?php if($videoLink2 !== '') {
												echo $videoEmbed2;
											}?>

											<div class="secondary-description">
												<?php echo $secondDescription ?>
											</div>

										</div>

									<?php } else { //Greater than 2000 characters ?>


										<!-- One column -->
										<div class="col-md-6">
											<?php echo $toneSpecsTable ?>
											<?php echo $audioPlayer ?>
										</div>
										<div class="col-md-6">
											<?php if($videoLink !== '') {
												echo $videoEmbed1;
											} ?>
										</div>
										<div class="col-md-12 custom-post-text" style="margin-top: 50px;">
											<?php echo $content; ?>
											<div class="secondary-description">
												<?php echo $secondDescription ?>
											</div>
										</div>
										<span id="read-more-button" class="hidden"></span>

									<?php } ?>

								</div>
								<!-- End body -->
							</div>

							<!-- Begin downloads and Links -->
							<?php if($downloadLink !== '') { ?>

								<div class="tab-button-wrapper">
									<form method="get" action="<?php echo $downloadLink;?>" class="download-form">
									   <button class="download-guitar-tab gem-button gem-button-size-large gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" type="submit">Get This Tone</button>
									</form>
								</div>
								<h2 class="download-description"  style="margin: 0px;">Use this button to download this <?php echo ' guitar tone by <a href="'.$linkToOriginal.'" target="_blank">'. $parentCatName.'</a>'; ?></h2>
								<p class="subfont">This is a free tone provided by <?php echo $parentCatName; ?>.</p>
							<?php } ?>

							<?php if($purchaseLink !== '') { ?>
							
								<div class="tab-button-wrapper">
									   <a href="<?php echo $purchaseLink;?>" class="download-guitar-tab gem-button gem-button-size-large gem-button-style-flat gem-button-text-weight-normal gem-button-icon-position-left" target="_blank">Get This Tone</a>
								</div>
								<h2 class="download-description" style="margin: 0px;">Use this button to get this guitar tone by <?php echo '<a href="' .$linkToOriginal.'" target="_blank">'.$parentCatName.'</a>';?></h2>
								<p class="subfont">You will be redirected to one of our partner sites.</p>
							<?php } ?>

						</div>
					</article>
					<div class="rating-box" id="rate-this-tone">
						<?php echo do_shortcode('[ratemypost]') ;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- #main-content -->

<?php
get_footer();

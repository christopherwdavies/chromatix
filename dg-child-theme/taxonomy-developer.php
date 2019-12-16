<?php
/**
* A Simple Category Template
*/
 
get_header(); ?> 

<?php

	if (current_user_can('administrator')) { //for printing out info i want
		echo '<div class="admin-object-info">';	

			$term = get_queried_object()->term_id;
			$termMeta = get_term_meta($term);
			// print_r($termMeta);
			// extract($termMeta);

			$facebookLink = $termMeta['facebook'][0];
			$websiteLink = $termMeta['website'][0];
			$youtubeLink = $termMeta['youtube'][0];
			$twitterLink = $termMeta['twitter'][0];
			$instagramLink = $termMeta['instagram'][0];
			$imageID = $termMeta['featured_image'][0];
			// $featuredImage = wp_get_attachment_url( $imageID ); //Original Image URL
			$featuredImage = wp_get_attachment_image_src( $imageID, 'medium')[0]; // Medium image (300x300)

		echo '</div>';
	}
?>


<div id="main-content" class="main-content">
	<header class="archive-header custom-post">

			<h1 class="archive-title"><?php echo single_cat_title( '', false); ?></h1>

			<div class="container archive-meta">
				<div class="col-md-4">
					<?php if ( $featuredImage ) { ?>
					<div class="featured-image-custom-taxonomy" style="background-image:url('<?php echo $featuredImage; ?>')"></div>
				<?php } ?>
				</div>
				<div class="col-md-8">
					<div class=""><?php echo category_description(); ?>
					<div class="socials-badge">
						<?php if($websiteLink) {?><a class="social-anchor website" href="<?php echo $websiteLink; ?>" target="_blank"><span class="text">Website</span></a><?php } ?>
						<?php if($facebookLink) {?><a class="social-anchor facebook" href="<?php echo $facebookLink; ?>" target="_blank"><span class="text">Facebook</span></a><?php } ?>
						<?php if($instagramLink) {?><a class="social-anchor instagram" href="<?php echo $instagramLink	; ?>" target="_blank"><span class="text">Instagram</span></a><?php } ?>
						<?php if($twitterLink) {?><a class="social-anchor twitter" href="<?php echo $twitterLink; ?>" target="_blank"><span class="text">Twitter</span></a><?php } ?>
						<?php if($youtubeLink) {?><a class="social-anchor youtube" href="<?php echo $youtubeLink; ?>" target="_blank"><span class="text">Youtube</span></a><?php } ?>
					</div>
				</div>
			</div>

	</header>



	<div class="block-content">
		<div class="container">

			<div class="custom-post-search-block col-lg-12 col-md-12 col-sm-12">
				<?php echo do_shortcode('[searchandfilter id="7009"]'); ?>
			</div>

		<!-- Top section -->
			<?php 
			// Check if there are any posts to display
			if ( have_posts() ) : ?>
			 

			 

		<!-- Blog Posts -->
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="artist-tabs">

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
				<?php if ($paged == 0) { $paged = 1; } ?>


				<div class="found-results">
					<div class="sf-results-found-no">
						<div class="nav-previous">
							<?php previous_posts_link( '<span class="previous-icon"></span>' ); ?>
						</div>
						<span>Found <?php echo $wp_query->found_posts; ?> Results</span>
					</div>
					<div class="sf-results-page-no">
						<span>Page <?php echo $paged; ?> of <?php echo $wp_query->max_num_pages; ?></span>
						<div class="nav-next">
							<?php next_posts_link( '<span class="next-icon"></span>', $query->max_num_pages ); ?>
						</div>
					</div>
				</div>

				<div class="guitar-tabs-container item-animation-move-up">
					<?php
					 $numberCounter = 0;

					// The Loop
					while ( have_posts() ) : the_post(); ?>
						<?php $numberCounter = $numberCounter + 1; ?>
							<div class="guitar-tab-container-wrapper">
								<?php get_template_part( 'content-loop-guitar-tones', 'content-loop-guitar-tones' ); ?>
							</div>
					<?php endwhile; 
						?>
				</div>

				<div class="found-results">
					<div class="sf-results-found-no">
						<div class="nav-previous">
							<?php previous_posts_link( '<span class="previous-icon"></span>' ); ?>
						</div>
						<span>Found <?php echo $wp_query->found_posts; ?> Results</span>
					</div>
					<div class="sf-results-page-no">
						<span>Page <?php echo $paged; ?> of <?php echo $wp_query->max_num_pages; ?></span>
						<div class="nav-next">
							<?php next_posts_link( '<span class="next-icon"></span>', $query->max_num_pages ); ?>
						</div>
					</div>
				</div>

				<?php
				 
				else: ?>
				<p>Sorry, no posts matched your criteria.</p>
				 
				<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>

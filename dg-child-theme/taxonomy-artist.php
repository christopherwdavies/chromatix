<?php
/**
* A Simple Category Template
*/
get_header(); ?> 

<div id="main-content" class="main-content">
	<header class="archive-header custom-post">

			<h1 class="archive-title"><?php echo single_cat_title( '', false); ?> Guitar Tabs</h1>	

			<div class="container">
				<div class="archive-meta"><?php echo category_description(); ?>
			</div>

	</header>



	<div class="block-content">
		<div class="container">

			<div class="custom-post-search-block col-lg-12 col-md-12 col-sm-12">
				<?php echo do_shortcode('[searchandfilter id="1960"]'); ?>
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
							<?php next_posts_link( '<span class="next-icon"></span>', $wp_query->max_num_pages ); ?>
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
								<?php get_template_part( 'content-loop-guitar-tabs', 'content-loop-guitar-tabs' ); ?>
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
							<?php next_posts_link( '<span class="next-icon"></span>', $wp_query->max_num_pages ); ?>
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

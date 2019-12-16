<?php /* Template Name: Guitar Tutorials Sidebar */ ?>
<?php

get_header(); ?>

<div id="main-content" class="main-content">
	<style>
		@media (max-width: 480px) {
			article .vc_column_container > .vc_column-inner {
				padding: 0px !important;
			}			
		}
	</style>

	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'page-guitar-tutorials' );
		endwhile;
	?>

</div><!-- #main-content -->

<?php
get_footer();

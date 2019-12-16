<?php /* Template Name: User Account Area */ ?>
<?php

if ( is_user_logged_in() ) {
	$class = 'user-logged-in';
} else {
	$class = 'inactive';
}

get_header(); ?>


<style>	
	/*Stable Styles*/
	#account-content.user-logged-in {
		width: calc(100% - 300px);
		float: right;
	}
	#account-sidebar {
		width: 300px;
		float: left;
		border-right: solid 2px #262626;
	}
	/*Mobile Styles*/
	@media (max-width: 1200px) {
		#account-content.user-logged-in {
			width: 100%;
			float: right;
		}
		#account-sidebar {
			display:none;
		}
	}
	.block-content.account-content {
	    min-height: 500px;
/*	    background: #f7f7f7;
*/	}
</style>

<div id="main-content" class="main-content">

	<?php if (is_user_logged_in() && 1 == 2): ?>
		<div id="account-sidebar">
			<?php echo do_shortcode('[account-menu-content]'); ?>
		</div>
	<?php endif; ?>


	<div id="account-content" class="">
		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'page-user-template' );
			endwhile;
		?>
	</div>

</div><!-- #main-content -->

<?php wp_enqueue_media(); ?>	
<?php wp_enqueue_script('update-profile-picture'); ?>
<?php get_footer();
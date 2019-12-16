<?php

	/*
	**
	**	The Gem Stuff
	**
	*/
		/**/
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
		/**/
	/*
	**
	**	End The Gem Stuff
	**
	*/

	$pageTitle 				= get_the_title();
	$description	 		= get_post_field('description', $post_id);
	$couponCode	 			= get_post_field('coupon_code', $post_id);
	$logo	 				= get_post_field('logo', $post_id);
	$company_name	 		= get_post_field('company_name', $post_id);
	$website	 			= get_post_field('website', $post_id);
	$terms	 				= get_post_field('terms', $post_id);

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

	<div class="item-post-container promotional-coupon">
		<div class="item-post clearfix">
			<a href="<?php echo $website;?>" target="_blank">
				<li class="guitar-tab-list">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<img src="<?php echo $logo['guid']; ?>" class="company-logo">
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="tab-content-container">
							<div class="styled-subtitle" style="text-align:center;"><?php echo $pageTitle; ?></div>
							<div class="merchant-coupon-content">
								<p style="text-align:center;"><?php echo $description ?></p>
								<?php if($couponCode) : ?> 
									<strong class="merchant-coupon-code"><?php echo $couponCode ?></strong>
								<?php endif ?>
								<strong>Terms:</strong>
								<p><?php echo $terms ?></p>
								<?php ?>
							</div>
						</div>
					</div>
				</li>
			</a>
		</div>
	</div>
	<?php endif; ?>
</article>

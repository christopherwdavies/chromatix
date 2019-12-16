<?php
/**
 * Search & Filter Pro 
 *
 * Sample Results Template
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 * 
 * Note: these templates are not full page templates, rather 
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think 
 * of it as a template part
 * 
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs 
 * and using template tags - 
 * 
 * http://codex.wordpress.org/Template_Tags
 *
 */

//Show search query
global $searchandfilter;
$sf_current_query = $searchandfilter->get(1960)->current_query();
$searchQuery = $sf_current_query->get_search_term();

if ( $query->have_posts() ) {
	$nuResults = $query->found_posts;
} else {
	$nuResults = '';
}

?>
<h3 class="query-string">Showing <?php echo $nuResults; ?> Results for "<?php echo $searchQuery; ?>"</h3>

<?php

if ( $query->have_posts() )
{
	?>
	
<!-- pagination -->
<?php chris_pagination($query); ?>

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
	
	<?php
	while ($query->have_posts())
	{
		$query->the_post();
		
		?>
		<div class="custom-post-container-wrapper">
			<?php get_template_part( 'content-loop-guitar-tabs', 'content-loop-guitar-tabs' ); ?>
		</div>
		<?php
	}
	?>

<!-- pagination -->
<?php chris_pagination($query); ?>

	<?php
}

else
	{
		echo "<h2 class='' style='text-align:center;'>Sorry Mate, Couldn't Find Anything!</h2>";
	}
?>
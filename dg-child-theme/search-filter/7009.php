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
$sf_current_query = $searchandfilter->get(7009)->current_query();
$searchQuery = $sf_current_query->get_search_term();

if ( $query->have_posts() ) {
	$nuResults = $query->found_posts;
} else {
	$nuResults = '';
}


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


?>
<h3 class="query-string">Showing <?php echo $nuResults; ?> Results for "<?php echo $searchQuery; ?>"</h3>

<?php if($templateClass == 'grid-template') {echo '<div class="row">';} //End row ?>
<?php $counter = 1; //counter for row breaks


if ( $query->have_posts() )
{
	?>


<a href="?template=grid" class="listing-template-icon"><i class="fas fa-th"></i></a>
<a href="?template=list" class="listing-template-icon"><i class="fas fa-th-list"></i></a>

	
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
        if ($templateClass == 'grid-template') { 
	        ?>
		    <div class="wpb_column vc_column_container vc_col-md-4 vc_col-sm-12">
				<div class="vc_column-inner clearfix">
		        	<div class="guitar-tab-container-wrapper <?php echo $templateClass; ?>">
		        		<?php get_template_part( 'content-loop-guitar-tones', 'content-loop-guitar-tones' ); ?>
		        	</div>
		    	</div>
		    </div>

		   	<?php
	        if ($counter % 3 == 0) {
				echo '</div><div class="row">';
        	}
			$counter++;

		    
        } elseif ($templateClass == 'list-template') {
        	?>
    		<div class="guitar-tab-container-wrapper <?php echo $templateClass; ?>">
        		<?php get_template_part( 'content-loop-guitar-tones', 'content-loop-guitar-tones' ); ?>
        	</div> 
        	<?php


		}
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
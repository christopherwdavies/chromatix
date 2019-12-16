<?php
$home_url = home_url();
$active_class = ' class="active"'
?>

<div class="dokan-dash-sidebar override-chris global-account-menu">
	<?php do_action('cd_override_dokan_menu'); ?>
    <?php // echo dokan_dashboard_nav( $active_menu ); ?>
</div>
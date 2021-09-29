<?php
use WprAddons\Admin\Includes\WPR_Conditions_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$conditions = json_decode( get_option('wpr_footer_conditions'), true );
$template_slug = WPR_Conditions_Manager::header_footer_display_conditions($conditions);

if ( '' == $template_slug ) {
	return;
}

// Render Royal Addons Header
Utilities::render_elementor_template($template_slug);

wp_footer();

// Ashe theme compatibility
if ( 'ashe' === get_option('stylesheet') ) {
	echo '</div>'; // .page-content
	echo '</div>'; // #page-wrap
}

?>

</body>
</html> 
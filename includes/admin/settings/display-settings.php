<?php
/**
 * Admin Options Page
 *
 * @package     RCP
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.5.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @since 2.5.0
 * @return void
 */
function rcp_options_page() {
	$active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], rcp_get_settings_tabs() ) ? $_GET['tab'] : 'general';

	ob_start();
	?>
	<div class="wrap">
		<h1 class="nav-tab-wrapper">
			<?php
			foreach( rcp_get_settings_tabs() as $tab_id => $tab_name ) {

				$tab_url = add_query_arg( array(
					'settings-updated' => false,
					'tab' => $tab_id
				) );

				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );
				echo '</a>';
			}
			?>
		</h1>
		<div id="tab_container">
			<form method="post" action="options.php">
				<table class="form-table">
				<?php
				settings_fields( 'rcp_settings' );
				do_settings_fields( 'rcp_settings_' . $active_tab, 'rcp_settings_' . $active_tab );
				?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div><!-- #tab_container-->
	</div><!-- .wrap -->
	<?php
	echo ob_get_clean();
}

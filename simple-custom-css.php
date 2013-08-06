<?php
/*
	Plugin Name: Simple Custom CSS
	Plugin URI: #
	Description: The simple, solid way to add custom CSS to your WordPress website. Simple Custom CSS allows you to add your own styles or override the default CSS of a plugin or theme.</p>

	Author: John Regan
	Author URI: http://johnregan3.com
	Version: 1.0
 */


/**
 * Print direct link to Custom CSS admin page
 *
 * Fetches array of links generated by WP Plugin admin page ( Deactivate | Edit )
 * and inserts a link to the Custom CSS admin page
 *
 * @since 1.0
 * @param array Array of links generated by WP in Plugin Admin page.
 */

function sccss_settings_link( $links ) {
	$settings_page = '<a href="' . admin_url('themes.php?page=simple-custom-css.php' ) .'">Settings</a>';
	array_unshift( $links, $settings_page );
	return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter( "plugin_action_links_$plugin", 'sccss_settings_link' );


/**
 * Print custom CSS to <HEAD>
 *
 * Fetches content of scss-settings and pulls out sccss-content field.
 * Then, echoes the sccss-content field.
 *
 * @since 1.0
 */
function sccss_style() {
?>
	<style type="text/css">
		<?php
			$options = get_option( 'sccss_settings' );
			$content = isset( $options['sccss-content'] ) ? $options['sccss-content'] : '';
			echo esc_html( $content );
		?>
	</style>
<?php
}

//Don't load in WP Admin
if ( ! is_admin() )
	add_action('wp_print_scripts','sccss_style', 99);


/**
 * Register text domain
 *
 * @since 1.0
 */
function sccss_textdomain() {
	load_plugin_textdomain('sccss');
}

add_action('init', 'sccss_textdomain');


/**
 * Register "Custom CSS" submenu in "Appearance" Admin Menu
 *
 * @since 1.0
 */
function sccss_register_submenu_page() {
	add_theme_page( __( 'Simple Custom CSS', 'sccss' ), __( 'Custom CSS', 'sccss' ), 'edit_themes', basename(__FILE__), 'sccss_render_submenu_page' );
}

add_action( 'admin_menu', 'sccss_register_submenu_page' );


/**
 * Register settings
 *
 * @since 1.0
 */
function sccss_register_settings() {
	register_setting('sccss_settings_group', 'sccss_settings');
}

add_action('admin_init', 'sccss_register_settings');


/**
 * Render Admin Menu page
 *
 * @since 1.0
 */
function sccss_render_submenu_page() {

	$options = get_option( 'sccss_settings' );
	$content = isset( $options['sccss-content'] ) ? $options['sccss-content'] : '';

	if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div id="message" class="updated"><p><?php _e( 'Custom CSS updated successfully.' ); ?></p></div>
	<?php endif; ?>
 
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Simple Custom CSS', 'sccss' ); ?></h2>
		<p>Simple Custom CSS allows you to add your own styles or override the default CSS of a plugin or theme.</p>

		<div class="fileedit-sub">
			<div class="alignright"></div>
			<br class="clear" />
		</div>
		<div id="templateside">
			<h3>Instructions</h3>
			<ol>
				<li>Enter your custom CSS in the the texarea to the right.</li>
				<li>Click "Update Custom CSS"</li>
				<li>Enjoy your new CSS styles!</li>
			</ol>
			<br />
			<br />
			<h3>Help</h3>
			<p><a href="<?php echo esc_url('#'); ?>" ><?php _e('View Simple Custom CSS Wiki', 'sccss'); ?></a></p>
		</div>

		<form name="sccss-form" id="template" action="options.php" method="post" enctype="multipart/form-data">

			<?php settings_fields('sccss_settings_group'); ?>


			<div>
				<textarea cols="70" rows="30" name="sccss_settings[sccss-content]" id="sccss_settings[sccss-content]" ><?php echo $content; ?></textarea>
				<input type="hidden" name="action" value="update" />
			</div>

			<div>
				<?php submit_button( __( 'Update Custom CSS' ), 'primary', 'submit', true ); ?>
			</div>
		</form>
		<br class="clear" />
	</div>

<?php }

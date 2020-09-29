<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://joe.szalai.org
 * @since             1.0.0
 * @package           Exopite_Seo_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Exopite SEO Core
 * Plugin URI:        https://joe.szalai.org
 * Description:       Core SEO optimizations with cookie notice, inline CSS and more.
 * Version:           20200929
 * Author:            Joe Szalai
 * Author URI:        https://joe.szalai.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       exopite-seo-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EXOPITE_SEO_CORE_VERSION', '20200929' );
define( 'EXOPITE_SEO_PLUGIN_NAME', 'exopite-seo-core' );
define( 'EXOPITE_SEO_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-exopite-seo-core-activator.php
 */
function activate_exopite_seo_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exopite-seo-core-activator.php';
	Exopite_Seo_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-exopite-seo-core-deactivator.php
 */
function deactivate_exopite_seo_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exopite-seo-core-deactivator.php';
	Exopite_Seo_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_exopite_seo_core' );
register_deactivation_hook( __FILE__, 'deactivate_exopite_seo_core' );

/**
 * Update
 */
if ( is_admin() ) {

    /**
     * A custom update checker for WordPress plugins.
     *
     * Useful if you don't want to host your project
     * in the official WP repository, but would still like it to support automatic updates.
     * Despite the name, it also works with themes.
     *
     * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
     * @link https://github.com/YahnisElsts/plugin-update-checker
     * @link https://github.com/YahnisElsts/wp-update-server
     */
    if( ! class_exists( 'Puc_v4_Factory' ) ) {

        require_once join( DIRECTORY_SEPARATOR, array( EXOPITE_SEO_PATH, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

    }

    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://update.joeszalai.org/?action=get_metadata&slug=' . EXOPITE_SEO_PLUGIN_NAME, //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        EXOPITE_SEO_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
    );

	/**
	 * add plugin upgrade notification
	 * https://andidittrich.de/2015/05/howto-upgrade-notice-for-wordpress-plugins.html
	 */
	add_action( 'in_plugin_update_message-' . EXOPITE_SEO_PLUGIN_NAME . '/' . EXOPITE_SEO_PLUGIN_NAME .'.php', 'exopite_seo_core_show_upgrade_notification', 10, 2 );
	function exopite_seo_core_show_upgrade_notification( $current_plugin_metadata, $new_plugin_metadata ) {

		/**
		 * Check "upgrade_notice" in readme.txt.
		 *
		 * Eg.:
		 * == Upgrade Notice ==
		 * = 20180624 = <- new version
		 * Notice		<- message
		 *
		 */
		if ( isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) ) > 0 ) {

			// Display "upgrade_notice".
			echo sprintf( '<span style="background-color:#d54e21;padding:10px;color:#f9f9f9;margin-top:10px;display:block;"><strong>%1$s: </strong>%2$s</span>', esc_attr( 'Important Upgrade Notice', 'exopite-multifilter' ), esc_html( rtrim( $new_plugin_metadata->upgrade_notice ) ) );

		}
    }

    // $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    //     'https://github.com/JoeSz/Exopite-SEO-Core', //Metadata URL.
    //     __FILE__, //Full path to the main plugin file.
    //     EXOPITE_SEO_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
    // );

}
// End Update

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-exopite-seo-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_exopite_seo_core() {

	$plugin = new Exopite_Seo_Core();
	$plugin->run();

}
run_exopite_seo_core();

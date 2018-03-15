<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/includes
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Seo_Core_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'exopite-seo-core',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/admin
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Seo_Core_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Seo_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Seo_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-seo-core-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Seo_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Seo_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-seo-core-admin.js', array( 'jquery' ), $this->version, false );

	}


    public function create_menu() {

        if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        $config = array(

            'type'              => 'menu',                          // Required, menu or metabox
            'id'                => $this->plugin_name,              // Required, meta box id, unique per page, to save: get_option( id )
            'menu'              => 'plugins.php',                   // Required, sub page to your options page
            'submenu'           => true,                            // Required for submenu
            'title'             => 'SEO Core',            //The name of this page
            'capability'        => 'manage_options',                // The capability needed to view the page
            'plugin_basename'   =>  plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
            'tabbed'            => true,

        );

        $fields[0] = array(
            'name'   => 'general',
            'title'  => 'General',
            'icon'   => 'dashicons-admin-generic',
            'fields' => array(

                array(
                    'id'      => 'activate_gzip',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Activate gzip', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'remove_json_from_header',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Remove JSON links from header', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'deactivate_attachment_pages',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Deactivate attachment pages', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'   => 'If you change this, please <a href="/wp-admin/options-permalink.php" target="_blank">save permalinks</a>',
                ),


                array(
                    'id'      => 'limit_revisions',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Limit revisions', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'revision_to_keep',
                    'type'    => 'range',
                    'title'   => esc_html__( 'Revision to keep', 'exopite-seo-core' ),
                    'default' => '20',
                    'min'     => '0',
                    'max'     => '100',
                    'dependency' => array( 'limit_revisions', '==', 'true' ),
                    'info'    =>  ' ' . 'For unlimited, set to 100.',
                ),

                array(
                    'id'      => 'noidex_archives_search',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Add noidex on archives, search and 404', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'auto_image_alt',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Automatically add image infos', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'    => esc_html__( 'Automatically Set the WordPress Image Title, Alt-Text & Other Meta' ),
                ),

                array(
                    'id'      => 'deactivate_comments',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Deactivate comments and pingbacks', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'deactivate_feed',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Deactivate feed', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'activate_google_analytics',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Activate Google Analytics', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'    => '<span class="info-links ga-links"><a href="https://analytics.google.com/analytics/web/?hl=de" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Open Google Analytics</a><br><a href="https://www.google.com/webmasters/tools/home?hl=de&pli=1" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Open Google Search Console</a></span>',
                ),

                array(
                    'id'     => 'google_analytics_id',
                    'type'   => 'text',
                    'title'  => esc_html__( 'Google Analytics ID', 'exopite-seo-core' ),
                    'dependency' => array( 'activate_google_analytics', '==', 'true' ),
                ),

            ),

        );

        /**
         * Detect plugin. For use in Admin area only.
         */
        if ( ! is_plugin_active( 'ewww-image-optimizer/ewww-image-optimizer.php' ) ) {

            $fields[0]['fields'][] = array(

                'type'    => 'notice',
                'class'   => 'warning',
                'content' => 'We are recommend to use <a href="http://www.emotions-in-print.localhost/wp-admin/plugin-install.php?tab=plugin-information&plugin=ewww-image-optimizer" target="_blank">EWWW Image Optimizer</a> to optimize your images.',

            );

        }

        if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {

            $fields[0]['fields'][] = array(

                'type'    => 'notice',
                'class'   => 'warning',
                'content' => 'We are recommend to use <a href="http://www.emotions-in-print.localhost/wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-seo" target="_blank">Yoast SEO</a> to optimize your site SEO. This plugin is created to extend your site SEO after Yoast SEO is installed and activated.',

            );

        }

        $fields[1] = array(
            'name'   => 'head_section',
            'title'  => 'Head',
            'icon'   => 'fa fa-h-square',
            'fields' => array(

                array(
                    'id'      => 'ace_editor_head',
                    'type'    => 'ace_editor',
                    'title'   => 'Head',
                    'options' => array(
                        'theme'                     => 'ace/theme/chrome',
                        'mode'                      => 'ace/mode/html',
                        'showGutter'                => true,
                        'showPrintMargin'           => true,
                        'enableBasicAutocompletion' => true,
                        'enableSnippets'            => true,
                        'enableLiveAutocompletion'  => true,
                    ),
                    'attributes'    => array(
                        'style'        => 'height: 700px; max-width: 700px;',
                    ),
                    'description' => '<span class="info-links"><a href="https://technicalseo.com/seo-tools/schema-markup-generator/" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Schema Markup Generator</a><br><a href="http://www.geo-tag.de/generator/de.html" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Geo-Tag Generator</a></span>',
                ),

            ),
        );

        $fields[2] = array(
            'name'   => 'backup_section',
            'title'  => 'Backup',
            'icon'   => 'fa fa-floppy-o',
            'fields' => array(

                array(
                    'type'    => 'backup',
                    'title'   => 'Backup',
                ),

            ),
        );

        $options_panel = new Exopite_Simple_Options_Framework( $config, $fields );

    }


}

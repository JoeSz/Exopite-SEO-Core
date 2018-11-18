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

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-seo-core-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function checkGZIPCompression() {

        $api_url = 'https://checkgzipcompression.com/js/checkgzip.json?url=' . urlencode( get_site_url() );
        $result = wp_remote_get($api_url);
        $body = json_decode( $result['body'] );
        if( isset( $body->error ) && $body->error ) {
            $this->error = $body->error;
            return '<span class="exopite-seo-core-gzip exopite-seo-core-gzip-error"><b>' . esc_html( 'Error', 'exopite-seo-core' ) . '</b>: ' . $body->error . '</span>';
        }
        elseif ( isset( $body->result->gzipenabled ) && $body->result->gzipenabled == true ) {

            return '<span class="exopite-seo-core-gzip exopite-seo-core-gzip-success">' . esc_html( 'GZip is enabled.', 'exopite-seo-core' ) . '</span>' . esc_html( 'Uncompressed bytes', 'exopite-seo-core' ) . ': ' . $body->result->uncompressedbytes . ', ' . esc_html( 'Compressed bytes', 'exopite-seo-core' ) . ': ' . $body->result->compressedbytes . ', ' . esc_html( 'Total Saved', 'exopite-seo-core' ) . ': <b>' . $body->result->percentagesaved . '%</b>';
        }
        elseif ( isset( $body->result->gzipenabled ) && $body->result->gzipenabled == false ) {
            return '<span class="exopite-seo-core-gzip exopite-seo-core-gzip-warning">' . esc_html( 'GZip is not enabled.', 'exopite-seo-core' ) . '</span>' .  esc_html( 'You could save', 'exopite-seo-core' ) . ': <b>' . $body->result->percentagesaved . '%</b>';
        }
        else {
            return '<span class="exopite-seo-core-gzip exopite-seo-core-gzip-error">' . esc_html( 'Unknown error.', 'exopite-seo-core' ) . '<br>' . var_export( $result , true ) . '</span>';
        }

    }

    public function get_php_version() {
        return phpversion();
    }

    public function create_menu() {

        if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        $parent = ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) ? 'wpseo_dashboard' : 'plugins.php';
        $settings_link = ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) ? 'admin.php?page=exopite-seo-core' : 'plugins.php?page=exopite-seo-core';

        $config = array(

            'type'              => 'menu',                                          // Required, menu or metabox
            'id'                => $this->plugin_name,                              // Required, meta box id, unique per page, to save: get_option( id )
            'parent'            => $parent,                                         // Required, sub page to your options page
            'submenu'           => true,                                            // Required for submenu
            'settings-link'     => $settings_link,
            'title'             => esc_html__( 'SEO Core', 'exopite-seo-core' ),    //The name of this page
            'capability'        => 'manage_options',                                // The capability needed to view the page
            'plugin_basename'   =>  plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
            'tabbed'            => true,

        );

        $fields[0] = array(
            'name'   => 'general',
            'title'  => esc_html__( 'General', 'exopite-seo-core' ),
            'icon'   => 'dashicons-admin-generic',
            'fields' => array(

                array(
                    'id'      => 'deactivate_attachment_pages',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Deactivate attachment pages', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'   => 'If you change this, please <a href="/wp-admin/options-permalink.php" target="_blank">save permalinks</a>',
                ),

                array(
                    'id'      => 'auto_image_alt',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Automatically add image infos', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'    => esc_html__( 'Automatically Set the WordPress Image Title, Alt-Text & Other Meta' ),
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

                array(
                    'id'      => 'cookie_note',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Add cookie permission', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'type'    => 'notice',
                    'title'  => esc_html__( 'PHP version', 'exopite-seo-core' ),
                    'wrap_class' => 'exopite-seo-core-bottom-border',
                    'callback' => array(
                        'function' => array( $this, 'get_php_version' ),
                    ),
                ),

                array(
                    'type'    => 'notice',
                    'title'  => esc_html__( 'GZip', 'exopite-seo-core' ),
                    'wrap_class' => 'exopite-seo-core-bottom-border',
                    'callback' => array(
                        'function' => array( $this, 'checkGZIPCompression' ),
                    ),
                ),

            ),

        );

        if ( ! defined( 'EXOPITE_VERSION' ) ) {

            $fields[0]['fields'][] = array(
                'id'      => 'activate_gzip',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Activate GZip', 'exopite-seo-core' ),
                'default' => 'no',
            );

            $fields[0]['fields'][] = array(
                'id'      => 'remove_json_from_header',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Remove JSON links from header', 'exopite-seo-core' ),
                'default' => 'no',
            );

            $fields[0]['fields'][] = array(
                'id'      => 'limit_revisions',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Limit revisions', 'exopite-seo-core' ),
                'default' => 'no',
            );

            $fields[0]['fields'][] = array(
                'id'      => 'revision_to_keep',
                'type'    => 'range',
                'title'   => esc_html__( 'Revision to keep', 'exopite-seo-core' ),
                'default' => '20',
                'min'     => '0',
                'max'     => '100',
                'dependency' => array( 'limit_revisions', '==', 'true' ),
                'info'    =>  ' ' . 'For unlimited, set to 100.',
            );

            $fields[0]['fields'][] = array(
                'id'      => 'noidex_archives_search',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Add noidex on archives, search and 404', 'exopite-seo-core' ),
                'default' => 'no',
            );

            $fields[0]['fields'][] = array(
                'id'      => 'deactivate_comments',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Deactivate comments and pingbacks', 'exopite-seo-core' ),
                'default' => 'no',
            );

            $fields[0]['fields'][] = array(
                'type'    => 'notice',
                'title'  => esc_html__( 'Integrated shortcodes', 'exopite-seo-core' ),
                'content' => '<code>[exopite-breadcrumbs]</code> <code>[exopite-ga-optout link="' . esc_html__( 'Link Text', 'exopite-seo-core' ) . '"]</code>',
            );

        } else {

            $fields[0]['fields'][] = array(
                'type'    => 'notice',
                'title'  => esc_html__( 'Integrated shortcodes', 'exopite-seo-core' ),
                'content' => '<code>[exopite-ga-optout link="' . esc_html__( 'Link Text', 'exopite-seo-core' ) . '"]</code>',
            );

        }

        /**
         * Detect plugin. For use in Admin area only.
         */
        if ( ! is_plugin_active( 'ewww-image-optimizer/ewww-image-optimizer.php' ) ) {

            $fields[0]['fields'][] = array(

                'type'    => 'notice',
                'class'   => 'warning',
                'content' => sprintf( esc_html__( 'We are recommend to use %s to optimize your images.', 'exopite-seo-core' ), '<a href="' . get_site_url() . 'wp-admin/plugin-install.php?tab=plugin-information&plugin=ewww-image-optimizer" target="_blank">EWWW Image Optimizer</a>' ),

            );

        }

        if ( defined( 'EXOPITE_VERSION' ) || ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {

            $fields[0]['fields'][] = array(

                'type'    => 'notice',
                'class'   => 'warning',
                'content' => sprintf( esc_html__( 'We are recommend to use %s to optimize your site SEO. This plugin is created to extend your site SEO after Yoast SEO is installed and activated.', 'exopite-seo-core' ), '<a href="' . get_site_url() . 'wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-seo" target="_blank">Yoast SEO</a>' ),

            );

        }

        $fields[1] = array(
            'name'   => 'head_section',
            'title'  => esc_html__( 'Head', 'exopite-seo-core' ),
            'icon'   => 'fa fa-h-square',
            'fields' => array(

                array(
                    'id'      => 'ace_editor_head',
                    'type'    => 'ace_editor',
                    'title'   => esc_html__( 'Head (for HTML)', 'exopite-seo-core' ),
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
                        'style'        => 'height: 300px; max-width: 700px;',
                    ),
                    'description' => '<span class="info-links"><a href="https://technicalseo.com/seo-tools/schema-markup-generator/" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Schema Markup Generator</a><br><a href="http://www.geo-tag.de/generator/de.html" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Geo-Tag Generator</a></span>',
                ),

                array(
                    'id'      => 'ace_editor_head_css',
                    'type'    => 'ace_editor',
                    'title'   => esc_html__( 'Head inline CSS', 'exopite-seo-core' ),
                    'options' => array(
                        'theme'                     => 'ace/theme/chrome',
                        'mode'                      => 'ace/mode/css',
                        'showGutter'                => true,
                        'showPrintMargin'           => true,
                        'enableBasicAutocompletion' => true,
                        'enableSnippets'            => true,
                        'enableLiveAutocompletion'  => true,
                    ),
                    'attributes'    => array(
                        'style'        => 'height: 600px; max-width: 700px;',
                    ),
                    // 'description' => '<span class="info-links"><a href="https://technicalseo.com/seo-tools/schema-markup-generator/" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Schema Markup Generator</a><br><a href="http://www.geo-tag.de/generator/de.html" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i> Geo-Tag Generator</a></span>',
                ),

            ),
        );

        $fields[2] = array(
            'name'   => 'footer_section',
            'title'  => esc_html__( 'Footer', 'exopite-seo-core' ),
            'icon'   => 'fa fa-code',
            'fields' => array(

                array(
                    'id'      => 'ace_editor_footer',
                    'type'    => 'ace_editor',
                    'title'   => esc_html__( 'Footer', 'exopite-seo-core' ),
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
                        'style'        => 'height: 600px; max-width: 700px;',
                    ),

                ),

                array(
                    'id'      => 'ace_editor_footer_print_hook',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Use wp_print_footer_scripts hook', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'    => ' ' . esc_html__( 'For themes where wp_footer is not called.', 'exopite-seo-core' ),
                ),

            ),
        );

        $fields[3] = array(
            'name'       => 'cookie',
            'title'      => esc_html__( 'Cookie', 'exopite-seo-core' ),
            'icon'       => 'fa fa-birthday-cake',
            'dependency' => array( 'cookie_note', '==', 'true' ),
            'fields'     => array(

                array(
                    'id'      => 'cookie_hint_content_from_translation',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Content from translation file', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'         => 'cookie_hint_content_left',
                    'type'       => 'textarea',
                    'title'      => esc_html__( 'Content Left', 'exopite-seo-core' ),
                    'dependency' => array( 'cookie_hint_content_from_translation', '!=', 'true' ),
                    'default'    => esc_html__( "In order to optimize our website for you and to be able to continuously improve it, we use cookies. By continuing to use the website, you agree to the use of cookies.", 'exopite-seo-core' ) . " <a href='/" . esc_html_x( 'privacy-policy', 'Relative permalink slug', 'exopite-seo-core' ) . "/'>" . esc_html__( "More information", 'exopite-seo-core' ) . '</a>',
                ),

                array(
                    'id'         => 'cookie_hint_content_right',
                    'type'       => 'textarea',
                    'title'      => esc_html__( 'Content Right', 'exopite-seo-core' ),
                    'dependency' => array( 'cookie_hint_left_column_width|cookie_hint_content_from_translation', '<|!=', '100|true' ),
                ),

                array(
                    'id'         => 'cookie_hint_button',
                    'type'       => 'text',
                    'title'      => esc_html__( 'Button Text', 'exopite-seo-core' ),
                    'default'    => 'OK',
                    'dependency' => array( 'cookie_hint_content_from_translation', '!=', 'true' ),
                ),

                array(
                    'id'      => 'cookie_hint_wrapper_class',
                    'type'    => 'text',
                    'title'   => esc_html__( 'Theme Body Wapper Class', 'exopite-seo-core' ),
                    'default' => 'gdlr-core-container',
                ),

                array(
                    'id'     => 'cookie_hint_inner_wrapper_class',
                    'type'   => 'text',
                    'title'  => esc_html__( 'Theme Body Inner Wapper Class', 'exopite-seo-core' ),
                    'default' => 'gdlr-core-item-pdlr',
                ),

                array(
                    'id'      => 'cookie_hint_bg_color',
                    'type'    => 'color',
                    'title'   => esc_html__( 'Background Color', 'exopite-seo-core' ),
                    'rgba'    => true,
                    'default' => 'rgba(5,5,5,0.6)',
                ),

                array(
                    'id'      => 'cookie_hint_top_border_color',
                    'type'    => 'color',
                    'title'   => esc_html__( 'Top Border Color', 'exopite-seo-core' ),
                    'rgba'    => true,
                    'default' => 'rgba(5,5,5,0.6)',
                ),

                array(
                    'id'     => 'cookie_hint_text_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Text Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#ffffff',
                ),

                array(
                    'id'     => 'cookie_hint_link_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Link Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#ffffff',
                ),

                array(
                    'id'     => 'cookie_hint_link_hover_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Link Hover Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#ffffff',
                ),

                array(
                    'id'     => 'cookie_hint_link_underline',
                    'type'   => 'checkbox',
                    'title'  => esc_html__( 'Link Underline', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'     => 'cookie_hint_accept_bg_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Accept Button Background Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#000',
                ),

                array(
                    'id'     => 'cookie_hint_accept_bg_color_hover',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Accept Button Background Hover Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#000',
                ),

                array(
                    'id'     => 'cookie_hint_accept_text_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Accept Button Text Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#fff',
                ),

                array(
                    'id'     => 'cookie_hint_accept_text_color_hover',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Accept Button Text Hover Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#fff',
                ),

                array(
                    'id'     => 'cookie_hint_accept_footer_link_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Footer Menu Link Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#fff',
                ),

                array(
                    'id'     => 'cookie_hint_accept_footer_link_color_hover',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Footer Menu Link Hover Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#fff',
                ),

                array(
                    'id'     => 'cookie_hint_accept_footer_bg_color',
                    'type'   => 'color',
                    'title'  => esc_html__( 'Footer Menu Background Color', 'exopite-seo-core' ),
                    'rgba'   => true,
                    'default' => '#000',
                ),

                array(
                     'id'             => 'cookie_hint_accept_footer_links',
                     'type'           => 'select',
                     'title'          => 'Select Footer Menu Pages',
                     'query'          => array(
                         'type'           => 'pages',
                         'args'           => array(
                             'orderby'      => 'post_date',
                             'order'        => 'DESC',
                         ),
                     ),
                     'default_option' => '',
                     'class'       => 'chosen',
                     'attributes' => array(
                           'multiple' => 'multiple',
                           'style'    => 'width: 200px; height: 125px;',
                       ),
                 ),

                array(
                    'id'      => 'cookie_hint_left_column_width',
                    'type'    => 'range',
                    'title'   => esc_html__( 'Left Column Width', 'exopite-seo-core' ),
                    'default' => '100',
                    'info'   => ' <i class="text-muted">%</i>',
                    'min'     => '1',
                    'max'     => '100',
                ),

                array(
                    'id'      => 'cookie_hint_font_size',
                    'type'    => 'range',
                    'title'   => esc_html__( 'Font Size', 'exopite-seo-core' ),
                    'default' => '14',
                    'info'   => ' <i class="text-muted">px</i>',
                    'min'     => '6',
                    'max'     => '36',
                ),

                array(
                    'id'      => 'cookie_hint_top_padding',
                    'type'    => 'range',
                    'title'   => esc_html__( 'Top Padding', 'exopite-seo-core' ),
                    'default' => '7',
                    'info'   => ' <i class="text-muted">px</i>',
                    'min'     => '0',
                    'max'     => '60',
                ),

                array(
                    'id'      => 'cookie_hint_bottom_padding',
                    'type'    => 'range',
                    'title'   => esc_html__( 'Bottom Padding', 'exopite-seo-core' ),
                    'default' => '7',
                    'info'   => ' <i class="text-muted">px</i>',
                    'min'     => '0',
                    'max'     => '60',
                ),

                array(
                    'id'      => 'cookie_hint_footer_top_bottom_padding',
                    'type'    => 'range',
                    'title'   => esc_html__( 'Footer Top Bottom Padding', 'exopite-seo-core' ),
                    'default' => '2',
                    'info'   => ' <i class="text-muted">px</i>',
                    'min'     => '0',
                    'max'     => '60',
                ),

            ),

        );

        $fields[4] = array(
            'name'   => 'backup_section',
            'title'  => esc_html__( 'Backup', 'exopite-seo-core' ),
            'icon'   => 'fa fa-floppy-o',
            'fields' => array(

                array(
                    'type'    => 'backup',
                    'title'   => esc_html__( 'Backup', 'exopite-seo-core' ),
                ),

            ),
        );

        $options_panel = new Exopite_Simple_Options_Framework( $config, $fields );

    }


}

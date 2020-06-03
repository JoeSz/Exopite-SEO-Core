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

        if ( ! is_wp_error( $result ) ) {
            $body = json_decode( $result['body'] );
        }

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

        $php_version = phpversion();

        if ( $php_version < 7.0 ) {

            return '<span style="color:#9e0000;font-weight: 600;">' . phpversion() . ' - ' . esc_attr__( 'This version of php is outdated. Please update to 7.x.x.', 'exopite-seo-core' ) . '</span>';

        }

        return '<span style="color:#009307;font-weight: 600;">' . phpversion() . '</span>';
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
                    'type'    => 'notice',
                    'title'  => esc_html__( 'PHP version', 'exopite-seo-core' ),
                    'wrap_class' => 'exopite-seo-core-bottom-border',
                    'callback' => array(
                        'function' => array( $this, 'get_php_version' ),
                    ),
                ),

                array(
                    'id'      => 'deactivate_attachment_pages',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Deactivate attachment pages', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'   => esc_html__( 'If you change this, please', 'exopite-seo-core' ),' <a href="/wp-admin/options-permalink.php" target="_blank" rel="noreferrer noopener">' . esc_html__( 'save permalinks', 'exopite-seo-core' ) . '</a>',
                ),

                array(
                    'id'      => 'deactivate_feed',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Deactivate feed', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'cookie_note',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Add cookie permission', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                array(
                    'id'      => 'sanitize_file_name',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Sanitize file name', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'   => esc_html__( 'Empty spaces and special characters can cause problems and they are not SEO freundly.', 'exopite-seo-core' ),
                ),

                array(
                    'id'      => 'canonical_url',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Add Canonical URL to head', 'exopite-seo-core' ),
                    'default' => 'no',
                ),

                // array(
                //     'id'      => 'links_nofollow',
                //     'type'    => 'switcher',
                //     'title'   => esc_html__( 'Add "nofollow" to external links', 'exopite-seo-core' ),
                //     'default' => 'no',
                //     'info'    => esc_html__( "Use this to signify to the search englines, that you don't vouch for a page you link to.", "exopite-seo-core" ),
                // ),

                // array(
                //     'id'      => 'links_noopener_noreferer',
                //     'type'    => 'switcher',
                //     'title'   => esc_html__( 'Add "noopener", "noreferrer" to external links"', 'exopite-seo-core' ),
                //     'default' => 'no',
                //     'info'   => esc_html__( 'Apply only to links with target="_blank" attribute.', 'exopite-seo-core' ) . '<br>' . esc_html__( 'If you not doing this, can couse performance and security issues for the user.', 'exopite-seo-core' ),
                //     'description' => '<span class="info-links"><a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#attr-target" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Mozilla attr-target</a><br><a href="https://mathiasbynens.github.io/rel-noopener/" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> About rel=noopener</a></span>',
                // ),

            ),

        );

        if ( ! defined( 'EXOPITE_VERSION' ) ) {

            $fields[0]['fields'][] = array(
                'id'      => 'auto_image_alt',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Automatically add image infos', 'exopite-seo-core' ),
                'default' => 'no',
                'info'    => esc_html__( 'Automatically Set the WordPress Image Title, Alt-Text & Other Meta for new images' ),
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
                'info'    => esc_html__( 'Whenever you update a post in WordPress, it creates a revision, which never will be deleted.', 'exopite-seo-core' ) . '<br>' . esc_html__( 'Eventually causes performance issues.', 'exopite-seo-core' ),
            );

            $fields[0]['fields'][] = array(
                'id'      => 'revision_to_keep',
                'type'    => 'range',
                'title'   => esc_html__( 'Revision to keep', 'exopite-seo-core' ),
                'default' => '10',
                'min'     => '0',
                'max'     => '100',
                'dependency' => array( 'limit_revisions', '==', 'true' ),
                'info'    =>  ' ' . esc_html__( 'For unlimited, set to 100.', 'exopite-seo-core' ),
            );

            $fields[0]['fields'][] = array(
                'id'      => 'noidex_archives_search',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Add "noindex"', 'exopite-seo-core' ),
                'info'    => esc_html__( 'to blog, archives, search and 404 pages', 'exopite-seo-core' ),
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
                'content' => sprintf( esc_html__( 'We are recommend to use %s to optimize your images.', 'exopite-seo-core' ), '<a href="' . get_site_url() . 'wp-admin/plugin-install.php?tab=plugin-information&plugin=ewww-image-optimizer" target="_blank" rel="noreferrer noopener">EWWW Image Optimizer</a>' ),

            );

        }

        if ( defined( 'EXOPITE_VERSION' ) || ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {

            $fields[0]['fields'][] = array(

                'type'    => 'notice',
                'class'   => 'warning',
                'content' => sprintf( esc_html__( 'We are recommend to use %s to optimize your site SEO. This plugin is created to extend your site SEO after Yoast SEO is installed and activated.', 'exopite-seo-core' ), '<a href="' . get_site_url() . 'wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-seo" target="_blank" rel="noreferrer noopener">Yoast SEO</a>' ),

            );

        }

        if ( ! is_plugin_active( 'images-to-webp/images-to-webp.php' ) ) {

            $fields[0]['fields'][] = array(

                'type'    => 'notice',
                'class'   => 'warning',
                'content' => sprintf( esc_html__( 'We are recommend to use %s to optimize your images. This plugin is created to use WebP, Googles new images format, whenever is possible.', 'exopite-seo-core' ), '<a href="' . get_site_url() . 'wp-admin/plugin-install.php?tab=plugin-information&plugin=images-to-webp" target="_blank" rel="noreferrer noopener">Images to WebP</a>' ),

            );

        }

        $fields[] = array(
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
                    'description' => '<span class="info-links"><a href="https://technicalseo.com/seo-tools/schema-markup-generator/" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Schema Markup Generator</a><br><a href="http://www.geo-tag.de/generator/de.html" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Geo-Tag Generator</a></span>',
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
                ),

            ),
        );

        $fields[] = array(
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
                    'description' => '<span class="info-links">
                    <a href="https://analytics.google.com/analytics/web/" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Open Google Analytics</a><br>
                    <a href="https://www.google.com/webmasters/tools/home?pli=1" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Open Google Search Console</a><br>
                    <a href="https://tagmanager.google.com/" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Open Google Tag Manager</a><br>
                    <a href="https://checkgoogleanalytics.psi.uni-bamberg.de/" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Check Anonymize IP</a><br>
                    <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Google ip-anonymization</a><br>
                    <a href="https://gtmetrix.com/" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> GTmetrix</a><br>
                    <a href="https://developers.google.com/speed/pagespeed/insights/?hl=EN&url=' . urlencode( get_site_url() ) .  '" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> PageSpeed Insights</a><br>
                    </span>',

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

        $fields[] = array(
            'name'   => 'robots_txt_section',
            'title'  => esc_html__( 'Robots.txt', 'exopite-seo-core' ),
            'icon'   => 'fa fa-file-text-o',
            'fields' => array(

                array(
                    'id'      => 'activate_robots_txt',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Append text to robots.txt', 'exopite-seo-core' ),
                    'default' => 'no',
                    'info'    => '<span class="info-links ga-links"><a href="https://support.google.com/webmasters/answer/6062596?hl=en" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> Google: about robots.txt</a><br><a href="/robots.txt" target="_blank" rel="noreferrer noopener"><i class="fa fa-arrow-right" aria-hidden="true"></i> See robots.txt</a></span>',
                ),

                array(
                    'id'      => 'append_to_robots_txt',
                    'type'    => 'textarea',
                    'title'   => esc_html__( 'Text to append to the robots.txt file', 'exopite-seo-core' ),
                    'dependency' => array( 'activate_robots_txt', '==', 'true' ),
                    'default' => "Disallow: /?s=\nDisallow: /search/",
                    // 'description' => '',
                    // 'info'    => ' <em>' . esc_html__( 'Leave empty to ignore', 'exopite-seo-core' ) . '</em>',

                ),

            ),
        );

        $fields[] = array(
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

        $fields[] = array(
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

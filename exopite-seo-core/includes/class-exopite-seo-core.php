<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/includes
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Seo_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Exopite_Seo_Core_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EXOPITE_SEO_CORE_VERSION' ) ) {
			$this->version = EXOPITE_SEO_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'exopite-seo-core';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Exopite_Seo_Core_Loader. Orchestrates the hooks of the plugin.
	 * - Exopite_Seo_Core_i18n. Defines internationalization functionality.
	 * - Exopite_Seo_Core_Admin. Defines all hooks for the admin area.
	 * - Exopite_Seo_Core_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exopite-seo-core-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exopite-seo-core-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-exopite-seo-core-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exopite-seo-core-public.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/exopite-simple-options/exopite-simple-options-framework-class.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exopite-seo-core-cookie-notice.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exopite-seo-core-breadcrumbs.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exopite-seo-core-sanitize.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exopite-seo-core-links.php';

		$this->loader = new Exopite_Seo_Core_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Exopite_Seo_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Exopite_Seo_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Exopite_Seo_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // Save/Update our plugin options
        $this->loader->add_action('init', $plugin_admin, 'create_menu');

	}

	/**
     * Checks if the current request is a WP REST API request.
     *
     * Case #1: After WP_REST_Request initialisation
     * Case #2: Support "plain" permalink settings
     * Case #3: URL Path begins with wp-json/ (your REST prefix)
     *          Also supports WP installations in subfolders
     *
     * @returns boolean
     * @author matzeeable
	 * @link https://wordpress.stackexchange.com/questions/221202/does-something-like-is-rest-exist/317041#317041
	 * @link https://gist.github.com/matzeeable/dfd82239f48c2fedef25141e48c8dc30
     */
    function is_rest() {
        $prefix = rest_get_url_prefix( );
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST // (#1)
            || isset( $_GET['rest_route'] ) // (#2)
            	&& strpos( trim( $_GET['rest_route'], '\\/' ), $prefix , 0 ) === 0) {
				return true;
			}

        // (#3)
        $rest_url = wp_parse_url( site_url( $prefix ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );
        return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
    }

    function is_api_request() {

        if ( ! ( ( defined( 'JSON_REQUEST' ) && JSON_REQUEST ) ||
             ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ||
             ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
             $this->is_rest()
        ) ) {
            return false;
        }

        return true;
    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		if ( is_admin() ) {
			return;
		}

        $options = get_exopite_sof_option( $this->plugin_name );

		$plugin_public = new Exopite_Seo_Core_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_cookie_notice = new Exopite_Seo_Core_Cookie_Notice( $this->get_plugin_name(), $this->get_version() );
		$plugin_breadcrumb = new Exopite_Seo_Core_Breadcrumbs( $this->get_plugin_name(), $this->get_version() );
		$plugin_sanitize = new Exopite_Seo_Core_Sanitize( $this->get_plugin_name(), $this->get_version() );
		$plugin_links = new Exopite_Seo_Core_Links( $this->get_plugin_name(), $this->get_version() );

        $cookie_note = ( isset( $options['cookie_note'] ) ) ? $options['cookie_note'] : 'no';
        $remove_json_from_header = ( isset( $options['remove_json_from_header'] ) ) ? $options['remove_json_from_header'] : 'no';
		$deactivate_attachment_pages = ( isset( $options['deactivate_attachment_pages'] ) ) ? $options['deactivate_attachment_pages'] : 'no';
		$deactivate_category_archives = ( isset( $options['deactivate_category_archives'] ) ) ? $options['deactivate_category_archives'] : 'no';
		$deactivate_tags_archives = ( isset( $options['deactivate_tags_archives'] ) ) ? $options['deactivate_tags_archives'] : 'no';
		$deactivate_archives = ( isset( $options['deactivate_archives'] ) ) ? $options['deactivate_archives'] : 'no';
        $noidex_archives_search = ( isset( $options['noidex_archives_search'] ) ) ? $options['noidex_archives_search'] : 'no';
        $auto_image_alt = ( isset( $options['auto_image_alt'] ) ) ? $options['auto_image_alt'] : 'no';
        $limit_revisions = ( isset( $options['limit_revisions'] ) ) ? $options['limit_revisions'] : 'no';
        $deactivate_comments = ( isset( $options['deactivate_comments'] ) ) ? $options['deactivate_comments'] : 'no';
        $deactivate_feed = ( isset( $options['deactivate_feed'] ) ) ? $options['deactivate_feed'] : 'no';
        $ace_editor_head = ( isset( $options['ace_editor_head'] ) ) ? $options['ace_editor_head'] : '';
        $ace_editor_footer = ( isset( $options['ace_editor_footer'] ) && ! empty( $options['ace_editor_footer'] ) ) ? true : false;
        $ace_editor_footer_print_hook = ( isset( $options['ace_editor_footer_print_hook'] ) ) ? $options['ace_editor_footer_print_hook'] : 'no';
        $sanitize_file_name = ( isset( $options['sanitize_file_name'] ) ) ? $options['sanitize_file_name'] : 'no';
        $canonical_url = ( isset( $options['canonical_url'] ) ) ? $options['canonical_url'] : 'no';
		$activate_robots_txt = ( isset( $options['activate_robots_txt'] ) ) ? $options['activate_robots_txt'] : 'no';
        $links_nofollow = ( isset( $options['links_nofollow'] ) ) ? $options['links_nofollow'] : 'no';
        $links_noopener_noreferer = ( isset( $options['links_noopener_noreferer'] ) ) ? $options['links_noopener_noreferer'] : 'no';

        if ( $options['links_nofollow'] == 'yes' || $options['links_noopener_noreferer'] == 'yes' ) {

            if ( ! $this->is_api_request() ) {

				/**
				 * PHP Simple HTML DOM Parser
				 * @link https://sourceforge.net/projects/simplehtmldom/files/
				 *
				 * Docs:
				 * - https://stackoverflow.com/questions/14264525/php-simple-html-dom-parser-select-only-divs-with-multiple-classes
				 * - https://code.tutsplus.com/tutorials/html-parsing-and-screen-scraping-with-the-simple-html-dom-library--net-11856
				 * - https://stackoverflow.com/questions/4812691/preserve-line-breaks-simple-html-dom-parser
				 */
				if( ! class_exists( 'simple_html_dom' ) ) {
					require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/simple_html_dom.1.9.1.php';
				}

                if ( apply_filters( 'exopite_ob_status', 'off' ) != 'on' ) {

                    $this->loader->add_filter( 'wp_loaded', $plugin_links, 'buffer_start', 12 );
                    $this->loader->add_filter( 'shutdown', $plugin_links, 'buffer_end', 12 );

                }

                $this->loader->add_filter( 'exopite_ob_content', $plugin_links, 'process_html', 10 );

            }

        }

        /**
         * Remove JSON API description
         * @link https://wordpress.stackexchange.com/questions/211467/remove-json-api-links-in-header-html/212472#212472
         */
        if ( ! defined( 'EXOPITE_VERSION' ) && $remove_json_from_header == 'yes' ) {

            $this->loader->add_action( 'after_setup_theme', $plugin_public, 'remove_json_api_links_from_header' );

        }

        /**
         * Plugin Name: Disable Attachment Pages
         * Plugin URI: https://gschoppe.com/wordpress/disable-attachment-pages
         * Description: Completely disable attachment pages the right way. No forced redirects or 404s, no reserved slugs.
         * Author: Greg Schoppe
         * Author URI: https://gschoppe.com/
         * Version: 1.0.0
         **/
        if ( $deactivate_attachment_pages == 'yes' ) {

            $this->loader->add_filter( 'rewrite_rules_array', $plugin_public, 'remove_attachment_rewrites' );
            $this->loader->add_filter( 'wp_unique_post_slug', $plugin_public, 'wp_unique_post_slug', 10, 6 );
            $this->loader->add_filter( 'request', $plugin_public, 'remove_attachment_query_var' );
            $this->loader->add_filter( 'attachment_link', $plugin_public, 'change_attachment_link_to_file', 10, 2 );

            // just in case everything else fails, and somehow an attachment page is requested
            $this->loader->add_action( 'template_redirect', $plugin_public, 'redirect_attachment_pages_to_file' );

        }

		/**
		 * Remove category, tag, date and author archives from WordPress
		 * @link https://mekshq.com/remove-archives-wordpress-improve-seo/
		 * @link https://wordpress.org/support/topic/remove-the-archive-pages-from-the-sitemap/
		 */
		if ( $deactivate_category_archives == 'yes' ) {
			$this->loader->add_filter( 'template_redirect', $plugin_public, 'template_redirect_deactivate_category_archives', 10, 2 );
			$this->loader->add_filter( 'wp_sitemaps_taxonomies', $plugin_public, 'wp_sitemaps_taxonomies_deactivate_category_archives', 10, 2 );
		}
		if ( $deactivate_tags_archives == 'yes' ) {
			$this->loader->add_filter( 'template_redirect', $plugin_public, 'template_redirect_deactivate_tags_archives', 10, 2 );
			$this->loader->add_filter( 'wp_sitemaps_taxonomies', $plugin_public, 'wp_sitemaps_taxonomies_deactivate_tags_archives', 10, 2 );
		}
		if ( $deactivate_archives == 'yes' ) {
			$this->loader->add_filter( 'template_redirect', $plugin_public, 'template_redirect_deactivate_archives', 10, 2 );
			$this->loader->add_filter( 'wp_sitemaps_add_provider', $plugin_public, 'wp_sitemaps_add_provider_deactivate_archives', 10, 2 );
		}

        /**
         * Add Canonical URL for singles
         */
        if ( $canonical_url == 'yes' ) {

			if( function_exists( 'rel_canonical' ) ) {
				remove_action( 'wp_head', 'rel_canonical' );
			}

            $this->loader->add_action( 'wp_head', $plugin_public, 'canonical_url' );

        }

        /**
         * Append text for robots.txt
         */
        if ( $activate_robots_txt == 'yes' ) {

            $this->loader->add_filter( 'robots_txt', $plugin_public, 'robots_txt' );

        }

        /**
         * Add noindex on archives, search and 404
         */
        if ( ! defined( 'EXOPITE_VERSION' ) && $noidex_archives_search == 'yes' ) {

            $this->loader->add_action( 'wp_head', $plugin_public, 'noidex_archives_search' );

        }

        if ( ! empty( $ace_editor_head ) ) {

            $this->loader->add_action( 'wp_head', $plugin_public, 'ace_editor_head' );

        }

        if ( $ace_editor_footer ) {

            $hook = 'wp_footer';

            if ( $ace_editor_footer_print_hook == 'yes' ) {

                $hook = 'wp_print_footer_scripts';

            }

            $this->loader->add_action( $hook, $plugin_public, 'add_to_footer', 999 );

        }

        if ( $sanitize_file_name == 'yes' ) {

			/**
			 * Sanitize filename.
			 *
			 * WordPress build in sanitize_file_name will not take care umlauts.
			 * This generate sometime some issues with urls and filenames.
			 */
			$this->loader->add_filter( 'sanitize_file_name', $plugin_sanitize, 'sanitize_file_name', 10, 2 );

		}

        if ( $auto_image_alt == 'yes' ) {

            /*
             * Automatically Set the WordPress Image Title, Alt-Text & Other Meta
             *
             * @link https://brutalbusiness.com/automatically-set-the-wordpress-image-title-alt-text-other-meta/
             */
            $this->loader->add_action( 'add_attachment', $plugin_public, 'auto_image_alt' );

        }

        if ( ! defined( 'EXOPITE_VERSION' ) && $deactivate_comments == 'yes' ) {

            // Disable support for comments and trackbacks in post types
            $this->loader->add_action('admin_init', $plugin_public, 'disable_comments_post_types_support');
            // Close comments on the front-end
            $this->loader->add_filter('comments_open', $plugin_public, 'disable_comments_status', 20, 2);
            $this->loader->add_filter('pings_open', $plugin_public, 'disable_comments_status', 20, 2);
            // Hide existing comments
            $this->loader->add_filter('comments_array', $plugin_public, 'disable_comments_hide_existing_comments', 10, 2);
            // Remove comments page in menu
            $this->loader->add_action('admin_menu', $plugin_public, 'disable_comments_admin_menu');
            // Redirect any user trying to access comments page
            $this->loader->add_action('admin_init', $plugin_public, 'disable_comments_admin_menu_redirect');
            // Remove comments metabox from dashboard
            $this->loader->add_action('admin_init', $plugin_public, 'disable_comments_dashboard');
            // Remove comments links from admin bar
            $this->loader->add_action('init', $plugin_public, 'disable_comments_admin_bar');

        }

        if ( ! defined( 'EXOPITE_VERSION' ) && $limit_revisions == 'yes' ) {

            /**
             * Limit revisions
             *
             * @link https://www.sitepoint.com/wordpress-post-revision-control/
             */
            $this->loader->add_filter( 'wp_revisions_to_keep', $plugin_public, 'limit_revisions', 10, 2 );

        }

        if ( $deactivate_feed == 'yes' ) {

            /**
             * Limit revisions
             *
             * @link https://www.sitepoint.com/wordpress-post-revision-control/
             */
            $this->loader->add_action('do_feed', $plugin_public, 'disable_feed', 1);
            $this->loader->add_action('do_feed_rdf', $plugin_public, 'disable_feed', 1);
            $this->loader->add_action('do_feed_rss', $plugin_public, 'disable_feed', 1);
            $this->loader->add_action('do_feed_rss2', $plugin_public, 'disable_feed', 1);
            $this->loader->add_action('do_feed_atom', $plugin_public, 'disable_feed', 1);
            $this->loader->add_action('do_feed_rss2_comments', $plugin_public, 'disable_feed', 1);
            $this->loader->add_action('do_feed_atom_comments', $plugin_public, 'disable_feed', 1);

        }

        if ( ! defined( 'EXOPITE_VERSION' ) ) $this->loader->add_shortcode( 'exopite-breadcrumbs', $plugin_breadcrumb, 'breadcrumbs' );

        $this->loader->add_shortcode( "exopite-ga-optout", $plugin_public, "ga_optout", $priority = 10, $accepted_args = 2 );


        if ( $cookie_note == 'yes' ) {

            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_cookie_notice, 'enqueue_styles', 999 );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_cookie_notice, 'enqueue_scripts', 999 );
            $this->loader->add_action( 'wp_footer', $plugin_cookie_notice, 'cookie_note', 1 );

        }

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Exopite_Seo_Core_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

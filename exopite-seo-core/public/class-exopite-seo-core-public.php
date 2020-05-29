<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Exopite_Seo_Core
 * @subpackage Exopite_Seo_Core/public
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Seo_Core_Public {

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

    private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->options = get_exopite_sof_option( $this->plugin_name );

	}

    public function remove_json_api_links_from_header() {

        // Remove the REST API lines from the HTML Header
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

        // Turn off oEmbed auto discovery.
        add_filter( 'embed_oembed_discover', '__return_false' );

        // Remove oEmbed discovery links.
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    }

    /**
     * Plugin Name: Disable Attachment Pages
     * Plugin URI: https://gschoppe.com/wordpress/disable-attachment-pages
     * Description: Completely disable attachment pages the right way. No forced redirects or 404s, no reserved slugs.
     * Author: Greg Schoppe
     * Author URI: https://gschoppe.com/
     * Version: 1.0.0
     **/
    /*
    https://www.emotions-in-print.de/feed/?attachment_id=4750
    https://www.emotions-in-print.de/media_category/blog/page/2/

    www.emotions-in-print.localhost/media_category/mitarbeiter-portraits
    http://www.emotions-in-print.localhost/portfolio/boehringer-ingelheim-vetmedin-chew-soundkarte/attachment/audio-logo-gmbh-sound-in-print-1440-boehringer-vetmedin-web/
     */
    public function remove_attachment_rewrites( $rules ) {

        foreach ( $rules as $pattern => $rewrite ) {

            if ( preg_match( '/([\?&]attachment=\$matches\[)/', $rewrite ) ) {

                unset( $rules[$pattern] );

            }

        }

        return $rules;

    }

    // this function is a trimmed down version of `wp_unique_post_slug` from WordPress 4.8.3
    public function wp_unique_post_slug( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {

        global $wpdb, $wp_rewrite;

        if ( $post_type =='nav_menu_item' ) {

            return $slug;

        }

        if ( $post_type == "attachment" ) {

            $prefix = apply_filters( 'gjs_attachment_slug_prefix', 'wp-attachment-', $original_slug, $post_ID, $post_status, $post_type, $post_parent );

            if ( ! $prefix ) {

                return $slug;

            }

            // remove this filter and rerun with the prefix
            remove_filter( 'wp_unique_post_slug', array( $this, 'wp_unique_post_slug' ), 10 );

            $slug = wp_unique_post_slug( $prefix . $original_slug, $post_ID, $post_status, $post_type, $post_parent );

            add_filter( 'wp_unique_post_slug', array( $this, 'wp_unique_post_slug' ), 10, 6 );

            return $slug;

        }

        if ( ! is_post_type_hierarchical( $post_type ) ) {

            return $slug;

        }

        $feeds = $wp_rewrite->feeds;

        if( ! is_array( $feeds ) ) {

            $feeds = array();

        }

        /**
         * NOTE: This is the big change. We are NOT checking attachments along with our post type
         */
        $slug = $original_slug;

        $check_sql = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type IN ( %s ) AND ID != %d AND post_parent = %d LIMIT 1";

        $post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $slug, $post_type, $post_ID, $post_parent ) );

        /**
         * Filters whether the post slug would make a bad hierarchical post slug.
         *
         * @since 3.1.0
         *
         * @param bool   $bad_slug    Whether the post slug would be bad in a hierarchical post context.
         * @param string $slug        The post slug.
         * @param string $post_type   Post type.
         * @param int    $post_parent Post parent ID.
         */
        if ( $post_name_check || in_array( $slug, $feeds ) || 'embed' === $slug || preg_match( "@^($wp_rewrite->pagination_base)?\d+$@", $slug )  || apply_filters( 'wp_unique_post_slug_is_bad_hierarchical_slug', false, $slug, $post_type, $post_parent ) ) {

            $suffix = 2;

            do {

                $alt_post_name = _truncate_post_slug( $slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";

                $post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $alt_post_name, $post_type, $post_ID, $post_parent ) );

                $suffix++;

            } while ( $post_name_check );

            $slug = $alt_post_name;

        }

        return $slug;

    }

    public function remove_attachment_query_var( $vars ) {

        if ( ! empty( $vars['attachment'] ) ) {

            $vars['page'] = '';

            $vars['name'] = $vars['attachment'];

            unset( $vars['attachment'] );

        }

        return $vars;

    }

    public function make_attachments_private( $args, $slug ) {

        if ( $slug == 'attachment' ) {

            $args['public'] = false;

            $args['publicly_queryable'] = false;

        }

        return $args;

    }

    public function change_attachment_link_to_file( $url, $id ) {

        $attachment_url = wp_get_attachment_url( $id );

        if ( $attachment_url ) {

            return $attachment_url;

        }

        return $url;

    }

    public function redirect_attachment_pages_to_file() {

        if ( is_attachment() ) {

            $id = get_the_ID();

            $url = wp_get_attachment_url( $id );

            if ( $url ) {

                wp_redirect( $url, 301 );

                die;

            }

        }

    }

    public function robots_txt( $text ) {

        return $text . esc_html( $this->options['append_to_robots_txt'] );
    }

    public function is_https() {
        return ( ! empty($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;
    }

    public function canonical_url() {
        global $post;

        // start page, is_archive, is_search, is_paged
        $canonical = site_url();
        $https = ( $this->is_https() ) ? 's' : '';

        if( is_singular() && ! is_attachment() ) {

            $canonical = wp_get_canonical_url();

        } elseif ( is_home() && "page" == get_option('show_on_front') ) {

            $canonical = get_permalink( get_option( 'page_for_posts' ) );

        } elseif ( is_attachment() ) {

            $canonical = get_permalink( $post->post_parent );

        } elseif ( is_home() ) {

            $canonical = 'http' . $https . '://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

        } elseif ( is_category() ) {

            $category_id = get_query_var( 'cat' );
            $canonical = get_category_link( $category_id );

        } else {

            $canonical = 'http' . $https . '://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

        }

	    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . PHP_EOL;

    }

    public function noidex_archives_search() {

        if ( is_author() || is_date() || is_search() || is_category() || is_tag() || is_404() || is_home() ) {

            echo '<meta name="robots" content="noindex,follow" />';

        }

    }

    public function ace_editor_head() {

        echo $this->options['ace_editor_head'];


    }

    public function disable_feed() {

        $location = get_site_url();
        wp_redirect( $location, 301 );
        exit;

    }

    public function limit_revisions( $num, $post ) {

        $revision_to_keep = intval( $this->options['revision_to_keep'] );

        if ( $revision_to_keep >= 0 && $revision_to_keep < 100 ) {

            // If 100 then do not change
            $num = $revision_to_keep;

        }

        return $num;

    }

    /*
     * Automatically Set the WordPress Image Title, Alt-Text & Other Meta
     *
     * @link https://brutalbusiness.com/automatically-set-the-wordpress-image-title-alt-text-other-meta/
     */
    public function auto_image_alt( $post_ID ) {

        // Check if uploaded file is an image, else do nothing

        if ( wp_attachment_is_image( $post_ID ) ) {

            // Get image filename
            // @link https://wordpress.stackexchange.com/questions/30313/change-attachment-filename
            $my_image_title = get_post( $post_ID )->post_title;

            // https://stackoverflow.com/questions/5546120/php-capitalize-after-dash/5546534#5546534
            $my_image_title = implode( '-', array_map( 'ucfirst', explode( '--', $my_image_title ) ) );

			// Remove multiple -
			$my_image_title = preg_replace( '/-+/', '-', $my_image_title );

            // Sanitize the title:  remove hyphens, underscores & extra spaces:
            $my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );

            // Sanitize the title:  capitalize first letter of every word (other letters lower case):
            $my_image_title = ucfirst( $my_image_title );

            // Create an array with the image meta (Title, Caption, Description) to be updated
            // Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
            $my_image_meta = array(
                'ID'            => $post_ID,            // Specify the image (ID) to be updated
                'post_title'    => $my_image_title,     // Set image Title to sanitized title
                'post_excerpt'  => $my_image_title,     // Set image Caption (Excerpt) to sanitized title
                'post_content'  => $my_image_title,     // Set image Description (Content) to sanitized title
            );

            // Set the image Alt-Text
            update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

            // Set the image meta (e.g. Title, Excerpt, Content)
            wp_update_post( $my_image_meta );

        }

    }

    // Disable support for comments and trackbacks in post types
    public function disable_comments_post_types_support() {
        $post_types = get_post_types();
        foreach ( $post_types as $post_type ) {
            if( post_type_supports( $post_type, 'comments' ) ) {
                remove_post_type_support( $post_type, 'comments' );
                remove_post_type_support( $post_type, 'trackbacks' );
            }
        }
    }

    // Close comments on the front-end
    public function disable_comments_status() {
        return false;
    }

    // Hide existing comments
    public function disable_comments_hide_existing_comments( $comments ) {
        $comments = array();
        return $comments;
    }

    // Remove comments page in menu
    public function disable_comments_admin_menu() {
        remove_menu_page( 'edit-comments.php' );
    }

    // Redirect any user trying to access comments page
    public function disable_comments_admin_menu_redirect() {
        global $pagenow;
        if ( $pagenow === 'edit-comments.php' ) {
            wp_redirect( admin_url() ); exit;
        }
    }

    // Remove comments metabox from dashboard
    public function disable_comments_dashboard() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }

    // Remove comments links from admin bar
    public function disable_comments_admin_bar() {
        if ( is_admin_bar_showing() ) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }

    public function ga_optout( $atts ) {

        $args = shortcode_atts(
            array(
                'link'   => 'Google Analytics OptOut',
            ),
            $atts
        );

        return '<a href="javascript:gaOptout();">' . $args['link'] . '</a>';

    }

    public function add_to_footer() {

        echo $this->options['ace_editor_footer'];

    }

}

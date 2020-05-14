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

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$cookie_note = ( isset( $this->options['cookie_note'] ) ) ? $this->options['cookie_note'] : 'no';
        $custom_css = '';
        // wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-seo-core-public.css', array(), $this->version, 'all' );

        if ( $cookie_note == 'yes' ) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-seo-core-public-cookie-note.css', array(), $this->version, 'all' );

            $custom_css .= "
            .cookie-container .cookie-text a:hover {
                color:" . $this->options['cookie_hint_link_hover_color'] . ";
            }
            .cookie-container .cookie-text a {
                color:" . $this->options['cookie_hint_link_color'] . ";
            }
            .cookie-container {
                display:none;
                background:" . $this->options['cookie_hint_bg_color'] . ";
                border-top:2px solid " . $this->options['cookie_hint_top_border_color'] . ";
                font-size:" . $this->options['cookie_hint_font_size'] . "px;
            }
            .cookie-wrapper-container .cookie-column {
                padding-top:" . $this->options['cookie_hint_top_padding'] . "px;
                padding-bottom:" . $this->options['cookie_hint_bottom_padding'] . "px;
            }
            .cookie-column-inner {
                color:" . $this->options['cookie_hint_text_color'] . ";
            }
            .cookie-btn {
                float:right;
                background:" . $this->options['cookie_hint_accept_bg_color'] . ";
                color:" . $this->options['cookie_hint_accept_text_color'] . ";
                transition: all 200ms ease;
            }
            .cookie-btn:hover {
                float:right;
                background:" . $this->options['cookie_hint_accept_bg_color_hover'] . ";
                color:" . $this->options['cookie_hint_accept_text_color_hover'] . ";
            }
            .cookie-container-footer {
                background:" . $this->options['cookie_hint_accept_footer_bg_color'] . ";
                color:" . $this->options['cookie_hint_accept_footer_link_color'] . ";
            }
            .cookie-container-footer .cookie-column-footer a {
                color:" . $this->options['cookie_hint_accept_footer_link_color'] . ";
            }
            .cookie-container-footer .cookie-column-footer a:hover {
                color:" . $this->options['cookie_hint_accept_footer_link_color_hover'] . ";
            }
            .cookie-container-footer .cookie-column-footer {
                padding:" . $this->options['cookie_hint_footer_top_bottom_padding'] . "px 0;
            }
            ";
            if ( $this->options['cookie_hint_link_underline'] === 'yes' ) {
                $custom_css .= "
                .cookie-container .cookie-text a {
                    text-decoration: underline;
                }
                ";
            }
        }

        if ( ! empty( $this->options['ace_editor_head_css'] ) ) {
            $custom_css .= esc_html( $this->options['ace_editor_head_css'] );
        }

        wp_add_inline_style( $this->plugin_name, $custom_css );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        $cookie_note = ( isset( $this->options['cookie_note'] ) ) ? $this->options['cookie_note'] : 'no';

        // wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-seo-core-public.js', array( 'jquery' ), $this->version, false );

        if ( $cookie_note == 'yes' ) {
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-seo-core-public-cookie-note.js', array( 'jquery' ), $this->version, false );
        }

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

    /*
     Plugin Name: GZIP Output
     Plugin URI: http://www.ilfilosofo.com/blog/2008/02/22/wordpress-gzip-plugin/
     Version: 1.1
     Description: Allow GZIPped output for your WordPress blog.  Restores functionality removed in WordPress 2.5.
     Author: Austin Matzko
     Author URI: http://www.ilfilosofo.com/
     */
    /* Copyright 2008 Austin Matzko    if.website at gmail.com License: GPL 2 */
    public function gzip_compression() {

        if ( is_admin() || in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) return;

        // don't use on TinyMCE
        if ( stripos($_SERVER['REQUEST_URI'], 'wp-includes/js/tinymce' ) !== false ) {
            return false;
        }
        // can't use zlib.output_compression and ob_gzhandler at the same time
        if ( ( ini_get( 'zlib.output_compression' ) == 'On' || ini_get( 'zlib.output_compression_level' ) > 0 ) || ini_get( 'output_handler' ) == 'ob_gzhandler' ) {
            return false;
        }

        if ( extension_loaded( 'zlib' ) ) {
            ob_start( 'ob_gzhandler' );
        }

    }

    public function cookie_note() {

        $left_column = $this->options['cookie_hint_left_column_width'];
        if ( $left_column != '100' ) {
            $right_column = 100 - $left_column;
        }

        $cookie_hint_wrapper_class = ( isset( $this->options['cookie_hint_wrapper_class'] ) ) ? $this->options['cookie_hint_wrapper_class'] : '';
        $cookie_hint_inner_wrapper_class = ( isset( $this->options['cookie_hint_inner_wrapper_class'] ) ) ? $this->options['cookie_hint_inner_wrapper_class'] : '';

        $cookie_hint_button = '';
        $cookie_hint_content_left = '';
        $cookie_hint_content_right = '';

        if ( isset( $this->options['cookie_hint_content_from_translation'] ) && $this->options['cookie_hint_content_from_translation'] != 'no' ) {
            $left_column = '100';
            $cookie_hint_content_left = esc_html__( 'In order to optimize our website for you and to be able to continuously improve it, we use cookies. By continuing to use the website, you agree to the use of cookies.', 'exopite-seo-core' );
            $cookie_hint_button = esc_html__( 'OK', 'exopite-seo-core' );
            $cookie_hint_content_left .= ' ' . '<a href="/' . esc_html_x( 'privacy-policy', 'Relative permalink slug', 'exopite-seo-core' ) . '/">' . esc_html__( 'More information', 'exopite-seo-core' ) . '</a>';

        } else {
            $cookie_hint_content_left = ( isset( $this->options['cookie_hint_content_left'] ) && ! empty( $this->options['cookie_hint_content_left'] ) ) ? $this->options['cookie_hint_content_left'] : '';
            $cookie_hint_button = ( isset( $this->options['cookie_hint_button'] ) && ! empty( $this->options['cookie_hint_button'] ) ) ? $this->options['cookie_hint_button'] : '';
        }

        ?>
        <div class="cookie-container">
            <div class="cookie-wrapper-container clearfix <?php echo $cookie_hint_wrapper_class; ?>">
                <div class="<?php echo $cookie_hint_inner_wrapper_class; ?>">
                    <div class="cookie-column " style="width:<?php echo $left_column; ?>%;">
                        <div class="cookie-column-inner cookie-text">
                            <?php

                            echo $cookie_hint_content_left;

                            if ( $left_column == '100' ) :
                            ?>
                            <span class="accept-cookies accept-cookies-js cookie-btn"><?php echo $cookie_hint_button; ?></span>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <?php if ( $left_column != '100' ) : ?>
                        <div class="cookie-column" style="text-align: right;width:<?php echo $right_column; ?>%;">
                            <div class="cookie-column-inner">
                                <?php echo $cookie_hint_content_right; ?>
                                <span class="accept-cookies accept-cookies-js cookie-btn"><?php echo $cookie_hint_button; ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ( isset( $this->options['cookie_hint_accept_footer_links'] ) && $this->options['cookie_hint_accept_footer_links'] != null && is_array( $this->options['cookie_hint_accept_footer_links'] ) ) : ?>
            <div class="cookie-container-footer">
                <div class="cookie-wrapper-container-footer clearfix <?php echo $cookie_hint_wrapper_class; ?>">
                    <div class="cookie-column-footer <?php echo $cookie_hint_inner_wrapper_class; ?>">
                    <?php

                    $links = array();

                    foreach ( $this->options['cookie_hint_accept_footer_links'] as $page_id ) :

                        $links[] = '<a href="' . get_the_permalink( $page_id ) . '">' . get_the_title( $page_id ) . '</a>';

                    endforeach;

                    echo implode( ' | ', $links );

                    ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php

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

    public function google_analytics_head() {

        /**
         * CHECK AND SET ANONYMIZE IP
         * @link https://checkgoogleanalytics.psi.uni-bamberg.de/howto/#enable
         */

        ?>
        <!-- Google Analytics -->
        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', '<?php echo $this->options['google_analytics_id']; ?>', 'auto');
        <?php // Enable anonymization. Must be placed before ga ('send', 'pageview'). ?>
        ga('set', 'anonymizeIp', true);
        <?php do_action( 'exopite_seo_core_additional_ga_code', $this->options['google_analytics_id'] ); ?>
        ga('send', 'pageview');
        </script>
        <!-- End Google Analytics -->
        <?php

    }

    public function google_analytics_head_gtag() {

        /**
         * MIGRATION
         * @link https://developers.google.com/analytics/devguides/collection/gtagjs/migration
         */

        ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->options['google_analytics_id']; ?>"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        <?php do_action( 'exopite_seo_core_additional_gtag_code', $this->options['google_analytics_id_gtag'] ); ?>
        gtag('config', '<?php echo $this->options['google_analytics_id_gtag']; ?>', { 'anonymize_ip': true });
        </script>
        <?php

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

    function body_class( $classes ) {

        // solution is based on the code of Yaniv Friedensohn
        // http://www.affectivia.com/blog/placing-the-google-tag-manager-in-wordpress-after-the-body-tag/
        // and Plugin Name: Google Tag Manager for Wordpress
        // https://duracelltomi.com/google-tag-manager-for-wordpress/
        // https://duracelltomi.com/
        $classes[] = '"><!-- Google Tag Manager (noscript) --><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $this->options['google_analytics_id'] . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><!-- End Google Tag Manager (noscript) --><br style="display:none;';

        return $classes;

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

    /**
     * For demonstration purposes.
     */

    /**
     * Deal with special chars.
     *
     * @link https://stackoverflow.com/questions/3371697/replacing-accented-characters-php/33856250#33856250
     *
     * "-" => "ъьЪЬ",
     * "A" => "АĂǍĄÀÃÁÆÂÅǺĀא",
     * "B" => "БבÞ",
     * "C" => "ĈĆÇЦצĊČ©ץ",
     * "D" => "ДĎĐדÐ",
     * "E" => "ÈĘÉËÊЕĒĖĚĔЄƏע",
     * "F" => "ФƑ",
     * "G" => "ĞĠĢĜГגҐ",
     * "H" => "חĦХĤה",
     * "I" => "IÏÎÍÌĮĬIИĨǏיЇĪІ",
     * "J" => "ЙĴ",
     * "K" => "ĸכĶКך",
     * "L" => "ŁĿЛĻĹĽל",
     * "M" => "מМם",
     * "N" => "ÑŃНŅןŊנŉŇ",
     * "O" => "ØÓÒÔÕОŐŎŌǾǑƠ",
     * "P" => "פףП",
     * "Q" => "ק",
     * "R" => "ŔŘŖרР®",
     * "S" => "ŞŚȘŠСŜס",
     * "T" => "ТȚטŦתŤŢ",
     * "U" => "ÙÛÚŪУŨƯǓŲŬŮŰǕǛǙǗ",
     * "V" => "Вו",
     * "Y" => "ÝЫŶŸ",
     * "Z" => "ŹŽŻЗזS",
     * "a" => "аăǎąàãáæâåǻāא",
     * "b" => "бבþ",
     * "c" => "ĉćçцצċč©ץ",
     * "ch" => "ч",
     * "d" => "дďđדð",
     * "e" => "èęéëêеēėěĕєəע",
     * "f" => "фƒ",
     * "g" => "ğġģĝгגґ",
     * "h" => "חħхĥה",
     * "i" => "iïîíìįĭıиĩǐיїīі",
     * "j" => "йĵ",
     * "k" => "ĸכķкך",
     * "l" => "łŀлļĺľל",
     * "m" => "מмם",
     * "n" => "ñńнņןŋנŉň",
     * "o" => "øóòôõоőŏōǿǒơ",
     * "p" => "פףп",
     * "q" => "ק",
     * "r" => "ŕřŗרр®",
     * "s" => "şśșšсŝס",
     * "t" => "тțטŧתťţ",
     * "u" => "ùûúūуũưǔųŭůűǖǜǚǘ",
     * "v" => "вו",
     * "y" => "ýыŷÿ",
     * "z" => "źžżзזſ",
     * "tm" => "™",
     * "at" => "@",
     * "ae" => "ÄǼäæǽ",
     * "ch" => "Чч",
     * "ij" => "ĳĲ",
     * "j" => "йЙĴĵ",
     * "ja" => "яЯ",
     * "je" => "Ээ",
     * "jo" => "ёЁ",
     * "ju" => "юЮ",
     * "oe" => "œŒöÖ",
     * "sch" => "щЩ",
     * "sh" => "шШ",
     * "ss" => "ß",
     * "tm" => "™",
     * "ue" => "Ü",
     * "zh" => "Жж"
     */

    /**
     * Replace language-specific characters by ASCII-equivalents.
     *
     * @param string $s
     * @return string
     *
     * @link https://stackoverflow.com/questions/3371697/replacing-accented-characters-php/24984010#24984010
     */
    public static function normalize_chars( $string ) {

        $replace = array(
            '&amp;' => 'and', '@' => 'at', '©' => 'copy', '®' => 'r', 'À' => 'a',
            'Á' => 'a', 'Â' => 'a', 'Ä' => 'ae', 'Å' => 'a', 'Æ' => 'ae','Ç' => 'c',
            'È' => 'e', 'É' => 'e', 'Ë' => 'e', 'Ì' => 'i', 'Í' => 'i', 'Î' => 'i',
            'Ï' => 'i', 'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'oe',
            'Ø' => 'o', 'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'ue', 'Ý' => 'y',
            'ß' => 'ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'ae', 'å' => 'a',
            'æ' => 'ae','ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'oe', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ü' => 'ue', 'ý' => 'y', 'þ' => 'p', 'ÿ' => 'y', 'Ā' => 'a',
            'ā' => 'a', 'Ă' => 'a', 'ă' => 'a', 'Ą' => 'a', 'ą' => 'a', 'Ć' => 'c',
            'ć' => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Č' => 'c',
            'č' => 'c', 'Ď' => 'd', 'ď' => 'd', 'Đ' => 'd', 'đ' => 'd', 'Ē' => 'e',
            'ē' => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ę' => 'e',
            'ę' => 'e', 'Ě' => 'e', 'ě' => 'e', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ğ' => 'g',
            'ğ' => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g', 'Ĥ' => 'h',
            'ĥ' => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ĩ' => 'i', 'ĩ' => 'i', 'Ī' => 'i',
            'ī' => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Į' => 'i', 'į' => 'i', 'İ' => 'i',
            'ı' => 'i', 'Ĳ' => 'ij','ĳ' => 'ij','Ĵ' => 'j', 'ĵ' => 'j', 'Ķ' => 'k',
            'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'l', 'ĺ' => 'l', 'Ļ' => 'l', 'ļ' => 'l',
            'Ľ' => 'l', 'ľ' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l', 'Ł' => 'l', 'ł' => 'l',
            'Ń' => 'n', 'ń' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ň' => 'n', 'ň' => 'n',
            'ŉ' => 'n', 'Ŋ' => 'n', 'ŋ' => 'n', 'Ō' => 'o', 'ō' => 'o', 'Ŏ' => 'o',
            'ŏ' => 'o', 'Ő' => 'o', 'ő' => 'o', 'Œ' => 'oe','œ' => 'oe','Ŕ' => 'r',
            'ŕ' => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'Ś' => 's',
            'ś' => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Ş' => 's', 'ş' => 's', 'Š' => 's',
            'š' => 's', 'Ţ' => 't', 'ţ' => 't', 'Ť' => 't', 'ť' => 't', 'Ŧ' => 't',
            'ŧ' => 't', 'Ũ' => 'u', 'ũ' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ŭ' => 'u',
            'ŭ' => 'u', 'Ů' => 'u', 'ů' => 'u', 'Ű' => 'u', 'ű' => 'u', 'Ų' => 'u',
            'ų' => 'u', 'Ŵ' => 'w', 'ŵ' => 'w', 'Ŷ' => 'y', 'ŷ' => 'y', 'Ÿ' => 'y',
            'Ź' => 'z', 'ź' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ž' => 'z', 'ž' => 'z',
            'ſ' => 'z', 'Ə' => 'e', 'ƒ' => 'f', 'Ơ' => 'o', 'ơ' => 'o', 'Ư' => 'u',
            'ư' => 'u', 'Ǎ' => 'a', 'ǎ' => 'a', 'Ǐ' => 'i', 'ǐ' => 'i', 'Ǒ' => 'o',
            'ǒ' => 'o', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ǘ' => 'u',
            'ǘ' => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǻ' => 'a',
            'ǻ' => 'a', 'Ǽ' => 'ae','ǽ' => 'ae','Ǿ' => 'o', 'ǿ' => 'o', 'ə' => 'e',
            'Ё' => 'jo','Є' => 'e', 'І' => 'i', 'Ї' => 'i', 'А' => 'a', 'Б' => 'b',
            'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ж' => 'zh','З' => 'z',
            'И' => 'i', 'Й' => 'j', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',
            'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
            'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch','Ш' => 'sh','Щ' => 'sch',
            'Ъ' => '-', 'Ы' => 'y', 'Ь' => '-', 'Э' => 'je','Ю' => 'ju','Я' => 'ja',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ж' => 'zh','з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh','щ' => 'sch','ъ' => '-','ы' => 'y', 'ь' => '-', 'э' => 'je',
            'ю' => 'ju','я' => 'ja','ё' => 'jo','є' => 'e', 'і' => 'i', 'ї' => 'i',
            'Ґ' => 'g', 'ґ' => 'g', 'א' => 'a', 'ב' => 'b', 'ג' => 'g', 'ד' => 'd',
            'ה' => 'h', 'ו' => 'v', 'ז' => 'z', 'ח' => 'h', 'ט' => 't', 'י' => 'i',
            'ך' => 'k', 'כ' => 'k', 'ל' => 'l', 'ם' => 'm', 'מ' => 'm', 'ן' => 'n',
            'נ' => 'n', 'ס' => 's', 'ע' => 'e', 'ף' => 'p', 'פ' => 'p', 'ץ' => 'C',
            'צ' => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't', '™' => 'tm',
            '№' => 'No', 'º' => 'o', 'ª' => 'a', '℗' => 'P', '™' => 'tm', '℠' => 'sm',
            '’' => '', '_' => '-', '%20' => '-', '€'=>'Euro', 'Ã' => 'A', 'ã' => 'a',
            'Ñ' => 'N', 'ð' => 'o', 'ñ' => 'n', 'ș' => 's', 'Ș' => 'S', 'ț' => 't',
            'Ț' => 'T',
        );

        return strtr( $string, $replace );
    }

    /**
     * END - For demonstration purposes.
     */

	/**
	 * Fix and sanitize some special cases.
	 *
	 * @link https://github.com/salcode/fe-sanitize-title-js/issues/1
	 */
	public function special_replace_chars( $string  ) {

        $replace = array(
            'Ä' => 'ae',
            'ä' => 'ae',
            'Ö' => 'oe',
            'ö' => 'oe',
            'Ü' => 'ue',
            'ü' => 'ue',
            'ß' => 'ss',
            '€' => 'euro',
            '@' => 'at',
            '%20' => '-',
            '©' => 'copy',
            '&amp;' => 'and',
            '℠' => 'sm',
            '™' => 'tm',
            '№' => 'No',
        );

        return strtr( $string, $replace );

    }

	public function sanitize_file_name( $filename_sanitized, $filename_raw ) {

		// Get file parts.
		$pathinfo = pathinfo( $filename_raw );
		$ext = $pathinfo['extension'];
		$filename = $pathinfo['filename'];

        /**
         * Replace language-specific characters by ASCII-equivalents.
         * Converts all accent characters to ASCII characters.
         */
        $filename = $this->normalize_chars( $filename );

        /**
         * Remove special chars, sanitize_title does not this (or at least not all).
         * Replace "speacial" chars without remove accents.
         */
        // $filename = $this->special_replace_chars( $filename );

        $filename = str_replace( '_', '-', $filename );

        $filename = remove_accents( $filename );

		/**
		 * Sanitize filename.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/sanitize_title
		 *
		 * "Despite the name of this function, the returned value is intended to be
		 * suitable for use in a URL, not as a human-readable title."
		 */
		$filename = sanitize_title( $filename );

        /**
         * Removes all non-ascii characters.
         *
         * @link https://ca.wordpress.org/plugins/wp-sanitize-accented-uploads
         */
        $filename = preg_replace("/[^(\x20-\x7F)]*/", "", $filename );

		return $filename . '.' . $ext;

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

    public function breadcrumbs() {


        $divider = ' '. apply_filters( 'exopite-breadcrumbs-divider', '<span class="divider">&#187;</span>' ) . ' ';
        $home =  esc_attr__( 'Home', 'exopite-seo-core' );
        $breadcrumb = '<div class="exopite-breadcrumbs">' . apply_filters( 'exopite-breadcrumbs-before', '' );

        if ( class_exists( 'WooCommerce' ) ) {
            $shop_page_id = wc_get_page_id( 'shop' );
            $woocommerce_shop_title   = get_the_title( $shop_page_id );
        }

        if ( ! is_front_page() ) {

            $breadcrumb .= '<a href="' . get_option('home') .'">' . apply_filters( 'exopite-breadcrumbs-home-name', $home ) . '</a>' . $divider;

            if ( is_category() || ( is_single() && "post" == get_post_type() ) ) {

                $categories = get_the_category();
                $last_category = key( array_slice( $categories, -1, 1, TRUE ) );
                $category_divider = apply_filters( 'exopite-breadcrumbs-category-divider', '&' ) . ' ';

                // Category parents
                if( is_category() ) {
                    $category_id = get_query_var('cat');
                } else {
                    $category_id = $categories[0]->cat_ID;
                }

                $category_parents = explode( ';', rtrim( get_category_parents( $category_id, true, ';' ),';' ) );

                $i = 0;
                $len = count( $category_parents );

                foreach( $category_parents as $category_parent ) {

                    if ( $i == $len - 1 ) {

                        if ( is_category() ) {

                            $breadcrumb .= strip_tags( $category_parent );

                        } else {

                            $breadcrumb .= $category_parent;

                        }

                    } else {

                        $breadcrumb .= $category_parent . $divider;

                    }

                    $i++;

                }

                if ( class_exists( 'WooCommerce' ) && is_product() ) {
                    $breadcrumb .= '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . $woocommerce_shop_title . '</a>';
                }

                if ( empty( $categories ) && ( class_exists( 'WooCommerce' ) && ! is_product() ) ) {

                    $post_type = get_post_type_object( get_post_type() );

                    if ( $post_type->has_archive ) {

                        $breadcrumb .= '<a href="' . get_site_url() . '/' . $post_type->rewrite['slug'] . '/">' . $post_type->labels->name . '</a>';

                    } else {

                        $breadcrumb .= $post_type->labels->name;

                    }

                }
                if ( is_single() ) {

                    $breadcrumb .= $divider;
                    $breadcrumb .= get_the_title();

                }

            }  elseif ( is_single() && ( 'post' != get_post_type() && 'page' != get_post_type() ) ) {

                $obj = get_post_type_object( get_post_type() );
                $cpt_archive = get_post_type_archive_link( $obj->name );
                $breadcrumb .= ( empty( $cpt_archive ) ) ? $obj->labels->singular_name : '<a href="' . $obj->slug . '">' . $obj->labels->singular_name . '</a>';
                $breadcrumb .= $divider;
                $breadcrumb .= get_the_title();

            }  elseif ( is_tag() ) {

                $breadcrumb .= single_tag_title( '', false );

            } elseif ( is_day() || is_month() || is_year() ) {

                $breadcrumb .= esc_attr__( 'Archive for ', 'exopite-seo-core' );

                if ( is_day() ) {

                    $breadcrumb .= get_the_time('F jS, Y');

                } elseif ( is_month() ) {

                    $breadcrumb .= get_the_time('F, Y');

                } elseif ( is_year() ) {

                    $breadcrumb .= get_the_time('Y');

                }

            } elseif ( is_author() ) {

                $author = get_userdata( get_query_var('author') );
                $breadcrumb .= esc_attr__( 'Author ', 'exopite-seo-core' ) . $author->display_name;

            } elseif ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {

                $breadcrumb .= esc_attr__( 'Blog Archives', 'exopite-seo-core' );

            } elseif ( is_search() ) {

                $breadcrumb .= esc_attr__( 'Search results for ', 'exopite-seo-core' ) . get_search_query();

            } elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {

                $breadcrumb .= $woocommerce_shop_title;

            } elseif ( is_tax() ) {


                if ( class_exists( 'WooCommerce' ) ) {

                    $breadcrumb .= '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . $woocommerce_shop_title . '</a>' . $divider;
                }

                $breadcrumb .= single_term_title( '', false );

            } elseif ( is_home() ) {

                $breadcrumb .= get_the_title( get_option('page_for_posts', true) );

            } else {

                // e.g. Page
                $post_parents_id = array_reverse( get_post_ancestors( get_the_ID() ) );

                foreach ( $post_parents_id as $post_parent_id ) {

                    $breadcrumb .= '<a href="' . get_permalink( $post_parent_id ) . '">' . get_the_title( $post_parent_id ) . '</a>' . $divider;

                }

                if ( class_exists( 'WooCommerce' ) && is_cart() ) {

                    $breadcrumb .= '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . $woocommerce_shop_title . '</a>' . $divider;

                }

                $breadcrumb .= get_the_title();

            }

        } else {

            $breadcrumb .= $home;
        }

        $breadcrumb .=  apply_filters( 'exopite-breadcrumbs-after', '' ) . '</div>';

        return $breadcrumb;

    }



}

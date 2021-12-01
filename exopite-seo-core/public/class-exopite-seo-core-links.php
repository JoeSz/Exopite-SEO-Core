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
class Exopite_Seo_Core_Links {

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

    public $include;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $include ) {

        $this->include = $include;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->options = get_exopite_sof_option( $this->plugin_name );

	}

    public function buffer_start() {

        // Start output buffering with a callback function
        add_filter( 'exopite_ob_status', 'on' );
        ob_start( array( $this, 'process_buffer' ) );

    }

    public function buffer_end() {

        // Display buffer
        if ( ob_get_length() ) ob_end_flush();

    }

    public function process_buffer( $content ) {

        return apply_filters( 'exopite_ob_content', $content );

    }

    //https://stackoverflow.com/questions/5266945/wordpress-how-detect-if-current-page-is-the-login-page/5892694#5892694
    public function is_login_page() {

        return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );

    }

    public function is_external_url( $url ) {

        // Abort if parameter URL is empty
        if( empty($url) ) {
            return true;
        }

        // Parse home URL and parameter URL
        $link_url = parse_url( $url );
        // $home_url = parse_url( $_SERVER['HTTP_HOST'] );
        $home_url = parse_url( home_url() );  // Works for WordPress

        // Decide on target
        if( empty( $link_url['host'] ) ||  $link_url['host'] == $home_url['host'] ) {
            return false;
        }

        return true;

    }


    public function add_links_attributes( $content ) {

        // $test = array( 'test' );

        $html = new simple_html_dom();

        // Load HTML from a string/variable
        $html->load( $content, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT );


        // Find all links
        foreach( $html->find('a') as $element ) {

            $href = $element->href;
            $innertext = strip_tags( $element->innertext );
            $title = $element->title;

            $debug = '';

            $rel = $element->rel;
            if ( ! empty( $rel ) ) {
                $rels = explode( ' ', $rel );
            } else {
                $rels = array();
            }

            if ( $href && $this->is_external_url( $href ) ) {

                $links_nofollow = ( isset( $this->options['links_nofollow'] ) ) ? $this->options['links_nofollow'] : 'no';
                $links_noopener_noreferer = ( isset( $this->options['links_noopener_noreferer'] ) ) ? $this->options['links_noopener_noreferer'] : 'no';

                if( $links_nofollow == 'yes' ) {

                    if ( ! in_array( 'nofollow', $rels ) ) {
                        $rels[] = 'nofollow';
                    }

                }

                if( $links_noopener_noreferer == 'yes' ) {

                    $target = $element->target;

                    if ( $target == '_blank' ) {

                        if ( ! in_array( 'noopener', $rels ) ) {
                            $rels[] = 'noopener';
                        }
                        if ( ! in_array( 'noreferrer', $rels ) ) {
                            $rels[] = 'noreferrer';
                        }

                    }

                }

            }

            $links_set_title = 'yes';
            if( $links_set_title == 'yes' ) {

                if ( ( empty( $title ) || ! $title ) && ! empty( $innertext ) ) {

                    $element->title = $innertext;

                }

            }

            if ( ! empty( $rels ) ) {
                $element->rel = implode( ' ', $rels );
            }

            // $test[] = array(
            //     'href' => $href,
            //     'target' => $target,
            //     'rel' => $rel,
            //     'title' => $title,
            //     'title' => $element->title,
            //     'htmlspec inner' => htmlspecialchars( $element->innertext ),
            //     'striptags inner' => $innertext,
            //     'debug' => $debug,
            // );


        }

        $content = $html->save();

        $html->clear();
        unset( $html );

        /*
        $doc = new DOMDocument();
        @$doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

        $links = $doc->getElementsByTagName('a');

        foreach ( $links as $link ) {
            $href = $link->getAttribute('href');

            if ( $this->is_external_url( $href ) ) {

                $rel = $link->getAttribute('rel');
                $rels = array();

                $links_nofollow = ( isset( $this->options['links_nofollow'] ) ) ? $this->options['links_nofollow'] : 'no';
                $links_noopener_noreferer = ( isset( $this->options['links_noopener_noreferer'] ) ) ? $this->options['links_noopener_noreferer'] : 'no';

                if( $links_nofollow == 'yes' ) {

                    if ( ! empty( $rel ) ) $rels = explode( ' ', $rel );

                    if ( ! in_array( 'nofollow', $rels ) ) {
                        $rels[] = 'nofollow';
                    }


                }

                if( $links_noopener_noreferer == 'yes' ) {

                    $target = $link->getAttribute('target');

                    if ( $target == '_blank' ) {

                        if ( ! in_array( 'noopener', $rels ) ) {
                            $rels[] = 'noopener';
                        }
                        if ( ! in_array( 'noreferrer', $rels ) ) {
                            $rels[] = 'noreferrer';
                        }

                    }

                }

                $rel = implode( ' ', $rels );

                $link->setAttribute( 'rel', $rel );

            }

        }

        $content = $doc->saveHTML();

        return $content;
        */

        return $content;

        // return $content . 'TEST<pre>X' . var_export( $test, true ) . '</pre>';
    }

    /**
     * 0.01s (localhost, real server will be faster
     */
    public function process_html( $content ) {

        if ( $this->include->is_api_request() ) {
            return $content;
        }

        // $startTime = microtime(true);

        if (is_admin() || $this->is_login_page()) {
            return $content;
        }

        $already_rant = apply_filters( 'exopite_seo_core_rant', false );
        if ( $already_rant == true ) {
            return $content;
        }

        add_filter( 'exopite_seo_core_rant', '__return_true' );

        return $this->add_links_attributes( $content );
        // return $this->add_links_rel( $content ) .  number_format( (microtime(true) - $startTime ), 4 ) . " Seconds\n";


    }

}

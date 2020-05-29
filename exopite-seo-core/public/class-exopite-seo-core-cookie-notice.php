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
class Exopite_Seo_Core_Cookie_Notice {

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

}

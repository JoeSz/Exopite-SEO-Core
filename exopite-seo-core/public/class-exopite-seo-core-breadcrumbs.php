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
class Exopite_Seo_Core_Breadcrumbs {

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

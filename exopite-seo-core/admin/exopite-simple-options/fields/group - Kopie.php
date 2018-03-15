<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_group' ) ) {
  class Exopite_Simple_Options_Framework_Field_group extends Exopite_Simple_Options_Framework_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();

      $unallows    = array( 'wysiwyg', 'group', 'repeater' );
      $limit       = ( ! empty( $this->field['limit'] ) ) ? $this->field['limit'] : 0;
      $fields      = array_values( $this->field['fields'] );
      $acc_title   = ( isset( $this->field['accordion_title'] ) ) ? $this->field['accordion_title'] : __( 'Adding', 'csf' );
      $field_title = ( isset( $fields[0]['title'] ) ) ? $fields[0]['title'] : $fields[1]['title'];
      $field_id    = ( isset( $fields[0]['id'] ) ) ? $fields[0]['id'] : $fields[1]['id'];
      $unique_id   = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];
      $search_id   = $this->csf_array_search( $fields, 'id', $acc_title );

      if( ! empty( $search_id ) ) {
        $acc_title = ( isset( $search_id[0]['title'] ) ) ? $search_id[0]['title'] : $acc_title;
        $field_id  = ( isset( $search_id[0]['id'] ) ) ? $search_id[0]['id'] : $field_id;
      }

      echo '<div class="csf-cloneable-item csf-cloneable-hidden csf-no-script">';

        echo '<div class="csf-cloneable-helper">';
        echo '<i class="csf-cloneable-pending fa fa-circle"></i>';
        echo '<i class="csf-cloneable-clone fa fa-clone"></i>';
        echo '<i class="csf-cloneable-remove fa fa-times"></i>';
        echo '</div>';

        echo '<h4 class="csf-cloneable-title"><span class="csf-cloneable-text">'. $acc_title .'</span></h4>';
        echo '<div class="csf-cloneable-content">';
        foreach ( $fields as $field ) {

          if( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

          $field['sub'] = true;
          $field['wrap_class'] = ( ! empty( $field['wrap_class'] ) ) ? $field['wrap_class'] .' csf-no-script' : 'csf-no-script';

          $unique = ( ! empty( $this->unique ) ) ? '_nonce['. $this->field['id'] .'][num]' : '_nonce[num]';
          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

          echo csf_add_field( $field, $field_default, $unique, 'field/group' );

        }
        echo '<div class="csf-field csf-text-right"><a href="#" class="button csf-warning-primary csf-cloneable-remove">'. __( 'Remove', 'csf' ) .'</a></div>';
        echo '</div>';

      echo '</div>';

      echo '<div class="csf-cloneable-wrapper">';

        if( ! empty( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            $title = ( isset( $this->value[$key][$field_id] ) ) ? $this->value[$key][$field_id] : '';

            if ( is_array( $title ) && isset( $this->multilang ) ) {
              $lang  = csf_language_defaults();
              $title = $title[$lang['current']];
              $title = is_array( $title ) ? $title[0] : $title;
            }

            $field_title = ( ! empty( $search_id ) ) ? $acc_title : $field_title;

            echo '<div class="csf-cloneable-item">';

            echo '<div class="csf-cloneable-helper">';
            echo '<i class="csf-cloneable-pending fa fa-circle"></i>';
            echo '<i class="csf-cloneable-clone fa fa-clone"></i>';
            echo '<i class="csf-cloneable-remove fa fa-times"></i>';
            echo '</div>';

            echo '<h4 class="csf-cloneable-title"><span class="csf-cloneable-text">'. $field_title .': '. $title .'</span></h4>';

            echo '<div class="csf-cloneable-content">';

            foreach ( $fields as $field ) {

              if( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

              $field['sub'] = true;
              $field['wrap_class'] = ( ! empty( $field['wrap_class'] ) ) ? $field['wrap_class'] .' csf-no-script' : 'csf-no-script';

              $unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              echo csf_add_field( $field, $value, $unique, 'field/group' );
            }

            echo '<div class="csf-field csf-text-right"><a href="#" class="button csf-warning-primary csf-cloneable-remove">'. __( 'Remove', 'csf' ) .'</a></div>';
            echo '</div>';
            echo '</div>';

            $num++;

          }

        }

      echo '</div>';

      echo '<div class="csf-cloneable-data" data-unique-id="'. $unique_id .'" data-limit="'. $limit .'">'. __( 'You can not add more than', 'csf' ) .' '. $limit .'</div>';

      echo '<a href="#" class="button button-primary csf-cloneable-add">'. $this->field['button_title'] .'</a>';

      echo $this->element_after();

    }

    /**
     *
     * Array search key & value
     *
     * @since 1.0.0
     * @version 1.0.0
     *
     */
    if ( ! function_exists( 'csf_array_search' ) ) {
      function csf_array_search( $array, $key, $value ) {

        $results = array();

        if ( is_array( $array ) ) {
          if ( isset( $array[$key] ) && $array[$key] == $value ) {
            $results[] = $array;
          }

          foreach ( $array as $sub_array ) {
            $results = array_merge( $results, csf_array_search( $sub_array, $key, $value ) );
          }

        }

        return $results;

      }
    }

  }
}

<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  // Page intro fields
  $page_intro = new_cmb2_box([
    'id'            => $prefix . 'page_intro',
    'title'         => esc_html__( 'Page Intro', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Title', 'cmb2' ),
    'id'   => $prefix .'intro_title',
    'type' => 'text',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Quote', 'cmb2' ),
    'id'   => $prefix .'intro_quote',
    'type' => 'textarea_small',
  ]);

  // Parent navigation fields
  $parent_page_navigation = new_cmb2_box([
    'id'            => 'landing_page_navigation',
    'title'         => __( 'Landing Page Navigation', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'side',
    'priority'      => 'low',
    'closed'        => true,
    'show_on_cb'    => '\Firebelly\CMB2\cmb_is_child_page',
  ]);
  $parent_page_navigation->add_field([
    'name' => esc_html__( 'Nav Excerpt', 'cmb2' ),
    'id'   => $prefix .'nav_excerpt',
    'type' => 'textarea_small',
    'desc' => 'These are shown on the parent page\'s secondary navigation.',
  ]);
  $parent_page_navigation->add_field([
    'name' => esc_html__( 'Nav Button Text', 'cmb2' ),
    'id'   => $prefix .'nav_button_text',
    'type' => 'text',
    'desc' => 'e.g. Join the Movement',
  ]);

  // Homepage fields
  $homepage_fields = new_cmb2_box([
    'id'            => 'secondary_content',
    'title'         => __( 'Custom Featured Block', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'front-page.php'],
    'priority'      => 'high',
    'show_names'    => true,
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Image', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_image',
    'type' => 'file',
    'options' => [
      'url' => false, // Hide the text input for the url
    ],
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Title', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_title',
    'type' => 'text',
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Body', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_body',
    'type' => 'wysiwyg',
    'options' => [
      'textarea_rows' => 8,
    ],
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Link', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_link',
    'type' => 'text_url',
    'desc' => 'e.g. http://foo.com/',
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Link Text', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_link_text',
    'type' => 'text_medium',
    'desc' => 'e.g. Support Us'
  ]);
}


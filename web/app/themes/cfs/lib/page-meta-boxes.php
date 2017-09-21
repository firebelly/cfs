<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_';

  $meta_boxes['page_intro'] = array(
    'id'            => 'page_intro',
    'title'         => __( 'Page Intro', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
    'description' => __( 'Title + Quote shown in the intro section of pages', 'cmb2' ),
    'fields'        => [
      [
        'name' => 'Intro Title',
        'id'   => $prefix .'intro_title',
        'type' => 'textarea_small',
      ],
      [
        'name' => 'Intro Quote',
        'id'   => $prefix .'intro_quote',
        'type' => 'textarea_small',
      ],
    ],
  );

  $meta_boxes['secondary_content'] = array(
    'id'            => 'secondary_content',
    'title'         => __( 'Secondary Page Content', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => false,
    'fields'        => [
      [
        'name' => 'Secondary Page Content',
        'desc' => 'The second set of main content on a page',
        'id'   => $prefix . 'secondary_content',
        'type' => 'wysiwyg',
      ],
    ],
  );

  // Homepage fields
  $meta_boxes['secondary_content'] = array(
    'id'            => 'secondary_content',
    'title'         => __( 'Custom Featured Block', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'front-page.php'],
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => [
      [
        'name' => 'Custom Featured Image',
        'id'   => $prefix . 'custom_featured_image',
        'type' => 'file',
        'options' => [
          'url' => false, // Hide the text input for the url
        ],
      ],
      [
        'name' => 'Custom Featured Title',
        'id'   => $prefix . 'custom_featured_title',
        'type' => 'text',
      ],
      [
        'name' => 'Custom Featured Body',
        'id'   => $prefix . 'custom_featured_body',
        'type' => 'wysiwyg',
        'options' => [
          'textarea_rows' => 8,
        ],
      ],
      [
        'name' => 'Custom Featured Link',
        'id'   => $prefix . 'custom_featured_link',
        'type' => 'text_url',
        'desc' => 'e.g. http://foo.com/',
      ],
    ],
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );
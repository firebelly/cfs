<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

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
        'type' => 'text',
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
    'object_types'  => array( 'page', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => false,
    'fields'        => array(
      array(
        'name' => 'Secondary Page Content',
        'desc' => 'The second set of main content on a page',
        'id'   => $prefix . 'secondary_content',
        'type' => 'wysiwyg',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );
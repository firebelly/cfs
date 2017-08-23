<?php
/**
 * Person post type
 */

namespace Firebelly\PostTypes\Person;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes

$persons = new PostType(['name' => 'person', 'plural' => 'People'], [
  'taxonomies' => ['person_category'],
  'supports'   => ['title', 'editor', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);
$persons->taxonomy([
  'name'     => 'person_category',
  'plural'   => 'Person Categories',
]);

/**
 * CMB2 custom fields
 */
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['person_info'] = array(
    'id'            => 'person_info',
    'title'         => __( 'Person Info', 'cmb2' ),
    'object_types'  => ['person'],
    'context'       => 'normal',
    'priority'      => 'high',
    'fields'        => array(
      [
        'name'      => 'Title',
        'id'        => $prefix . 'person_title',
        'type'      => 'text_medium',
        'desc'      => 'e.g. 20xx Freedom Fellow',
      ],
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

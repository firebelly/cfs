<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['secondary_featured_image'] = array(
    'id'            => 'secondary_featured_image',
    'title'         => __( 'Secondary Featured Image', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => false, // Show field names on the left
    'fields'        => array(

      array(
        'name' => 'Secondary Featured Image',
        'desc' => 'The black and white image that appears in the second section of most pages',
        'id'   => $prefix . 'secondary_featured_image',
        'type' => 'file',
      ),
    ),
  );

  $meta_boxes['featured_video'] = array(
    'id'            => 'featured_video',
    'title'         => __( 'Featured Video', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'context'       => 'side',
    'priority'      => 'low',
    'show_names'    => false, // Show field names on the left
    'fields'        => array(

      array(
        'name' => 'Featured Video',
        'desc' => 'If set, replaces the featured image in page header. Must be MP4 video.',
        'id'   => $prefix . 'featured_video',
        'type' => 'file',
        'options' => array(
          'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
          'add_upload_file_text' => 'Select or Upload Video'
        ),
        // query_args are passed to wp.media's library query
        'query_args' => array(
          'type' => 'video/mp4',
        ),
      ),
    ),
  );

  $meta_boxes['secondary_content'] = array(
    'id'            => 'secondary_content',
    'title'         => __( 'Secondary Page Content', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => false, // Show field names on the left
    'fields'        => array(

      array(
        'name' => 'Secondary Page Content',
        'desc' => 'The second set of main content on a page',
        'id'   => $prefix . 'secondary_content',
        'type' => 'wysiwyg',
      ),
    ),
  );

  // $meta_boxes['program_sessions'] = array(
  //   'id'            => 'program_sessions',
  //   'title'         => __( 'Program Sessions Text', 'cmb2' ),
  //   'object_types'  => array( 'page', ), // Post type
  //   'context'       => 'normal',
  //   'show_on'       => array( 'key' => 'page-template', 'value' => 'templates/program-type.php'),
  //   'priority'      => 'high',
  //   'show_names'    => false, // Show field names on the left
  //   'fields'        => array(

  //     array(
  //       'name' => 'Program Sessions Text',
  //       'desc' => 'The text to introduce the sessions. Ex: "Upcoming sessions", "Current sessions"<br> Default text is "Current sessions".',
  //       'id'   => $prefix . 'program_sessions_text',
  //       'type' => 'text',
  //     ),
  //   ),
  // );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );
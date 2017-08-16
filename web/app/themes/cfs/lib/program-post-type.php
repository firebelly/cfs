<?php
/**
 * Program post type
 */

namespace Firebelly\PostTypes\Program;

// Custom image size for post type?
// add_image_size( 'program-thumb', 350, null, null );

/**
 * Register Custom Post Type
 */
function post_type() {

  $labels = array(
    'name'                => 'Programs',
    'singular_name'       => 'Program',
    'menu_name'           => 'Programs',
    'parent_item_colon'   => '',
    'all_items'           => 'All Programs',
    'view_item'           => 'View Program',
    'add_new_item'        => 'Add New Program',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Program',
    'update_item'         => 'Update Program',
    'search_items'        => 'Search Programs',
    'not_found'           => 'Not found',
    'not_found_in_trash'  => 'Not found in Trash',
  );
  $rewrite = array(
    'slug'                => '',
    'with_front'          => false,
    'pages'               => false,
    'feeds'               => false,
  );
  $args = array(
    'label'               => 'program',
    'description'         => 'Programs',
    'labels'              => $labels,
    'taxonomies'          => array('program_type'),
    'supports'            => array( 'title', 'editor', 'thumbnail', ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 20,
    'menu_icon'           => 'dashicons-hammer',
    'can_export'          => false,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
  );
  register_post_type( 'program', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

// /* define program seasons here for the sake of DRYness */
// function get_program_season_array() {
//   return array(
//     'spring' => __( 'Spring', 'cmb' ),
//     'summer' => __( 'Summer', 'cmb' ),
//     'fall' => __( 'Fall', 'cmb' ),
//     'winter' => __( 'Winter', 'cmb' ),
//   );
// }

/**
 * Custom admin columns for post type
 */
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Title',
    '_cmb2_program_subtitle' => 'Subtitle',
    'program_type' => 'Type',
    '_cmb2_program_is_featured' => 'Featured',
    '_cmb2_program_season' => 'Season',
    'program_dates' => 'Date',
  );
  return $columns;
}
add_filter('manage_program_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'program' ) {
    $custom = get_post_custom();
    if ( $column == 'featured_image' )
      echo the_post_thumbnail( 'program-thumb' );
    elseif ( $column == 'program_dates' ) {
      $timestamp_start = $custom['_cmb2_program_start'][0];
      $timestamp_end = !empty($custom['_cmb2_program_end'][0]) ? $custom['_cmb2_program_end'][0] : $timestamp_start;
      if ($timestamp_end != $timestamp_start) {
        $date_txt = date('m/d/Y g:iA', $timestamp_start) . ' – ' . date('m/d/Y g:iA', $timestamp_end);
      } else {
        $date_txt = date('m/d/Y g:iA', $timestamp_start);
      }
      echo $date_txt . ($timestamp_end < current_time('timestamp') ? ' - <strong class="post-state">Past Program</strong>' : '');
    } elseif ( $column == 'program_type') {
      echo get_the_term_list($post->ID,'program_type','',', ','');
    } else {
      if (array_key_exists($column, $custom))
        echo $custom[$column][0];
    }
  }
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');

/**
 * CMB2 custom fields
 */
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['program_is_featured'] = array(
    'id'            => 'program_is_featured',
    'title'         => __( 'Is this a featured session on the homepage?', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'side',
    'priority'      => 'high',
    'show_names'    => false, // Show field names on the left
    'fields'        => array(
      array(
          'name'    => 'Featured',
          'id'      => $prefix . 'program_is_featured',
          'desc'    => 'Featured?',
          'type'    => 'checkbox',
      ),
    ),
  );

  $meta_boxes['program_images'] = array(
    'id'            => 'program_images',
    'title'         => __( 'Program Image Slideshow', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name' => 'Images',
        'id'   => $prefix .'slideshow-images',
        'type' => 'file_list',
        'description' => __( 'Multiple images as a slideshow in the featured image section of the post', 'cmb' ),
      ),
    ),
  );

  $meta_boxes['program_summary'] = array(
    'id'            => 'program_summary',
    'title'         => __( 'Program Summary', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
          'name'    => 'Subtitle',
          'id'      => $prefix . 'program_subtitle',
          'type'    => 'text',
      ),
      array(
          'name'    => 'Description',
          'id'      => $prefix . 'program_description',
          'type'    => 'textarea',
      ),
    ),
  );

  $meta_boxes['program_instructors'] = array(
    'id'            => 'program_instructors',
    'title'         => __( 'Instructors', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name'      => __( 'Program Instructors', 'cmb2' ),
        'desc'      => 'Select all that apply',
        'id'        => $prefix . 'program_instructors',
        'type'      => 'multicheck',
        'multiple'  => true,
        'options'   => \Firebelly\CMB2\get_post_options(['post_type' => 'person', 'numberposts' => -1]),
      ),
      array(
          'name'    => 'Other Instructors',
          'desc'    => 'Instructors that aren\'t part of the team, comma separated',
          'id'      => $prefix . 'other_instructors',
          'type'    => 'textarea',
      ),
    ),
  );

  $meta_boxes['program_prerequisites'] = array(
    'id'            => 'program_prerequisites',
    'title'         => __( 'Program Prerequisite(s)', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
          'name'    => 'Prerequisite(s)',
          'id'      => $prefix . 'program_prerequisites',
          'type'    => 'textarea',
      ),
    ),
  );

  $meta_boxes['program_when'] = array(
    'id'            => 'program_when',
    'title'         => __( 'Program Date & Time', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      // array(
      //   'name' => 'Program Season',
      //   'id'   => $prefix . 'program_season',
      //   'type' => 'select',
      //   'default' => 'spring',
      //   'options' => get_program_season_array(),
      // ),
      array(
          'name'    => 'Day(s) of the week',
          'id'      => $prefix . 'program_days',
          'type'    => 'text',
          'desc'    => 'Ex: Mondays & Wednesdays',
      ),
      array(
          'name'    => 'Start Date',
          'id'      => $prefix . 'program_start',
          'type'    => 'text_datetime_timestamp',
      ),
      array(
          'name'    => 'End Date',
          'desc'    => 'This must be filled — if a single day program, choose the same date as the start date.',
          'id'      => $prefix . 'program_end',
          'type'    => 'text_datetime_timestamp',
      ),
    ),
  );

  $meta_boxes['program_registration'] = array(
    'id'            => 'program_registration',
    'title'         => __( 'Program Registration Details', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
          'name'    => 'Enrollment',
          'id'      => $prefix . 'program_enrollment',
          'type'    => 'text',
          'desc'    => 'Ex: \'99 girls\''
      ),
      array(
          'name'    => 'Age Limit',
          'id'      => $prefix . 'age_limit',
          'type'    => 'text',
          'desc'    => 'Is there an age limit?'
      ),
      array(
          'name'    => 'Tuition',
          'id'      => $prefix . 'tuition',
          'type'    => 'text',
      ),
      array(
          'name'    => 'Hide Scholarship Link?',
          'id'      => $prefix . 'hide_scholarship',
          'desc'    => 'Check the box if there is <strong>not</strong> a scholarship available for this program',
          'type'    => 'checkbox',
      ),
      array(
          'name'    => 'Open Registration Date',
          'id'      => $prefix . 'registration_open',
          'type'    => 'text_datetime_timestamp'
      ),
      array(
          'name'    => 'Registration Link',
          'id'      => $prefix . 'registration_url',
          'type'    => 'text',
      ),
      array(
          'name'    => 'Registration Link Text',
          'id'      => $prefix . 'registration_link_text',
          'type'    => 'text',
          'default' => 'Sign up!',
          'desc'    => 'Could also be "apply" or other'
      ),
      array(
          'name'    => 'Registration full',
          'id'      => $prefix . 'registration_is_full',
          'desc'    => 'Is the registration for the program filled up?',
          'type'    => 'checkbox',
      ),
    ),
  );

  $meta_boxes['program_location'] = array(
    'id'            => 'program_location',
    'title'         => __( 'Program Location', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
          'name'    => 'Venue Name',
          'id'      => $prefix . 'venue',
          'type'    => 'text',
      ),
      array(
          'name'    => 'Address',
          'id'      => $prefix . 'address',
          'type'    => 'address',
      )
    ),
  );

  $meta_boxes['program_badges'] = array(
    'id'            => 'program_badges',
    'title'         => __( 'Earned Badges', 'cmb2' ),
    'object_types'  => array( 'program', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name'      => __( 'Earned Badges', 'cmb2' ),
        'desc'      => 'Select all that apply',
        'id'        => $prefix . 'program_badges',
        'type'      => 'multicheck',
        'multiple'  => true,
        'options'   => \Firebelly\CMB2\get_post_options(['post_type' => 'badge', 'numberposts' => -1]),
      ),
      array(
          'name'    => 'No badges Text',
          'desc'    => 'If there are no badges use this field to explain how badges are earned.',
          'id'      => $prefix . 'badges_text',
          'type'    => 'text',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

/**
 * Get Programs
 */
function get_programs($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = get_option('posts_per_page');
  if (!empty($_REQUEST['past_programs'])) $options['past_programs'] = 1;
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type' => 'program',
    'meta_key' => '_cmb2_program_start',
    'orderby' => 'meta_value_num',
    'tax_query' => array(
      array(
        'taxonomy' => 'program_type',
        'field' => 'id',
        'terms' => $options['program_type']
      )
    )
  ];
  // Make sure we're only pulling upcoming or past programs
  $args['order'] = !empty($options['past_programs']) ? 'DESC' : 'ASC';
  $args['meta_query'] = [
    [
      'key' => '_cmb2_program_end',
      'value' => current_time('timestamp'),
      'compare' => (!empty($options['past_programs']) ? '<=' : '>')
    ],
    [
      'key' => '_cmb2_program_type',
      // 'value' => $options['program_type'],
    ]
  ];

  // Display all matching programs using article-program.php
  $program_posts = get_posts($args);
  if (!$program_posts) return false;
  $output = '';
  foreach ($program_posts as $program_post):
    ob_start();
    include(locate_template('templates/article-program.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

/**
 * Geocode address for event and save in custom fields
 */
function geocode_address($post_id, $post='') {
  $address = get_post_meta($post_id, '_cmb2_address', 1);
  $address = wp_parse_args($address, array(
      'address-1' => '',
      'address-2' => '',
      'city'      => '',
      'state'     => '',
      'zip'       => '',
   ));

  if (!empty($address['address-1'])):
    $address_combined = $address['address-1'] . ' ' . $address['address-1'] . ' ' . $address['city'] . ', ' . $address['state'] . ' ' . $address['zip'];
    $request_url = "http://maps.google.com/maps/api/geocode/xml?sensor=false&address=" . urlencode($address_combined);

    $xml = simplexml_load_file($request_url);
    $status = $xml->status;
    if(strcmp($status, 'OK')===0):
        $lat = $xml->result->geometry->location->lat;
        $lng = $xml->result->geometry->location->lng;
        update_post_meta($post_id, '_cmb2_lat', (string)$lat);
        update_post_meta($post_id, '_cmb2_lng', (string)$lng);
    endif;
  endif;
}
add_action('save_post_program', __NAMESPACE__ . '\\geocode_address', 20, 2);

/**
 * Add query vars for programs
 */
function add_query_vars_filter($vars){
  $vars[] = "past_programs";
  return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\\add_query_vars_filter' );

/**
 * Helper function to populate program object for listings & single view
 */
function get_program_details($post) {
  $program = [
    'ID' => $post->ID,
    'title' => $post->post_title,
    'name' => $post->post_name,
    'subtitle' => get_post_meta($post->ID, '_cmb2_program_subtitle', true),
    'body' => apply_filters('the_content', $post->post_content),
    'tuition' => get_post_meta($post->ID, '_cmb2_tuition', true),
    'hide_scholarship' => get_post_meta($post->ID, '_cmb2_hide_scholarship', true),
    'description' => get_post_meta($post->ID, '_cmb2_program_description', true),
    'days' => get_post_meta($post->ID, '_cmb2_program_days', true),
    'start' => get_post_meta($post->ID, '_cmb2_program_start', true),
    'end' => get_post_meta( $post->ID, '_cmb2_program_end', true),
    'venue' => get_post_meta($post->ID, '_cmb2_venue', true),
    'address' => get_post_meta($post->ID, '_cmb2_address', true),
    'address_lat' => get_post_meta($post->ID, '_cmb2_lat', true),
    'address_lng' => get_post_meta($post->ID, '_cmb2_lng', true),
    'instructors' => get_post_meta($post->ID, '_cmb2_program_instructors', false),
    'other_instructors' => get_post_meta($post->ID, '_cmb2_other_instructors', true),
    'prerequisites' => get_post_meta($post->ID, '_cmb2_program_prerequisites', true),
    'season' => get_post_meta($post->ID, '_cmb2_program_season', true),
    'enrollment' => get_post_meta($post->ID, '_cmb2_program_enrollment', true),
    'age_limit' => get_post_meta($post->ID, '_cmb2_age_limit', true),
    'registration_open' => get_post_meta($post->ID, '_cmb2_registration_open', true),
    'registration_url' => get_post_meta($post->ID, '_cmb2_registration_url', true),
    'registration_link_text' => get_post_meta($post->ID, '_cmb2_registration_link_text', true),
    'registration_is_full' => get_post_meta($post->ID, '_cmb2_registration_is_full', true),
    'badges' => get_post_meta($post->ID, '_cmb2_program_badges', false),
    'badges_text' => get_post_meta($post->ID, '_cmb2_badges_text', true),
  ];
  // Is this program multiple days?
  $program['multiple_days'] = (date('Y-m-d', $program['start']) != date('Y-m-d', $program['end']));
  $program['start_time'] = date('g:ia', $program['start']);
  $program['end_time'] = date('g:ia', $program['end']);
  if ($program['start_time'] != $program['end_time']) {
    $program['time_txt'] = $program['start_time'] . '–' . $program['end_time'];
  } else {
    $program['time_txt'] = $program['start_time'];
  }

  $program['archived'] = empty($program['end']) ? ($program['start'] < current_time('timestamp')) : ($program['end'] < current_time('timestamp'));
  $program['desc'] = date('M d, Y @ ', $program['start']) . $program['time_txt']; // used in map pins
  $program['year'] = date('Y', $program['start']);

  $address = get_post_meta($post->ID, '_cmb2_address', true);
  $program['address'] = wp_parse_args($address, array(
      'address-1' => '',
      'address-2' => '',
      'city'      => '',
      'state'     => '',
      'zip'       => '',
   ));
  return (object)$program;
}
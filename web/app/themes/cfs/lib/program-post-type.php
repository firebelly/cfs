<?php
/**
 * Program post type
 */

namespace Firebelly\PostTypes\Program;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes

$cpt = new PostType('program', [
  'taxonomies' => ['program_type'],
  'supports'   => ['title', 'editor', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);
$cpt->taxonomy('program_type');

/**
 * Admin columns
 */
$cpt->columns()->set([
    'cb' => '<input type="checkbox" />',
    'title' => __('Title'),
    'program_type' => __('Type'),
    'date_start' => __('Date Start'),
    'date_end' => __('Date End'),
    'time' => __('Time'),
    'featured' => __('Featured'),
    // 'date' => __('Date')
]);
$cpt->columns()->sortable([
    'date_start' => ['_cmb2_date_start', true],
    'date_end' => ['_cmb2_date_end', true]
]);
$cpt->columns()->populate('date_start', function($column, $post_id) {
  if ($val = get_post_meta($post_id, '_cmb2_date_start', true)) {
    echo date('Y-m-d', $val);
  } else {
    echo 'n/a';
  }
});
$cpt->columns()->populate('date_end', function($column, $post_id) {
  if ($val = get_post_meta($post_id, '_cmb2_date_end', true)) {
    echo date('Y-m-d', $val);
  } else {
    echo 'n/a';
  }
});
$cpt->columns()->populate('time', function($column, $post_id) {
  echo get_post_meta($post_id, '_cmb2_time', true);
});
$cpt->columns()->populate('featured', function($column, $post_id) {
  echo (get_post_meta($post_id, '_cmb2_featured', true)) ? '&check;' : '';
});

/**
 * CMB2 custom fields
 */
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['program_info'] = array(
    'id'            => 'program_info',
    'title'         => __( 'Program Info', 'cmb2' ),
    'object_types'  => ['program'],
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true,
    'fields'        => array(
      [
        'name'      => 'Details',
        'id'        => $prefix . 'program_details',
        'type'      => 'wysiwyg',
        'options' => [
          'textarea_rows' => 10,
        ],
      ],
      [
        'name'       => 'Age Minimum',
        'id'         => $prefix . 'age_minimum',
        'type'       => 'text_small',
        'attributes' => [
          'type'     => 'number',
          'pattern'  => '\d*',
        ],
      ],
      [
        'name'       => 'Age Maximum',
        'id'         => $prefix . 'age_maximum',
        'type'       => 'text_small',
        'attributes' => [
          'type'     => 'number',
          'pattern'  => '\d*',
        ],
      ],
    ),
  );

  $meta_boxes['program_when'] = array(
    'id'            => 'program_when',
    'title'         => __( 'Program Date & Time', 'cmb2' ),
    'object_types'  => ['program'],
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true,
    'fields'        => array(
      [
        'name'      => 'Start Date',
        'id'        => $prefix . 'date_start',
        'type'      => 'text_date_timestamp',
      ],
      [
        'name'      => 'End Date',
        'id'        => $prefix . 'date_end',
        'type'      => 'text_date_timestamp',
      ],
      [
        'name'      => 'Time',
        'id'        => $prefix . 'time',
        'desc'      => 'e.g. 5:00pm to 8:00pm',
        'type'      => 'text_medium',
      ],
      [
        'name'      => 'Registration Opens',
        'id'        => $prefix . 'registration_opens',
        'type'      => 'text_datetime_timestamp'
      ],
      [
        'name'      => 'Registration Deadline',
        'id'        => $prefix . 'registration_deadline',
        'type'      => 'text_datetime_timestamp'
      ],

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
  if (!empty($_REQUEST['past_programs'])) $options['past_programs'] = 1; // support for AJAX requests
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type' => 'program',
    'meta_key' => '_cmb2_date_start',
    'orderby' => 'meta_value_num',
  ];
  if (!empty($options['tax_query'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'program_type',
        'field' => 'id',
        'terms' => $options['program_type']
      ]
    ];
  }
  // Make sure we're only pulling upcoming or past programs
  $args['order'] = !empty($options['past_programs']) ? 'DESC' : 'ASC';
  $args['meta_query'] = [
    [
      'key' => '_cmb2_date_end',
      'value' => current_time('timestamp'),
      'compare' => (!empty($options['past_programs']) ? '<=' : '>')
    ],
    [
      'key' => '_cmb2_program_type',
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

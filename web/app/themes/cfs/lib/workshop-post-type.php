<?php
/**
 * Workshop post type
 */

namespace Firebelly\PostTypes\Workshop;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes

$workshops = new PostType('workshop', [
  'taxonomies' => ['workshop_type', 'workshop_series'],
  'supports'   => ['title', 'editor', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);
$workshops->taxonomy('workshop_type');
$workshops->taxonomy([
  'name'     => 'workshop_series',
  'plural'   => 'Workshop Series',
]);

/**
 * CMB2 custom fields
 */
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['workshop_info'] = array(
    'id'            => 'workshop_info',
    'title'         => __( 'Workshop Info', 'cmb2' ),
    'object_types'  => ['workshop'],
    'context'       => 'normal',
    'priority'      => 'high',
    'fields'        => array(
      [
        'name'      => 'Details',
        'id'        => $prefix . 'workshop_details',
        'type'      => 'wysiwyg',
        'options' => [
          'textarea_rows' => 10,
        ],
      ],
      [
        'name'        => 'Cost',
        'id'          => $prefix . 'cost',
        'type'        => 'text_small',
      ],
      [
        'name'        => 'Eventbrite URL',
        'id'          => $prefix . 'eventbrite_url',
        'type'        => 'text',
        'description' => 'e.g. https://www.eventbrite.com/e/xxxx',
      ],
      [
        'name'        => 'Tickets Available',
        'id'          => $prefix . 'tickets_available',
        'type'        => 'text_small',
        'attributes'  => [
          'type'      => 'number',
          'pattern'   => '\d*',
        ],
      ],
    ),
  );

  $meta_boxes['workshop_when'] = array(
    'id'            => 'workshop_when',
    'title'         => __( 'Workshop Date & Time', 'cmb2' ),
    'object_types'  => ['workshop'],
    'context'       => 'normal',
    'priority'      => 'high',
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
      // [
      //   'name'      => 'Application Opens',
      //   'id'        => $prefix . 'application_opens',
      //   'type'      => 'text_datetime_timestamp'
      // ],
      [
        'name'      => 'Application Deadline',
        'id'        => $prefix . 'application_deadline',
        'type'      => 'text_datetime_timestamp'
      ],

    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

/**
 * Get Workshops
 */
function get_workshops($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = get_option('posts_per_page');
  if (!empty($_REQUEST['past_workshops'])) $options['past_workshops'] = 1; // support for AJAX requests
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'workshop',
    'meta_key'    => '_cmb2_date_start',
    'orderby'     => 'meta_value_num',
  ];
  if (!empty($options['tax_query'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'workshop_type',
        'field'    => 'id',
        'terms'    => $options['workshop_type']
      ]
    ];
  }
  // Make sure we're only pulling upcoming or past workshops
  $args['order'] = !empty($options['past_workshops']) ? 'DESC' : 'ASC';
  $args['meta_query'] = [
    [
      'key'     => '_cmb2_date_end',
      'value'   => current_time('timestamp'),
      'compare' => (!empty($options['past_workshops']) ? '<=' : '>')
    ],
    [
      'key' => '_cmb2_workshop_type',
    ]
  ];

  // Display all matching workshops using article-workshop.php
  $workshop_posts = get_posts($args);
  if (!$workshop_posts) return false;
  $output = '';
  foreach ($workshop_posts as $workshop_post):
    ob_start();
    include(locate_template('templates/article-workshop.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

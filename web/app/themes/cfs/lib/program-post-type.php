<?php
/**
 * Program post type
 */

namespace Firebelly\PostTypes\Program;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$cpt = new PostType('program', [
  'supports'   => ['title', 'editor', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);

/**
 * Admin columns
 */
$cpt->columns()->set([
    'cb' => '<input type="checkbox" />',
    'title' => esc_html__( 'Title', 'cmb2' ),
    'date_start' => esc_html__( 'Date Start', 'cmb2' ),
    'date_end' => esc_html__( 'Date End', 'cmb2' ),
    'time' => esc_html__( 'Time', 'cmb2' ),
    'featured' => esc_html__( 'Featured', 'cmb2' ),
    // 'date' => esc_html__('Date')
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

// Register CPT with Wordpress
$cpt->register();

/**
 * CMB2 custom fields
 */
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  // Program Info fields
  $program_info = new_cmb2_box([
    'id'            => $prefix . 'program_info',
    'title'         => esc_html__( 'Program Info', 'cmb2' ),
    'object_types'  => ['program'],
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true,
  ]);
  $program_info->add_field([
    'name'      => esc_html__( 'Intro Quote', 'cmb2' ),
    'id'        => $prefix . 'intro_quote',
    'type'      => 'textarea_small',
  ]);
  $program_info->add_field([
    'name'       => 'Age Minimum',
    'id'         => $prefix . 'age_minimum',
    'type'       => 'text_small',
    'attributes' => [
      'type'     => 'number',
      'pattern'  => '\d*',
    ],
  ]);
  $program_info->add_field([
    'name'       => 'Age Maximum',
    'id'         => $prefix . 'age_maximum',
    'type'       => 'text_small',
    'attributes' => [
      'type'     => 'number',
      'pattern'  => '\d*',
    ],
  ]);

  // Program When fields
  $program_when = new_cmb2_box([
    'id'            => 'program_when',
    'title'         => esc_html__( 'Program Date & Time', 'cmb2' ),
    'object_types'  => ['program'],
    'context'       => 'normal',
    'priority'      => 'high',
    'required'      => 'required',
    'show_names'    => true,
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'Start Date', 'cmb2' ),
    'id'        => $prefix . 'date_start',
    'type'      => 'text_date_timestamp',
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'End Date', 'cmb2' ),
    'id'        => $prefix . 'date_end',
    'type'      => 'text_date_timestamp',
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'Time', 'cmb2' ),
    'id'        => $prefix . 'time',
    'desc'      => 'e.g. 5:00pm to 8:00pm',
    'type'      => 'text_medium',
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'Applications Open', 'cmb2' ),
    'id'        => $prefix . 'registration_opens',
    'type'      => 'text_datetime_timestamp'
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'Application Deadline', 'cmb2' ),
    'id'        => $prefix . 'registration_deadline',
    'type'      => 'text_datetime_timestamp'
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'Applications URL', 'cmb2' ),
    'id'        => $prefix . 'registration_url',
    'type'      => 'text',
    'desc'      => 'Link used for Apply button, e.g. http://example.com/',
  ]);
  $program_when->add_field([
    'name'      => esc_html__( 'Applications Closed', 'cmb2' ),
    'id'        => $prefix . 'registration_closed',
    'type'      => 'checkbox',
    'desc'      => 'Manually close applications by checking this',
  ]);
  $program_when->add_field([
    'name'             => esc_html__( 'Constant Contact List', 'cmb2' ),
    'id'               => $prefix . 'cc_list_id',
    'type'             => 'select',
    'show_option_none' => true,
    'options_cb'       => '\Firebelly\ConstantContact\get_cc_lists',
    'desc'             => 'If a list is selected, will show "Subscribe to our newsletter to receive updates about when applications open" link when registrations are closed.',
  ]);
}

/**
 * Get Programs
 */
function get_programs($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = get_option('posts_per_page');
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'program',
    'meta_key'    => '_cmb2_date_start',
    'orderby'     => 'meta_value_num',
  ];
  // Make sure we're only pulling upcoming or past programs
  $args['order'] = !empty($options['past_programs']) ? 'DESC' : 'ASC';
  $args['meta_query'] = [
    [
      'key'     => '_cmb2_date_end',
      'value'   => current_time('timestamp'),
      'compare' => (!empty($options['past_programs']) ? '<=' : '>')
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

function get_featured_programs($args=[]) {
  array_merge($featured_args = [
    'post_type'  => 'program',
    'order'      => 'ASC',
    'orderby'    => 'meta_value_num',
    'meta_key'   => '_cmb2_date_start',
    'meta_query' => [
      [
        'key'    => '_cmb2_featured',
        'value'  => 'on',
      ],
    ]
  ], $args);
  return get_posts($featured_args);
}

function get_registration_button($program_post) {
  $output = '';
  $now = time();

  // Check if registration open
  if ( empty($program_post->meta['_cmb2_registration_closed'][0])
       && !empty($program_post->meta['_cmb2_registration_url'][0])
       && (!empty($program_post->meta['_cmb2_registration_opens']) && $now >= $program_post->meta['_cmb2_registration_opens'][0])
       && (!empty($program_post->meta['_cmb2_registration_deadline']) && $now < $program_post->meta['_cmb2_registration_deadline'][0])
    ) {
    $output .= '<a target="_blank" class="button -wide" href="'.$program_post->meta['_cmb2_registration_url'][0].'">Apply</a>';
  } else {
    if (!empty($program_post->meta['_cmb2_cc_list_id'][0])) {
      // Adjust copy if we know when applications open
      if (!empty($program_post->meta['_cmb2_registration_opens']) && $now < $program_post->meta['_cmb2_registration_opens'][0]) {
        $output .= '<p><a data-cc-list-id="'.$program_post->meta['_cmb2_cc_list_id'][0].'" href="#site-footer" class="subscribe-to-newsletter">Subscribe to our newsletter to receive updates about this program</a></p>';
      } else {
        $output .= '<p><a data-cc-list-id="'.$program_post->meta['_cmb2_cc_list_id'][0].'" href="#site-footer" class="subscribe-to-newsletter">Subscribe to our newsletter to receive updates about when applications open</a></p>';
      }
    }
  }

  return $output;
}

<?php
/**
 * Person post type
 */

namespace Firebelly\PostTypes\Person;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$persons = new PostType(['name' => 'person', 'plural' => 'People', 'slug' => 'person'], [
  'taxonomies' => ['person_category'],
  'capability_type' => 'post',
  'supports'   => ['title', 'editor', 'thumbnail', 'post-formats', 'excerpt'],
  'has_archive' => true,
  'public' => true,
  'rewrite'    => ['with_front' => true, 'slug' => 'person'],
  'query_var' => true
]);
$persons->filters(['person_category']);
$persons->register();

// Custom taxonomy
$person_category = new Taxonomy([
  'name'     => 'person_category',
  'slug'     => 'person_category',
  'plural'   => 'Person Categories',
]);
$person_category->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $person_info = new_cmb2_box([
    'id'            => $prefix . 'person_info',
    'title'         => __( 'Person Info', 'cmb2' ),
    'object_types'  => ['person'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $person_info->add_field([
    'name'      => 'Title',
    'id'        => $prefix . 'person_title',
    'type'      => 'text_medium',
    'column'    => array(
      'position' => 2,
        'name'     => 'Title',
      ),
    // 'desc'      => 'e.g. 20xx Freedom Fellow',
  ]);
  $person_info-> add_field([
    'name'      => 'Show Link?',
    'id'        => $prefix . 'show_link',
    'type'      => 'checkbox',
    'desc'      => 'If checked, will show front-end (e.g. /person/john-doe)',
    'default' => false
  ]);

}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

// Update post meta for sorting people by last name
function person_sort_meta($post_id) {
  if (wp_is_post_revision($post_id))
    return;

  $post_title = get_the_title($post_id);
  if (strpos($post_title, ' ') !== FALSE) {
    list($first, $last) = preg_split('/ ([^ ]+)$/', $post_title, 0, PREG_SPLIT_DELIM_CAPTURE);
  } else {
    $first = '';
    $last = $post_title;
  }
  update_post_meta($post_id, '_cmb2_first_name', $first);
  update_post_meta($post_id, '_cmb2_last_name', $last);
}
add_action('save_post_person', __NAMESPACE__.'\person_sort_meta');

/**
 * Get People
 */
function get_people($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = -1;
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'person',
  ];
  if (!empty($options['category'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'person_category',
        'field' => 'slug',
        'terms' => $options['category']
      ]
    ];
  }

  // Display all matching posts using article-{$post_type}.php
  $people_posts = get_posts($args);
  if (!$people_posts) return false;
  $output = '';
  foreach ($people_posts as $person_post):
    $person_post->column_width = $options['column-width'];
    ob_start();
    include(locate_template('templates/article-person.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

function get_person_by_slug($slug) {
  $posts = get_posts([
    'name' => $slug,
    'posts_per_page' => 1,
    'post_type' => 'person',
    'post_status' => 'publish'
  ]);
  if (!$posts) {
    return false;
  }
  return $posts[0];
}

function get_person_like_title($title) {
  global $wpdb;
  $sql = $wpdb->prepare('SELECT ID FROM wp_posts
          WHERE post_type = "person"
          AND post_title LIKE %s ORDER BY ID DESC LIMIT 1',
          '%'.$wpdb->esc_like($title).'%');
  $results = $wpdb->get_results($sql);
  if ($results) {
    return get_post($results[0]->ID);
  }
  return false;
}

function get_featured_person($args=[]) {
  array_merge($featured_args = [
    'post_type' => 'person',
    'orderby' => 'meta_value_num',
    'meta_key' => '_cmb2_first_name',
    'meta_query' => [
      [
        'key' => '_cmb2_featured',
        'value' => 'on'
      ],
    ]
  ], $args);

  return get_posts($featured_args);
}

add_action('pre_get_posts', __NAMESPACE__ . '\\custom_query_vars');
function custom_query_vars($query) {
  if (!is_admin() && $query->is_main_query()) {
    if (is_post_type_archive('person')) {
      $query->set('tax_query', [
        [
          'taxonomy' => 'person_category',
          'field' => 'slug',
        ]
      ]);
    }
  }
  return $query;
}

function people_query($query) {
  global $wp_the_query;
  if ($wp_the_query === $query && !is_admin() && is_post_type_archive('person')) {
    $query->set('orderby', 'meta_value_num');
  }
}
add_action('pre_get_posts', __NAMESPACE__ . '\\people_query');

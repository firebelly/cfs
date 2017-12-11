<?php
/**
 * Partner post type
 */

namespace Firebelly\PostTypes\Partner;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$partners = new PostType('partner', [
  'taxonomies' => ['partner_category'],
  'supports'   => ['title', 'editor', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);
$partners->filters(['partner_category']);
$partners->register();

// Custom taxonomy
$partner_category = new Taxonomy([
  'name'     => 'partner_category',
  'slug'     => 'partner_category',
  'plural'   => 'Partner Categories',
]);
$partner_category->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $partner_info = new_cmb2_box([
    'id'            => $prefix . 'partner_info',
    'title'         => __( 'Partner Info', 'cmb2' ),
    'object_types'  => ['partner'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $partner_info->add_field([
    'name'      => 'Title',
    'id'        => $prefix . 'partner_title',
    'type'      => 'text_medium',
    // 'desc'      => 'e.g. 20xx Freedom Fellow',
  ]);

}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

// Update post meta for sorting partners by last name
function partner_sort_meta($post_id) {
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
add_action('save_post_partner', __NAMESPACE__.'\partner_sort_meta');

/**
 * Get partners
 */
function get_partners($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = -1;
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'partner',
  ];
  if (!empty($options['category'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'partner_category',
        'field' => 'slug',
        'terms' => $options['category']
      ]
    ];
  }

  // Display all matching posts using article-{$post_type}.php
  $partners_posts = get_posts($args);
  if (!$partners_posts) return false;
  $output = '';
  foreach ($partners_posts as $partner_post):
    $partner_post->column_width = $options['column-width'];
    ob_start();
    include(locate_template('templates/article-partner.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

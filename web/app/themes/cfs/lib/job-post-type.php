<?php
/**
 * Job post type
 */

namespace Firebelly\PostTypes\Job;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$jobs = new PostType(['name' => 'job', 'plural' => 'Jobs', 'slug' => 'job'], [
  'capability_type' => 'post',
  'supports'   => ['title', 'editor', 'post-formats'],
  'has_archive' => true,
  'public' => true,
  'rewrite'    => ['with_front' => true, 'slug' => 'job'],
  'query_var' => true
]);
$jobs->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $job_info = new_cmb2_box([
    'id'            => $prefix . 'job_info',
    'title'         => __( 'Job Info', 'cmb2' ),
    'object_types'  => ['job'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $job_info->add_field([
    'name'      => 'Type',
    'id'        => $prefix . 'job_type',
    'type'      => 'text_medium',
    'column'    => array(
      'position' => 2,
        'name'     => 'Type',
      ),
    // 'desc'      => 'e.g. 20xx Freedom Fellow',
  ]);

}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Get jobs
 */
function get_jobs($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = -1;
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'job',
  ];

  // Display all matching posts using article-{$post_type}.php
  $jobs_posts = get_posts($args);
  if (!$jobs_posts) return false;
  $output = '';
  foreach ($jobs_posts as $job_post):
    $job_post->column_width = $options['column-width'];
    ob_start();
    include(locate_template('templates/article-job.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

add_action('pre_get_posts', __NAMESPACE__ . '\\custom_query_vars');
function custom_query_vars($query) {
  if (!is_admin() && $query->is_main_query()) {
    if (is_post_type_archive('job')) {
      $query->set('tax_query', [
        [
          'field' => 'slug',
        ]
      ]);
    }
  }
  return $query;
}

function jobs_query($query) {
  global $wp_the_query;
  if ($wp_the_query === $query && !is_admin() && is_post_type_archive('job')) {
    $query->set('orderby', 'meta_value_num');
  }
}
add_action('pre_get_posts', __NAMESPACE__ . '\\jobs_query');

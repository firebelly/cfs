<?php
/**
 * Job post type
 */

namespace Firebelly\PostTypes\Job;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes

$jobs = new PostType('job', [
  'supports'   => ['title', 'editor'],
  'rewrite'    => ['with_front' => false],
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

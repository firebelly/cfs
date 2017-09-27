<?php
/**
 * Extra fields for Posts
 */

namespace Firebelly\PostTypes\Posts;

function metaboxes() {
  $prefix = '_cmb2_';

  // $meta_boxes['post_metabox'] = array(
  //   'id'            => 'post_metabox',
  //   'title'         => esc_html__( 'Image Slideshow', 'cmb2' ),
  //   'object_types'  => array( 'post', ), // Post type
  //   'context'       => 'normal',
  //   'priority'      => 'high',
  //   'show_names'    => true, // Show field names on the left
  //   'fields'        => array(
  //     array(
  //       'name' => 'Images',
  //       'id'   => $prefix .'slideshow-images',
  //       'type' => 'file_list',
  //       'description' => esc_html__( 'Multiple images as a slideshow in the featured image section of the post', 'cmb' ),
  //     ),
  //   ),
  // );

  $post_is_featured = new_cmb2_box([
    'id'            => $prefix . 'post_is_featured',
    'title'         => esc_html__( 'Is this a featured post on the homepage?', 'cmb2' ),
    'object_types'  => ['post', 'program', 'workshop'],
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
  ]);
  $post_is_featured->add_field([
    'name'    => esc_html__( 'Featured', 'cmb2' ),
    'id'      => $prefix . 'featured',
    'desc'    => 'Featured?',
    'type'    => 'checkbox',
  ]);

  /**
   * Repeating media/quote/stat blocks used on Programs and Workshops
   */
  $cmb_group = new_cmb2_box([
    'id'           => $prefix . 'metabox',
    'title'        => esc_html__( 'Media Blocks', 'cmb2' ),
    'priority'      => 'low',
    'closed'      => true,
    'object_types' => ['program','workshop', 'page'],
  ]);

  $group_field_id = $cmb_group->add_field([
    'id'          => $prefix . 'program_blocks',
    'type'        => 'group',
    // 'description' => esc_html__( '', 'cmb' ),
    'options'     => array(
      'group_title'   => esc_html__( 'Media Block {#}', 'cmb2' ),
      'add_button'    => esc_html__( 'Add Another Block', 'cmb2' ),
      'remove_button' => esc_html__( 'Remove Block', 'cmb2' ),
      'sortable'      => true,
    ),
  ]);

  $cmb_group->add_group_field( $group_field_id, [
    'name' => 'Video',
    'description' => 'Paste in a Vimeo URL, e.g. https://vimeo.com/101102896',
    'id'   => 'video_url',
    'type' => 'text',
  ]);

  $cmb_group->add_group_field( $group_field_id, [
    'name' => 'Image(s)',
    'id'   => 'images',
    'type' => 'file_list',
  ]);

  $cmb_group->add_group_field( $group_field_id, [
    'name' => 'Pull-quote',
    'id'   => 'pullquote',
    'type' => 'textarea_small',
  ]);

  $cmb_group->add_group_field( $group_field_id, [
    'name' => 'Stat Figure',
    'id'   => 'stat_figure',
    'type' => 'text_small',
    'description' => 'e.g. 99/100',
  ]);

  $cmb_group->add_group_field( $group_field_id, [
    'name' => 'Stat Label',
    'id'   => 'stat_label',
    'type' => 'textarea_small',
    'description' => 'Statistic caption or context',
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\\metaboxes' );

/**
 * Remove tags from admin for posts
 */
function remove_tags_metabox() {
  remove_meta_box('tagsdiv-post_tag', 'post', 'side');
}
add_action( 'do_meta_boxes', __NAMESPACE__ . '\\remove_tags_metabox' );

/**
 * Get post images and put into slideshow
 */
function get_post_slideshow($post_id) {
    $images = get_post_meta($post_id, '_cmb2_slideshow-images', true);
    $video_links_parsed = get_post_meta($post_id, '_cmb2_video_links_parsed', true);

    if (!$images && !$video_links_parsed) return false;
    $output = '<ul class="slider">';
    // Are there videos?
    if ($video_links_parsed) {
      $output .= \Firebelly\Utils\video_slideshow($video_links_parsed);
    }
    // Is there also a featured image?
    if (get_the_post_thumbnail($post_id)) {
      $image = get_post($post_id);
      $image = \Firebelly\Media\get_header_bg($image, '', 'bw', 'large');
      $output .= '<li class="slide-item"><div class="slide-image" '.$image.'></div></li>';
    }
    if ($images) {
      foreach ($images as $attachment_id => $attachment_url):
        $image = get_attached_file($attachment_id, false);
        $image = \Firebelly\Media\get_header_bg($image, '', 'bw', 'large');
        $output .= '<li class="slide-item"><div class="slide-image" '.$image.'></div></li>';
      endforeach;
    }
    $output .= '</ul>';
    return $output;
}

/**
 * Parse video_links on save
 */
function parse_video_links($post_id, $post, $update) {
  $video_links = !empty($_POST['_cmb2_video_links']) ? $_POST['_cmb2_video_links'] : update_post_meta($post_id, '_cmb2_video_links_parsed', '');
  $video_links_parsed = '';
  if ($video_links) {
    $video_lines = explode(PHP_EOL, trim($video_links));
    foreach ($video_lines as $line) {
      // Extract vimeo video ID and pull large thumbnail from API
      $vimeo_url = trim($line);
      $img_url = '';
      if (preg_match('/vimeo.com\/(\d+)/', $line, $m)) {
        $img_id = $m[1];
        $hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $img_id . '.php'));
        $img_url = $hash[0]['thumbnail_large'];
        $img_url = str_replace('640','1280x720', $img_url);
        $title = $hash[0]['title'];
      }

      // If we found an image, show link to video and build new_lines
      if ($img_url) {
        $video_links_parsed .= $vimeo_url.'¶'.$img_url.'¶'.$title."\n";
      }
    }
    // Store parsed links in hidden post meta field
    if ($video_links_parsed) {
      update_post_meta($post_id, '_cmb2_video_links_parsed', $video_links_parsed);
    }
  }
}
add_action('save_post', __NAMESPACE__ . '\\parse_video_links', 10, 3);
add_action('save_post_program', __NAMESPACE__ . '\\parse_video_links', 10, 3);



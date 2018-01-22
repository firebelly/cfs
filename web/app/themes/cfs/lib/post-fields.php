<?php
/**
 * Extra fields for Posts (and shared fields w/ other post types)
 */

namespace Firebelly\Fields\Posts;

function metaboxes() {
  $prefix = '_cmb2_';

  // Moments of Justice fields
  $page_intro = new_cmb2_box([
    'id'            => $prefix . 'moments_justice_fields',
    'title'         => esc_html__( 'Moments of Justice options', 'cmb2' ),
    'object_types'  => ['workshop'],
    'show_on_cb'    => '\Firebelly\CMB2\cmb_is_moments_of_justice',
    'context'       => 'side',
    'priority'      => 'low',
  ]);
  $page_intro->add_field([
    'name'       => esc_html__( 'Sponsorship Packet PDF', 'cmb2' ),
    'id'         => $prefix .'sponsorship_packet',
    'type'       => 'file',
  ]);

  // Parent navigation fields
  $image_slideshow = new_cmb2_box([
    'id'            => 'image_slideshow',
    'title'         => esc_html__( 'Image Slideshow', 'cmb2' ),
    'object_types'  => ['program', 'workshop'],
    'context'       => 'side',
    'priority'      => 'low',
    // 'closed'        => true,
  ]);
  $image_slideshow->add_field([
    'name'       => __( 'Images', 'cmb2' ),
    'show_names' => false,
    'id'         => $prefix .'slideshow_images',
    'type'       => 'file_list',
    'desc'       => esc_html__('Slideshow for bottom of post', 'cmb2'),
  ]);

  $post_is_featured = new_cmb2_box([
    'id'            => $prefix . 'post_is_featured',
    'title'         => esc_html__( 'Is this a featured post on the homepage?', 'cmb2' ),
    'object_types'  => ['post', 'program'],
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
   * (Uses tabbed groups from https://gist.github.com/natebeaty/39672b0d96eedf621dadf24c8ddc9a32)
   */

  $cmb_group = new_cmb2_box([
    'id'           => $prefix . 'accordions_group',
    'title'        => esc_html__( 'Accordions', 'cmb2' ),
    'priority'     => 'low',
    'object_types' => ['program', 'workshop', 'page', 'post'],
    'show_on_cb'   => '\Firebelly\CMB2\cmb_is_not_front_page',
  ]);
  $group_field_id = $cmb_group->add_field([
    'id'          => $prefix . 'accordions',
    'type'        => 'group',
    'options'     => array(
      'group_title'   => esc_html__( 'Accordion {#}', 'cmb2' ),
      'add_button'    => esc_html__( 'Add Another Accordion', 'cmb2' ),
      'remove_button' => esc_html__( 'Remove Accordion', 'cmb2' ),
      'sortable'      => true,
    ),
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'        => 'Accordion Title',
    'id'          => 'accordion_title',
    'type'        => 'text',
    'before_row'   => '
        <div class="cmb2-tabs">
            <ul class="tabs-nav">
                <li class="current"><a href="#tab-content-1">Content</a></li>
                <li><a href="#tab-content-2">Media Block</a></li>
            </ul>
            <div class="tab-content tab-content-1 current">
    ',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'        => 'Always Open Accordion',
    'desc'        => 'If checked, accordion content will be open on page load',
    'id'          => 'accordion_always_open',
    'type'        => 'checkbox',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'        => 'Accordion Body',
    'id'          => 'accordion_body',
    'type'        => 'wysiwyg',
    'options' => [
      'textarea_rows' => 8,
    ],
    'after_row' => '</div>',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'        => 'Video',
    'description' => 'Paste in a Vimeo URL, e.g. https://vimeo.com/101102896',
    'id'          => 'video_url',
    'type'        => 'text',
    'before_row'   => '<div class="tab-content tab-content-2"><h3>(Choose One)</h3>',
  ]);

  $cmb_group->add_group_field($group_field_id, [
    'name'   => 'Image(s)',
    'id'     => 'images',
    'type'   => 'file_list',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'   => 'Pull-quote or Image caption',
    'id'     => 'pullquote',
    'desc'   => 'If image is selected, this is used as caption.',
    'type'   => 'textarea_small',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'   => 'Pull-quote author',
    'id'     => 'pullquote_author',
    'type'   => 'text',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'        => 'Stat Figure',
    'id'          => 'stat_figure',
    'type'        => 'text_small',
    'description' => 'e.g. 99/100',
  ]);
  $cmb_group->add_group_field($group_field_id, [
    'name'        => 'Stat Label',
    'id'          => 'stat_label',
    'type'        => 'textarea_small',
    'description' => 'Statistic caption or context',
    'after_row'    => '
            </div><!-- /.tab-content -->
        </div><!-- /.cmb2-tabs -->
    ',
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
function get_post_slideshow($post, $opts=[]) {
  $opts = array_merge([
    'treated_images' => false
  ], $opts);
  if (!is_object($post) || empty($post->ID)) return;
  $images = get_post_meta($post->ID, '_cmb2_slideshow_images', true);
  $video_links_parsed = get_post_meta($post->ID, '_cmb2_video_links_parsed', true);
  if (!$images && !$video_links_parsed) return;

  $output = '<div class="slideshow"><ul class="slider">';
  // Are there videos?
  if ($video_links_parsed) {
    $output .= \Firebelly\Utils\video_slideshow($video_links_parsed);
  }
  // Is there also a featured image?
  // if (has_post_thumbnail($post)) {
  //   $output .= '<li class="slide-item"><div class="slide-image" ' . \Firebelly\Media\get_header_bg($post, ['size' => 'large']) . '></div></li>';
  // }
  if ($images) {
    foreach ($images as $attachment_id => $attachment_url) {
      if (!empty($opts['treated_images'])) {
        $image = \Firebelly\Media\get_header_bg($attachment_url, ['thumb_id' => $attachment_id, 'size' => 'large', 'output' => 'image']);
      } else {
        $image =  wp_get_attachment_image_src($attachment_id, 'large')[0];
      }
      $output .= '<li class="slide-item"><div class="slide-image" style="background-image:url(' . $image . ')"></div></li>';
    }
  }
  $output .= '</ul></div>';
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

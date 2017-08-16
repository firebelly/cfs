<?php
/**
 * Various media functions
 */

namespace Firebelly\Media;

// Image sizes for grid items
add_image_size( 'post-thumb', 288, 288, array('center', 'top') );
add_image_size( 'grid-thumb', 600, 430 );
add_image_size( 'grid-large', 1200, 700 );
add_image_size( 'banner_image', 1600, 800 );

/**
 * Get the file path (not URL) to a thumbnail of a particular size.
 * (get_attached_file() only returns paths to full-sized thumbnails.)
 * @param  int            $thumb_id - attachment id of thumbnail
 * @param  string|array   $size - thumbnail size string (e.g. 'full') or array [w,h]
 * @return path           file path to properly sized thumbnail
 */
function get_thumbnail_size_path($thumb_id, $size) {
  // Find the path to the root image. We can get this from get_attached_file.
  $old_path = get_attached_file($thumb_id, true);

  // Find the url of the image with the proper size
  $attr = wp_get_attachment_image_src($thumb_id, $size);
  $url = $attr[0];

  // Grab the filename of the sized image from the url
  $exploded_url = explode('/', $url);
  $filename = $exploded_url[count($exploded_url)-1];

  // Replace the filename in our path with the filename of the properly sized image
  $exploded_path = explode('/', $old_path);
  $exploded_path[count($exploded_path)-1] = $filename;
  $new_path = implode ('/', $exploded_path);

  return $new_path;
}

/**
 * Get header bg for post, duotone treated with the random IHC_BACKGROUND + Dark Blue
 * @param  string|object   $post_or_image (WP post object or background image)
 * @return HTML            background image code
 */
function get_header_bg($post_or_image, $remote_img=false, $thumb_id='', $color_or_bw='color', $size='full') {

  // Set colors if bw or color
  if ($color_or_bw!=='color') {
    $shadow = '141414';
    $highlight = 'e6e6f0';
  } else {
    $shadow = '3e4d9b';
    $highlight = 'c0c8f7';
  }

  $header_bg = $background_image = false;
  // If WP post object, get the featured image
  if (is_object($post_or_image)) {
    if (has_post_thumbnail($post_or_image->ID)) {
      $thumb_id = get_post_thumbnail_id($post_or_image->ID);
      $background_image = get_thumbnail_size_path($thumb_id, $size);
    }
  } else {
    // These are sent from a taxonomy page
    if ($remote_img) {
      $base_dir = wp_upload_dir()['basedir'] . '/backgrounds/';
      if(!file_exists($base_dir)) {
        mkdir($base_dir);
      }

      $background_image = $post_or_image;
      $remote_filename = basename($background_image, '.jpg');
      $remote_image = file_get_contents($background_image);
      file_put_contents($base_dir.$remote_filename.'.jpg', $remote_image);
      $background_image = $base_dir.$remote_filename.'.jpg';
    } else {
      $background_image = $post_or_image;
    }
  }
  if ($background_image) {
    $upload_dir = wp_upload_dir();
    $base_dir = $upload_dir['basedir'] . '/backgrounds/';

    // Build treated filename with thumb_id in case there are filename conflicts
    $treated_filename = preg_replace("/.+\/(.+)\.(\w{2,5})$/", $thumb_id."-$1-".$shadow."-".$highlight.".$2", $background_image);
    $treated_image = $base_dir . $treated_filename;

    // If treated file doesn't exist, create it
    if (!file_exists($treated_image)) {

      // If the background directory doesn't exist, create it first
      if(!file_exists($base_dir)) {
        mkdir($base_dir);
      }
      $convert_command = (WP_ENV==='development') ? '/usr/local/bin/convert' : '/usr/bin/convert';
      exec($convert_command.' '.$background_image.' +profile "*" -quality 65 -modulate 100,0 -size 256x1! gradient:#'.$shadow.'-#'.$highlight.' -clut '.$treated_image);
    }
    $header_bg = ' style="background-image:url(' . $upload_dir['baseurl'] . '/backgrounds/' . $treated_filename . ');"';
  }
  return $header_bg;
}

/**
 * Get thumbnail image for post
 * @param  integer $post_id
 * @return string image URL
 */
function get_post_thumbnail($post_id, $size='medium') {
  $return = false;
  if (has_post_thumbnail($post_id)) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    $return = $thumb[0];
  }
  return $return;
}


/**
 * Allow SVG files to be uplaoded via media uploader
 */
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', __NAMESPACE__ . '\\cc_mime_types');

/**
 * Delete background images when attachment is deleted
 */
add_action('delete_attachment', __NAMESPACE__ . '\delete_background_images');
function delete_background_images($post_id) {
  // Get attachment image metadata
  $metadata = wp_get_attachment_metadata($post_id);
  if (!$metadata || empty($metadata['file']))
    return;

  $pathinfo = pathinfo($metadata['file']);
  $upload_dir = wp_upload_dir();
  $base_dir = trailingslashit($upload_dir['basedir']) . 'backgrounds/';
  $files = scandir($base_dir);

  foreach($files as $file) {
    // If filename matches background file, delete it
    if (strpos($file,$pathinfo['filename']) !== false) {
      @unlink($base_dir . '/' . $file);
    }
  }
}

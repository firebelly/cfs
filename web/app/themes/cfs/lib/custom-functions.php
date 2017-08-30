<?php

namespace Firebelly\Utils;

/**
 * Bump up # search results
 */
function search_queries( $query ) {
  if ( !is_admin() && is_search() ) {
    $query->set( 'posts_per_page', 40 );
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\\search_queries' );

/**
 * Custom li'l excerpt function
 */
function get_excerpt( $post, $length=15, $force_content=false ) {
  $excerpt = trim($post->post_excerpt);
  if (!$excerpt || $force_content) {
    $excerpt = $post->post_content;
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = apply_filters( 'the_content', $excerpt );
    $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
    $excerpt_length = apply_filters( 'excerpt_length', $length );
    $excerpt = wp_trim_words( $excerpt, $excerpt_length );
  }
  return $excerpt;
}

/**
 * Get top ancestor for post
 */
function get_top_ancestor($post){
  if (!$post) return;
  $ancestors = $post->ancestors;
  if ($ancestors) {
    return end($ancestors);
  } else {
    return $post->ID;
  }
}

/**
 * Get first term for post
 */
function get_first_term($post, $taxonomy='category') {
  $return = false;
  if ($terms = get_the_terms($post->ID, $taxonomy))
    $return = array_pop($terms);
  return $return;
}

/**
 * Get page content from slug
 */
function get_page_content($slug) {
  $return = false;
  if ($page = get_page_by_path($slug))
    $return = apply_filters('the_content', $page->post_content);
  return $return;
}

/**
 * Get category for post
 */
function get_category($post) {
  if ($category = get_the_category($post)) {
    return $category[0];
  } else return false;
}

/**
 * Get num_pages for category given slug + per_page
 */
function get_total_pages($category, $per_page) {
  $cat_info = get_category_by_slug($category);
  $num_pages = ceil($cat_info->count / $per_page);
  return $num_pages;
}

/**
 * Get Secondary header image
 */
function get_secondary_header($post) {
  if (empty(get_post_meta($post->ID, '_cmb2_secondary_featured_image', true))) {
    return false;
  }
  $secondary_bg_id = get_post_meta($post->ID, '_cmb2_secondary_featured_image_id', true);
  $secondary_bg_image = get_attached_file($secondary_bg_id, false);
  $secondary_bg = \Firebelly\Media\get_header_bg($secondary_bg_image, '', 'bw', 'banner_image');
  return $secondary_bg;
}

/**
 * Breadcrumbs
 */

function fb_crumbs() {
  $separator = '>';
  if (is_front_page()) return '';
  $return = '<nav class="crumb"><a href="'.home_url().'">Home</a>';
  if (is_category() || is_single()) {
      $return .= " {$separator} ";
      $return .= get_the_category(" {$separator} ");
          if (is_single()) {
              $return .= " {$separator} ";
              $return .= get_the_title();
          }
  } elseif (is_page()) {
      $return .= " {$separator} ";
      $return .= get_the_title();
  } elseif (is_search()) {
      $return .= " {$separator} Search: ";
      $return .= the_search_query();
  }
  $return .= '</nav>';
  return $return;
}


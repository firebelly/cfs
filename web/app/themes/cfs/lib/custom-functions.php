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
  $secondary_bg = \Firebelly\Media\get_header_bg($secondary_bg_image);
  return $secondary_bg;
}

/**
 * Breadcrumbs
 */

function fb_crumbs() {
  global $post;
  $separator = '/';
  if (is_front_page()) return '';
  $return = '<nav class="crumb"><a href="'.home_url().'">Home</a>';
  if (is_404()) {
    $return .= " {$separator} 404";
  } else if (is_category()) {
    $return .= get_the_category(" {$separator} ");
  } else if (is_post_type_archive('workshop') || is_tax('workshop_series')) {
    $workshop_page = get_page_by_path('/workshops-trainings/');
    $return .= " {$separator} <a href=\"" . get_permalink($workshop_page) . '">' . $workshop_page->post_title . "</a> {$separator} Upcoming Workshops";
  } else if (is_singular('workshop')) {
    $parent_page = get_page_by_path('/workshops-trainings/');
    $return .= " {$separator} <a href=\"" . get_permalink($parent_page) . '">' . $parent_page->post_title . "</a> {$separator} " . get_the_title();
  } else if (is_singular('program')) {
    $parent_page = get_page_by_path('/programs/');
    $return .= " {$separator} <a href=\"" . get_permalink($parent_page) . '">' . $parent_page->post_title . "</a> {$separator} " . get_the_title();
  } else if (is_singular('post')) {
    // todo: check for post_type and if not page, show link to listing page (e.g. /programs/)
    $return .= " {$separator} " . get_the_title();
  } elseif (is_page()) {
    if ($post->post_parent) {
      $parent_page = get_page($post->post_parent);
      $return .= "{$separator} <a href=\"".get_permalink($parent_page).'">' . get_the_title($parent_page) . '</a>';
    }
    $return .= " {$separator} " . get_the_title();
  } elseif (is_search()) {
    $return .= " {$separator} Search: " . get_search_query();
  }
  $return .= '</nav>';
  return $return;
}

/**
 * Get accordions HTML for post
 */
function get_accordions($post) {
  if (!is_object($post) || empty($post->ID)) return;
  $accordions = get_post_meta($post->ID, '_cmb2_accordions', true);
  if (!$accordions) return '';
  $accordions_html = '<div class="accordion fb-accordion" role="tablist" aria-multiselectable="true">';
  foreach ($accordions as $accordion) {
    $i = 1;

    if (!empty($accordion['accordion_title'])) {
      // Accordion title
      $accordions_html .= '<h3 id="accordion-t'.$i.'" class="accordion-title" role="tab" aria-controls="accordion-c'.$i.'" aria-selected="false" aria-expanded="false" tabindex="0">'.$accordion['accordion_title'].'<svg class="icon icon-arrow-right" aria-hidden="hidden" role="image"><use xlink:href="#icon-arrow-right"></use></svg></h3>';
    }
    // Accordion content
    $accordions_html .= '<div id="accordion-c'.$i.'" class="accordion-content" role="tabpanel" aria-labelledby="accordion-t'.$i.'" aria-hidden="true" style="display:none">';
    // Main body
    if (!empty($accordion['accordion_body'])) {
      $accordions_html .= '<div class="accordion-body user-content">'.apply_filters('the_content', $accordion['accordion_body']).'</div>';
    }

    // Check for media blocks

    // Video URL
    if (!empty($accordion['video_url'])) {
      $accordions_html .= '<div class="media-block video">'.$accordion['video_url'].'</div>';
    }
    // Image(s) + optional caption
    if (!empty($accordion['images'])) {
      $accordions_html .= '<div class="media-block images"><figure>';
      foreach ( (array)$accordion['images'] as $attachment_id => $attachment_url ) {
        $accordions_html .= '<img src="' . \Firebelly\Media\get_header_bg($attachment_url, ['thumb_id' => $attachment_id, 'size' => 'medium_large', 'output' => 'image']) . '">';
      }
      if (!empty($accordion['pullquote'])) {
        $accordions_html .= '<figcaption class="image-caption">'.$accordion['pullquote'].'</figcaption>';
      }
      $accordions_html .= '</figure></div>';
    } else if (!empty($accordion['pullquote'])) {
      // Standalone pull-quote
      $accordions_html .= '<div class="media-block pull-quote"><blockquote><p>'.$accordion['pullquote'].'</p>';
      $accordions_html .= !empty($accordion['pullquote_author']) ? ' <cite>– '.$accordion['pullquote_author'].'</cite>' : '';
      $accordions_html .= '</blockquote></div>';
    }
    // Stat figure + optional label
    if (!empty($accordion['stat_figure'])) {
      $accordions_html .= '<div class="media-block stat"><dl><dd>'.$accordion['stat_figure'].'</dd>';
      if (!empty($accordion['stat_label'])) {
        $accordions_html .= '<dt>'.$accordion['stat_label'].'</dt>';
      }
      $accordions_html .= '</dl></div>';
    }

    $accordions_html .= '</div><!-- /.accordion-content -->';
    $i++;
  }
  $accordions_html .= '</div><!-- /.fb-accordion -->';

  return $accordions_html;
}

function get_registration_details($post) {
  if (empty($post->meta)) $post->meta = get_post_meta($post->ID);
  $now = time();
  $output = '<div class="registration">';
  $output .= '<ul class="details"><li>';
  if (!empty($post->meta['_cmb2_date_start'])) {
    $output .= '<time datetime="' . date('Y-m-d', $post->meta['_cmb2_date_start'][0]) . '">' . date('m/j/y', $post->meta['_cmb2_date_start'][0]) . '</time>';
  }
  if (!empty($post->meta['_cmb2_date_end'])) {
    $output .= '– <time datetime="' . date('Y-m-d', $post->meta['_cmb2_date_end'][0]) . '">' . date('m/j/y', $post->meta['_cmb2_date_end'][0]) . '</time>';
  }
  $output .= '</li>';
  if (!empty($post->meta['_cmb2_registration_deadline'])) {
    $output .= '<li class="applications-due">Applications Due ' . date('Y-m-d', $post->meta['_cmb2_registration_deadline'][0]) . '</li>';
  }
  if (!empty($post->meta['_cmb2_age_minimum']) && !empty($post->meta['_cmb2_age_maximum'])) {
    $output .= '<li class="ages">Age of Applicants: ' . $post->meta['_cmb2_age_minimum'][0] . ' – ' . $post->meta['_cmb2_age_maximum'][0] . '</li>';
  }
  $output .= '</ul>';
  // Check if applications open
  if (1 || ($now > $post->meta['_cmb2_registration_opens'] && $now < $post->meta['_cmb2_registration_deadline'])) {
    $output .= '<a class="button -wide" href="#">Apply</a>';
  }
  $output .= '</div>';
  return $output;
}

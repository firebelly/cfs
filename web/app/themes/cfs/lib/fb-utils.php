<?php

namespace Firebelly\Utils;

/**
 * Custom li'l excerpt function
 */
function get_excerpt($post, $length=15, $force_content=false) {
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
  $return = '<nav class="crumb"><a href="'.home_url().'">Home</a> ';

  if ($post->post_name=='moments-of-justice') {
    // Omg help, mega kludgetastical — MOJ is a workshop in a strange land
    $return .= "{$separator} <a href=\"/support-us/\">Support Us</a> {$separator} " . $post->post_title;
  } else if (is_404()) {
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
    $return .= " {$separator} Search Results";
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
    $always_open = (!empty($accordion['accordion_always_open']) ? ' always-open open' : '');
    if (!empty($accordion['accordion_title'])) {
      // Accordion title
      $accordions_html .= '<h3 id="accordion-t'.$i.'" class="accordion-title'.$always_open.'" role="tab" aria-controls="accordion-c'.$i.'" aria-selected="false" aria-expanded="false" tabindex="0">'.$accordion['accordion_title'].'<svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"></use></svg></h3>';
    }
    // Accordion content
    $accordions_html .= '<div id="accordion-c'.$i.'" class="accordion-content'.$always_open.'" role="tabpanel" aria-labelledby="accordion-t'.$i.'"'.($always_open ? '' : ' aria-hidden="true" style=" display:none"').'>';
    // Main body
    if (!empty($accordion['accordion_body'])) {
      $accordions_html .= '<div class="accordion-body user-content">'.apply_filters('the_content', $accordion['accordion_body']).'</div>';
    }

    // Check for media blocks

    // Video URL
    if (!empty($accordion['video_url'])) {
      $accordions_html .= '<div class="media-block '.($always_open ? 'active' : '').' video">'.wp_oembed_get($accordion['video_url']).'</div>';
    }
    // Image(s) + optional caption
    if (!empty($accordion['images'])) {
      $accordions_html .= '<div class="media-block '.($always_open ? 'active' : '').' images"><figure>';
      foreach ( (array)$accordion['images'] as $attachment_id => $attachment_url ) {
        // Youth Program posts get untreated image
        if (\Firebelly\Init\is_youth_program()) {
          if ($image = wp_get_attachment_image_src($attachment_id, 'medium_large')) {
            $accordions_html .= '<img src="' . $image[0] . '">';
          }
        } else {
          $accordions_html .= '<img src="' . \Firebelly\Media\get_header_bg($attachment_url, ['thumb_id' => $attachment_id, 'size' => 'medium_large', 'output' => 'image']) . '">';
        }
      }
      if (!empty($accordion['pullquote'])) {
        $accordions_html .= '<figcaption class="image-caption">'.$accordion['pullquote'].'</figcaption>';
      }
      $accordions_html .= '</figure></div>';
    } else if (!empty($accordion['pullquote'])) {
      // Standalone pull-quote
      $accordions_html .= '<div class="media-block '.($always_open ? 'active' : '').' pull-quote"><blockquote><p>'.$accordion['pullquote'].'</p>';
      $accordions_html .= !empty($accordion['pullquote_author']) ? ' <cite>– '.$accordion['pullquote_author'].'</cite>' : '';
      $accordions_html .= '</blockquote></div>';
    }
    // Stat figure + optional label
    if (!empty($accordion['stat_figure'])) {
      $accordions_html .= '<div class="media-block '.($always_open ? 'active' : '').' stat"><dl><dd>'.$accordion['stat_figure'].'</dd>';
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
  $output .= \Firebelly\Utils\get_dates($post);
  $output .= '</li>';
  if (!empty($post->meta['_cmb2_registration_opens']) && $post->meta['_cmb2_registration_opens'][0] > $now) {
    $output .= '<li class="applications-due">Applications Open ' . date('m/d/Y', $post->meta['_cmb2_registration_opens'][0]) . '</li>';
  }
  if (!empty($post->meta['_cmb2_registration_deadline']) && $post->meta['_cmb2_registration_deadline'][0] > $now) {
    $output .= '<li class="applications-due">Applications Due ' . date('m/d/Y', $post->meta['_cmb2_registration_deadline'][0]) . '</li>';
  }
  if (!empty($post->meta['_cmb2_age_minimum']) && !empty($post->meta['_cmb2_age_maximum'])) {
    $output .= '<li class="ages">Ages ' . $post->meta['_cmb2_age_minimum'][0] . ' – ' . $post->meta['_cmb2_age_maximum'][0] . '</li>';
  }
  if (!empty($post->meta['_cmb2_address'])) {
    $output .= '<li class="address">';
    $output .= !empty($post->meta['_cmb2_venue'][0]) ? $post->meta['_cmb2_venue'][0].'<br>' : '';
    $address = wp_parse_args(unserialize($post->meta['_cmb2_address'][0]), [
      'address-1' => '',
      'address-2' => '',
      'city'      => '',
      'state'     => '',
      'zip'       => '',
    ]);
    $output .= $address['address-1'];
    $output .= !empty($address['address-2']) ? '<br>'.$address['address-2'] : '';
    $output .= !empty($address['city']) ? '<br>'.$address['city'] : '';
    $output .= !empty($address['state']) ? ', '.$address['state'] : '';
    $output .= !empty($address['zip']) ? ' '.$address['zip'] : '';
    $output .= '</li>';
  }

  $output .= '</ul>';
  if ($post->post_type=='program') {
    $output .= \Firebelly\PostTypes\Program\get_registration_button($post);
  } elseif ($post->post_type=='workshop') {
    $output .= \Firebelly\PostTypes\Workshop\get_registration_button($post);
  }
  $output .= '</div>';
  return $output;
}

function get_dates($post) {
  if (empty($post->meta)) $post->meta = get_post_meta($post->ID);
  $output = '<div class="date">';
  if (!empty($post->meta['_cmb2_date_start'])) {
    $output .= '<time datetime="' . date('Y-m-d', $post->meta['_cmb2_date_start'][0]) . '">' . date('m/d/y', $post->meta['_cmb2_date_start'][0]) . '</time>';
  }
  if (!empty($post->meta['_cmb2_date_end']) && (empty($post->meta['_cmb2_date_start']) || date('Y-m-d', $post->meta['_cmb2_date_end'][0]) != date('Y-m-d', $post->meta['_cmb2_date_start'][0]))) {
    if (!empty($post->meta['_cmb2_date_start'])) $output .= ' – ';
    $output .= '<time datetime="' . date('Y-m-d', $post->meta['_cmb2_date_end'][0]) . '">' . date('m/d/y', $post->meta['_cmb2_date_end'][0]) . '</time>';
  }
  if (!empty($post->meta['_cmb2_time'])) {
    $output .= ' <span class="timespan">' . $post->meta['_cmb2_time'][0] . '</span>';
  }
  $output .= '</div>';
  return $output;
}

function pagination($args=[]) {
  $defaults = array(
    'type'      => 'array',
    'nav_class' => 'pagination',
    'prev_text' => __('Prev'),
    'next_text' => __('Next'),
    'li_class'  => ''
  );
  $args = wp_parse_args( $args, $defaults );
  $page_links = paginate_links( $args );
  if ( $page_links ) {
    $r = '';
    $ul_class = empty($args['ul_class']) ? '' : ' ' . $args['ul_class'];
    $r .= '<nav class="'. $args['nav_class'] .'" aria-label="navigation">' . "\n\t";
    $r .= '<ul>' . "\n";
    foreach ($page_links as $link) {
      $li_classes = !empty($args['li_class']) ? explode(' ', $args['li_class']) : [];
      strpos($link, 'current') !== false ? array_push($li_classes, 'active') : ( strpos($link, 'dots') !== false ? array_push($li_classes, 'disabled') : '' );
      $class = empty($li_classes) ? '' : ' class="' . join(" ", $li_classes) . '"';
      $after_number = !preg_match('/prev|next|dots/', $link) ? ',' : '';
      $r .= "\t\t" . '<li' . $class . '>' . $link . $after_number . '</li>' . "\n";
      // If dots link, remove previous comma
      if (strpos($link, 'dots') !== false) {
        $r = strrev(implode(strrev(''), explode(strrev(','), strrev($r), 2)));
      }
    }
    $r .= "\t</ul>";
    $r .= "\n</nav>";
    // Remove last comma
    $r = strrev(implode(strrev(''), explode(strrev(','), strrev($r), 2)));
    return $r;
  }
}

/**
 * Edit post link for various front end areas
 */
function admin_edit_link($post_or_term) {
  if (!empty($post_or_term->term_id)) {
    $link = get_edit_term_link($post_or_term->term_id);
  } else {
    $link = get_edit_post_link($post_or_term->ID);
  }
  return !empty($link) ? '<a class="edit-link" href="'.$link.'">Edit</a>' : '';
}

/**
 * Edit post link for cronjobs
 */
function cronjob_edit_link($id=0, $context = 'display') {
  if (!$post = get_post($id))
    return;
  $action = '&action=edit';

  $post_type_object = get_post_type_object($post->post_type);
  if (!$post_type_object)
    return;

  return apply_filters('get_edit_post_link', admin_url(sprintf($post_type_object->_edit_link . $action, $post->ID)), $post->ID, $context);
}
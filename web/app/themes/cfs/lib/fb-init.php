<?php

namespace Firebelly\Init;
use Roots\Sage\Assets;

/**
 * Bump up # search results
 */
// function search_queries( $query ) {
//   if ( !is_admin() && is_search() ) {
//     $query->set( 'posts_per_page', 40 );
//   }
//   return $query;
// }
// add_filter( 'pre_get_posts', __NAMESPACE__ . '\\search_queries' );

/**
 * Don't run wpautop before shortcodes are run! wtf Wordpress. from http://stackoverflow.com/a/14685465/1001675
 */
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop' , 99);
add_filter('the_content', 'shortcode_unautop',100);

/**
 * Various theme defaults
 */
function setup() {
  // Default Image options
  update_option('image_default_align', 'none');
  update_option('image_default_link_type', 'none');
  update_option('image_default_size', 'large');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/*
 * Tiny MCE options
 */
function mce_buttons_2($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', __NAMESPACE__ . '\\mce_buttons_2');

function simplify_tinymce($settings) {
  // What goes into the 'formatselect' list
  $settings['block_formats'] = 'H2=h2;H3=h3;Paragraph=p';

  $settings['inline_styles'] = 'false';
  if (!empty($settings['formats']))
    $settings['formats'] = substr($settings['formats'],0,-1).",underline: { inline: 'u', exact: true} }";
  else
    $settings['formats'] = "{ underline: { inline: 'u', exact: true} }";

  // What goes into the toolbars. Add 'wp_adv' to get the Toolbar toggle button back
  $settings['toolbar1'] = 'styleselect,bold,italic,underline,strikethrough,formatselect,bullist,numlist,blockquote,link,unlink,hr,wp_more,outdent,indent,AccordionShortcode,AccordionItemShortcode,fullscreen';
  $settings['toolbar2'] = '';
  $settings['toolbar3'] = '';
  $settings['toolbar4'] = '';

  // $settings['autoresize_min_height'] = 250;
  $settings['autoresize_max_height'] = 1000;

  // Clear most formatting when pasting text directly in the editor
  $settings['paste_as_text'] = 'true';

  $style_formats = array(
    // array(
    //   'title' => 'Two Column',
    //   'block' => 'div',
    //   'classes' => 'two-column',
    //   'wrapper' => true,
    // ),
    array(
      'title' => 'Button',
      'block' => 'span',
      'classes' => 'button',
    ),
    // array(
    //   'title' => '» Arrow Link',
    //   'block' => 'span',
    //   'classes' => 'arrow-link',
    // ),
 );
  $settings['style_formats'] = json_encode($style_formats);

  return $settings;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\simplify_tinymce');

/**
 * Clean up content before saving post
 */
function clean_up_content($content) {
  // Convert <span class="button"><a></span> to <a class="button"> (can't just add class to element w/ tinymce style formats, has to have wrapper)
  $content = preg_replace('/<span class=\\\"button\\\"><a(.*)<\/a><\/span>/', '<a class=\"button\"$1</a>', $content);
  return $content;
}
add_filter('content_save_pre', __NAMESPACE__ . '\\clean_up_content', 10, 1);
// ... and support for cmb2 wysiwyg fields:
function cmb2_sanitize_wysiwyg_callback($override_value, $content) {
  $content = preg_replace('/<span class=\\\"button\\\"><a(.*)<\/a><\/span>/', '<a class=\"button\"$1</a>', $content);
  return $content;
}
add_filter('cmb2_sanitize_wysiwyg', __NAMESPACE__ . '\\cmb2_sanitize_wysiwyg_callback', 10, 2);

/**
 * Remove unused Customize link from admin bar
 */
add_action( 'wp_before_admin_bar_render', function() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('customize');
});

/**
 * Default options for all accordion shortcodes
 */
add_filter('shortcode_atts_accordion', function($atts) {
  $atts['clicktoclose'] = true;
  $atts['autoclose'] = false;
  return $atts;
}, 10, 3);

/**
 * Custom Admin styles + JS
 */
add_action('admin_enqueue_scripts', function($hook){
  wp_enqueue_style('fb_wp_admin_css', Assets\asset_path('styles/admin.css'));
  wp_enqueue_script('fb_wp_admin_js', Assets\asset_path('scripts/admin.js'), ['jquery'], null, true);
}, 100);

/**
 * Function to determine if we're on a yellow-themed Youth Program page
 */
function is_youth_program() {
  global $post;
  // Are we on a program page (all are youth programs), or Alumni Resources?
  if ((is_single() && $post->post_type=='program') ||
      (is_page() && $post->post_title == 'Alumni Resources')) {
    return true;
  }
  return false;
}

/**
 * Add theme-yellow class if on Youth Program page
 */
add_filter('body_class', function($classes) {
  if (is_youth_program()) {
    $classes[] = 'theme-yellow';
  }
  return $classes;
});


/**
 * Remove labels from archive/category titles
 */
add_filter( 'get_the_archive_title', function($title) {
  if ( is_category() ) {
      $title = single_cat_title( '', false );
  } elseif ( is_tag() ) {
      $title = single_tag_title( '', false );
  } elseif ( is_author() ) {
      $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif ( is_post_type_archive() ) {
      $title = post_type_archive_title( '', false );
  } elseif ( is_tax() ) {
      $title = single_term_title( '', false );
  }
  return $title;
} );

/**
 * Also search postmeta
 */
function search_join($join) {
  global $wpdb;
  if (is_search()) {
    $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
  }
  return $join;
}
add_filter('posts_join', __NAMESPACE__ . '\search_join');

function search_where($where) {
  global $wpdb;
  if (is_search()) {
    $where = preg_replace(
      "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
      "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where);
  }
  return $where;
}
add_filter('posts_where', __NAMESPACE__ . '\search_where');

function search_distinct($where) {
  global $wpdb;
  if (is_search()) {
    return "DISTINCT";
  }
  return $where;
}
add_filter('posts_distinct', __NAMESPACE__ . '\search_distinct');


/**
 * Add custom links to the admin bar (trying to maintain the Edit Page link-at-top-of-page paradigm as much as possible)
 */
function custom_admin_bar() {
  global $wp_admin_bar, $post;

  // If workshop archive/taxonomy page, add Edit Page link to edit Upcoming Workshops post
  if (!is_admin() && (is_post_type_archive('workshop') || is_tax('workshop_series'))) {
    $workshop_post = get_page_by_title('Upcoming Workshops');
    $wp_admin_bar->add_menu( array(
      'parent' => false,
      'id' => 'edit',
      'title' => 'Edit Page',
      'href' => get_edit_post_link($workshop_post->ID),
    ));
  }

  // Moments of Justice is an odd duck, a page showing a workshop post, so show Edit Workshop link instead
  if (is_singular('page') && preg_match('/Moments of Justice/',$post->post_title)) {
    $wp_admin_bar->remove_menu('edit');
    $workshop_post = \Firebelly\PostTypes\Workshop\get_workshop_like_title('Moments of Justice');
    $wp_admin_bar->add_menu( array(
      'parent' => false,
      'id' => 'edit',
      'title' => 'Edit Workshop',
      'href' => get_edit_post_link($workshop_post->ID),
    ));
  }
}
add_action('wp_before_admin_bar_render', __NAMESPACE__ . '\custom_admin_bar');

/**
 * Remove unused Posts link in WP admin sidebar (CFS has no blog)
 */
add_action('admin_menu', function() {
  remove_menu_page('edit.php');
});

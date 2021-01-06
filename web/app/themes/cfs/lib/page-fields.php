<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\Fields\Pages;

add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\press_tab_metaboxes' );
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\media_assets_group_metabox_register' );
function metaboxes() {
  $prefix = '_cmb2_';

  /**
    * Page intro fields
    */
  $page_intro = new_cmb2_box([
    'id'            => $prefix . 'page_intro',
    'title'         => esc_html__( 'Page Intro', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Title', 'cmb2' ),
    'id'   => $prefix .'intro_title',
    'type' => 'text',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Quote', 'cmb2' ),
    'id'   => $prefix .'intro_quote',
    'type' => 'textarea_small',
  ]);

  /**
    * Parent page navigation fields
    */
  $parent_page_navigation = new_cmb2_box([
    'id'            => 'landing_page_navigation',
    'title'         => __( 'Landing Page Navigation', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'side',
    'priority'      => 'low',
    // 'closed'        => true,
    'show_on_cb'    => '\Firebelly\CMB2\cmb_is_child_page',
  ]);
  $parent_page_navigation->add_field([
    'name' => esc_html__( 'Nav Excerpt', 'cmb2' ),
    'id'   => $prefix .'nav_excerpt',
    'type' => 'textarea_small',
    'desc' => 'These are shown on the parent page\'s secondary navigation.',
  ]);
  $parent_page_navigation->add_field([
    'name' => esc_html__( 'Nav Button Text', 'cmb2' ),
    'id'   => $prefix .'nav_button_text',
    'type' => 'text',
    'desc' => 'e.g. Join the Movement',
  ]);
  $parent_page_navigation->add_field([
    'name' => esc_html__( 'Nav Button Link', 'cmb2' ),
    'id'   => $prefix .'nav_button_link',
    'type' => 'text',
    'desc' => 'Overrides link to child page if set, e.g. /program/freedom-fellowships/',
  ]);

  /**
    * Homepage fields
    */
  $homepage_fields = new_cmb2_box([
    'id'            => 'secondary_content',
    'title'         => __( 'Custom Featured Block', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'front-page.php'],
    'priority'      => 'high',
    'show_names'    => true,
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Image', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_image',
    'type' => 'file',
    'options' => [
      'url' => false, // Hide the text input for the url
    ],
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Title', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_title',
    'type' => 'text',
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Body', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_body',
    'type' => 'wysiwyg',
    'options' => [
      'textarea_rows' => 8,
    ],
  ]);
  $homepage_fields->add_field([
    'name' => esc_html__( 'Custom Featured Link', 'cmb2' ),
    'id'   => $prefix . 'custom_featured_link',
    'type' => 'text_url',
    'desc' => 'e.g. http://foo.com/',
  ]);

  /**
    * Donate page fields
    */
  $donate_multiple_fields = new_cmb2_box([
    'id'            => 'donation_multiple_fields',
    'title'         => __( 'Monthly Donation Options', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'priority'      => 'core',
    'show_names'    => true,
  ]);
  $group_field_id = $donate_multiple_fields->add_field( array(
    'id'          => 'donation_multiple_options',
    'type'        => 'group',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'options'     => array(
      'group_title'   => __( 'Option {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Option', 'cmb2' ),
      'remove_button' => __( 'Remove Option', 'cmb2' ),
      'sortable'      => true,
    ),
  ) );
  $donate_multiple_fields->add_group_field( $group_field_id, array(
    'name' => 'Amount',
    'id'   => 'amount',
    'type' => 'text',
    'row_classes' => '-half',
  ) );
  $donate_multiple_fields->add_group_field( $group_field_id, array(
    'name' => 'Description',
    'id'   => 'description',
    'type' => 'text',
  'row_classes' => '-half',
  ) );

  $donate_gift_fields = new_cmb2_box([
    'id'            => 'donation_gift_fields',
    'title'         => __( 'Multiple Donation Gift Options', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'priority'      => 'core',
    'show_names'    => true,
  ]);
  $group_field_id = $donate_gift_fields->add_field( array(
    'id'          => 'donation_gift_options',
    'type'        => 'group',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'options'     => array(
      'group_title'   => __( 'Option {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
      'add_button'    => __( 'Add Another Option', 'cmb2' ),
      'remove_button' => __( 'Remove Option', 'cmb2' ),
      'sortable'      => true,
    ),
  ) );
  $donate_gift_fields->add_group_field( $group_field_id, array(
    'name' => 'Description',
    'id'   => 'description',
    'type' => 'text',
  ) );

  $donate_single_fields = new_cmb2_box([
    'id'            => 'donation_single_fields',
    'title'         => __( 'Single Donation Options', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'priority'      => 'core',
    'show_names'    => true,
  ]);
  $group_field_id = $donate_single_fields->add_field( array(
    'id'          => 'donation_single_options',
    'type'        => 'group',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'options'     => array(
      'group_title'   => __( 'Option {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
      'add_button'    => __( 'Add Another Option', 'cmb2' ),
      'remove_button' => __( 'Remove Option', 'cmb2' ),
      'sortable'      => true,
    ),
  ) );
  $donate_single_fields->add_group_field( $group_field_id, array(
    'name' => 'Amount',
    'id'   => 'amount',
    'type' => 'text',
    'row_classes' => '-half',
  ) );
  $donate_single_fields->add_group_field( $group_field_id, array(
    'name' => 'Description',
    'id'   => 'description',
    'sanitization_cb' => __NAMESPACE__.'\sanitize_text_callback',
    'type' => 'text',
    'row_classes' => '-half',
  ) );
}

/**
 *  Press tabs: News/Press Releases
 */
function press_tab_metaboxes() {
	$prefix = '_cmb2_';

  // News
  $news_repeat_metabox = new_cmb2_box(array(
    'id' => $prefix . 'news',
    'title' => __( 'News', 'cmb2' ),
    'object_types' => array( 'page' ), // post type
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-press.php' ),
    'context' => 'normal',
    'show_names' => true,
    'priority' => 'high',
    'save_button' => 'Save Settings',
    'repeatable' => true,
    'sortable' => true
  ));

  // Repeatable group
  $news_metaboxes = $news_repeat_metabox->add_field([
    'id' => $prefix . 'news_tabs',
    'type' => 'group',
    'options' => array(
      'group_title' => __( 'Article', 'cmb2' ) . ' {#}', // {#} gets replaced by row number
      'add_button' => __( 'Add Article', 'cmb2' ),
      'remove_button' => __( 'Remove Article', 'cmb2' ),
      'sortable' => true, // beta
    ),
  ]);

  // News Metaboxes
  $news_repeat_metabox->add_group_field( $news_metaboxes, array(
    'name' => __( 'Headline', 'cmb2' ),
    'id' => $prefix . 'headline',
    'desc' => __('Article headline', 'cmb2'),
    'type' => 'text',
  ) );

  $news_repeat_metabox->add_group_field( $news_metaboxes, array(
    'name' => __( 'Subine', 'cmb2' ),
    'desc' => __('Excerpt attained by the article', 'cmb2'),
    'id' => $prefix . 'subline',
    'type' => 'textarea_small',
  ) );

  $news_repeat_metabox->add_group_field( $news_metaboxes, array(
    'name' => __( 'Link', 'cmb2' ),
    'desc' => __( 'Link field for the article', 'cmb2' ),
    'id' => $prefix . 'link',
    'type' => 'text_url',
  ));
  $news_repeat_metabox->add_group_field( $news_metaboxes, array(
    'name' => esc_html__( 'Date Published', 'cmb2' ),
    'id' => $prefix . 'date_published',
    'type' => 'text_date_timestamp',
    'date_format' => 'l jS \of F Y',
  ));
  $news_repeat_metabox->add_group_field( $news_metaboxes, array(
    'name' => __( 'Featured Image', 'cmb2' ),
    'desc' => 'Upload an image or enter an URL.',
    'id' => $prefix . 'featured_img',
    'type' => 'file',
    'options' => array(
      'url' => false,
    ),
    'text' => array(
      'add_upload_file_text' => 'Add Image'
    ),
    'query_args' => array(
      'type' => array(
        'image/jpeg',
        'image/png',
      ),
    ),
    'preview_size' => 'medium', // Image size to use when previewing in the admin.
  ));

  // Press Release
  $press_release_repeat_metabox = new_cmb2_box(array(
    'id' => $prefix . 'press_release',
    'title' => __( 'Press Releases', 'cmb2' ),
    'object_types' => array( 'page' ), // post type
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-press.php' ),
    'context' => 'normal',
    'show_names' => true,
    'priority' => 'high',
    'save_button' => 'Save Settings',
    'repeatable' => true,
    'sortable' => true
  ));

  // Repeatable group
  $pr_metaboxes = $press_release_repeat_metabox->add_field([
    'id' => $prefix . 'press_release_section',
    'type' => 'group',
    'options' => array(
      'group_title' => __( 'Post', 'cmb2' ) . ' {#}', // {#} gets replaced by row number
      'add_button' => __( 'Add Post', 'cmb2' ),
      'remove_button' => __( 'Remove Post', 'cmb2' ),
      'sortable' => true, // beta
    ),
  ]);

  // News Metaboxes
  $press_release_repeat_metabox->add_group_field( $pr_metaboxes, array(
    'name' => __( 'Title', 'cmb2' ),
    'id' => $prefix . 'title',
    'type' => 'text',
  ) );

  $press_release_repeat_metabox->add_group_field( $pr_metaboxes, array(
    'name' => __( 'Post Image', 'cmb2' ),
    'desc' => 'Upload an image or enter an URL.',
    'id' => $prefix . 'post_img',
    'type' => 'file',
    'options' => array(
      'url' => false,
    ),
    'text' => array(
      'add_upload_file_text' => 'Add Image'
    ),
    'query_args' => array(
      'type' => array(
        'image/jpeg',
        'image/png',
      ),
    ),
    'preview_size' => 'medium', // Image size to use when previewing in the admin.
  ));

  $press_release_repeat_metabox->add_group_field( $pr_metaboxes, array(
    'name' => __( 'Post Body', 'cmb2' ),
    'id' => $prefix . 'post_body',
    'type' => 'wysiwyg',
    'options' => array(),
  ) );

  $press_release_repeat_metabox->add_group_field( $pr_metaboxes, array(
    'name' => esc_html__( 'Press Release Link', 'cmb2' ),
    'id' => $prefix . 'press_release_link',
    'type' => 'text_url',
    'desc' => 'e.g. http://foo.com/',
  ) );

  $press_release_repeat_metabox->add_group_field( $pr_metaboxes, array(
    'name' => esc_html__( 'Post Published', 'cmb2' ),
    'id' => $prefix . 'post_published',
    'type' => 'text_date_timestamp',
    'date_format' => 'l jS \of F Y',
  ));

}

function media_assets_group_metabox_register() {
  $prefix = '_cmb2_';

  $cmb_repeat_metaboxes = new_cmb2_box(array(
    'id' => $prefix . 'media_assets',
    'title' => __( 'Media Assets', 'cmb2' ),
    'object_types' => array( 'page' ), // post type
    'show_on' => array( 'key' => 'page-template', 'value' => 'page-press.php' ),
    'context' => 'normal',
    'show_names' => true,
  ));

  // Repeatable group
  $group_repeat_test = $cmb_repeat_metaboxes->add_field([
    'id' => $prefix . 'assets',
    'type' => 'group',
    'options' => array(
      'group_title' => __( 'Assets', 'cmb2' ) . ' {#}', // {#} gets replaced by row number
      'add_button' => __( 'Add Assets', 'cmb2' ),
      'remove_button' => __( 'Remove Asset', 'cmb2' ),
      'sortable' => true, // beta
    ),
  ]);

  // Logo Titles
  $cmb_repeat_metaboxes->add_group_field( $group_repeat_test, array(
    'name' => __( 'Logo Title', 'cmb2' ),
    'id' => $prefix . 'logo_title',
    'type' => 'text',
  ) );

  // Logos
  $cmb_repeat_metaboxes->add_group_field( $group_repeat_test, array(
    'name' => __( 'Brand Logo', 'cmb2' ),
    'desc' => 'Upload logos of different sizes and colors that coincide with the brand',
    'id' => $prefix . 'brand_logo',
    'type' => 'file',
    'options' => array(
      'url' => false,
    ),
    'text' => array(
      'add_upload_file_text' => 'Add Image'
    ),
    'query_args' => array(
      'type' => array(
        'image/jpeg',
        'image/png',
      ),
    ),
    'preview_size' => 'medium', // Image size to use when previewing in the admin.
  ));
}

function sanitize_text_callback( $value, $field_args, $field ) {
  $value = strip_tags( $value, '<b><strong><i><em>' );
  return $value;
}

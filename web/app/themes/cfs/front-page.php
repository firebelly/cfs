<?php
/*
  Template name: Homepage
*/

$header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
if (!$header_video) {
  $header_bg = \Firebelly\Media\get_header_bg($post, false, '', 'color', 'banner_image');
} else {
  $header_bg = '';
}
$page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);

// Get featured posts
$featured_args = [
  'post_type'  => 'program',
  'order'      => 'ASC',
  'orderby'    => 'meta_value_num',
  'meta_key'   => '_cmb2_date_start',
  'meta_query' => [
    [
      'key'    => '_cmb2_featured',
      'value'  => 'on',
    ],
  ]
];
$featured_programs = get_posts($featured_args);
$featured_program = ($featured_programs) ? $featured_programs[0] : null;

$featured_workshops = get_posts(array_merge($featured_args, ['post_type' => 'workshop']));
$featured_workshop = ($featured_workshops) ? $featured_workshops[0] : null;

// Custom featured block fields
$custom_featured_title = get_post_meta($post->ID, '_cmb2_custom_featured_title', true);
$custom_featured_body = get_post_meta($post->ID, '_cmb2_custom_featured_body', true);
$custom_featured_image = get_post_meta($post->ID, '_cmb2_custom_featured_image', true);
$custom_featured_link = get_post_meta($post->ID, '_cmb2_custom_featured_link', true);
?>

<div class="page-header" <?= $header_bg ?>>
  <?php if ($header_video): ?>
  <div class="background-video-wrapper">
    <video class="background-video" playsinline autoplay muted loop poster="">
      <source src="<?= $header_video ?>" type="video/mp4">
    </video>
  </div>
  <?php endif; ?>
  <div class="wrap">
    <h2><?php bloginfo('description'); ?></h2>
  </div>
</div>

<?= Firebelly\Utils\fb_crumbs(); ?>

<div class="page-intro">
  <div class="page-intro-content">
    <div class="-inner">
      <div class="page-content user-content grid">
        <div class="one-half -left">
          <?= $page_intro_quote; ?>
        </div>
        <div class="one-half -right">
          <?= apply_filters('the_content', $post->post_content); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<h2>Featured Program:</h2>
<?= print_r($featured_program) ?>

<?php if ($custom_featured_title): ?>
  <?php if ($custom_featured_image): ?>
    <img src="<?= $custom_featured_image ?>">
  <?php endif; ?>
  <h2><?= $custom_featured_title ?></h2>
  <div class="user-content">
    <?= $custom_featured_body ?>
  </div>
  <a class="button" href="<?= $custom_featured_link ?>">More</a>
<?php endif; ?>

<h2>Featured Workshop:</h2>
<?= print_r($featured_workshop) ?>

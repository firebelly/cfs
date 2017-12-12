<?php
/*
  Template name: Homepage
*/

$header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
if (!$header_video) {
  $header_bg = \Firebelly\Media\get_header_bg($post);
} else {
  $header_bg = '';
}
$page_intro_title = get_post_meta($post->ID, '_cmb2_intro_title', true);
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
$custom_featured_image_id = get_post_meta($post->ID, '_cmb2_custom_featured_image_id', true);
$custom_featured_link = get_post_meta($post->ID, '_cmb2_custom_featured_link', true);
$custom_featured_link_text = get_post_meta($post->ID, '_cmb2_custom_featured_link_text', true);

?>

<header class="page-header homepage">
  <div class="bg-image" <?= $header_bg ?>>
    <div class="gradient-l"></div><div class="gradient-b"></div>
    <?php if ($header_video): ?>
    <div class="background-video-wrapper">
      <video class="background-video" playsinline autoplay muted loop poster="">
        <source src="<?= $header_video ?>" type="video/mp4">
      </video>
    </div>
    <?php endif; ?>
    <section class="page-intro">
      <h1><?= !empty($page_intro_title) ? $page_intro_title : get_bloginfo('description'); ?></h1>
      <div class="page-content user-content grid">
        <div class="one-half -left">
          <p class="p-intro"><?= $page_intro_quote; ?></p>
        </div>
        <div class="one-half -right">
          <?= apply_filters('the_content', $post->post_content); ?>
        </div>
      </div>
    </section>
    <svg class="icon icon-notch bottom-left" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
    <svg class="icon icon-notch bottom-right" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
  </div>
</header>

<section class="featured">
  <?php if ($featured_program): ?>
  <article class="feature program-post">
    <div class="wrap grid">
      <div class="one-half -left">
        <a href="<?= get_permalink($featured_program) ?>" class="image" <?= \Firebelly\Media\get_header_bg($featured_program, ['size' => 'medium_large']) ?>></a>
      </div>
      <div class="one-half -right">
        <h1><a href="<?= get_permalink($featured_program) ?>"><?= $featured_program->post_title ?></a></h1>
        <div class="user-content excerpt">
          <p><?= Firebelly\Utils\get_excerpt($featured_program) ?></p>
        </div>
        <a href="<?= get_permalink($featured_program) ?>" class="button -wide -white">Get the Details</a>
      </div>
    </div>
  </article>
  <?php endif; ?>

  <?php if ($custom_featured_title): ?>
    <article class="feature custom-link">
      <div class="wrap grid">
        <div class="one-half -left details">
          <h1><a href="<?= $custom_featured_link ?>"><?= $custom_featured_title ?></a></h1>
          <div class="user-content excerpt">
            <?= apply_filters('the_content', $custom_featured_body) ?>
          </div>
          <a href="<?= $custom_featured_link ?>" class="button -wide -red"><?= $custom_featured_link_text ?></a>
        </div>
        <div class="one-half -right">
          <?php if ($custom_featured_image): ?>
            <a href="<?= $custom_featured_link ?>" class="image" <?= \Firebelly\Media\get_header_bg($custom_featured_image, ['thumb_id' => $custom_featured_image_id, 'size' => 'medium_large']) ?>></a>
          <?php endif; ?>
        </div>
      </div>
    </article>
  <?php endif; ?>

  <?php if ($featured_workshop): ?>
  <article class="feature workshop-post">
    <div class="wrap grid">
      <div class="one-half -left">
        <a href="<?= get_permalink($featured_workshop) ?>" class="image" <?= \Firebelly\Media\get_header_bg($featured_workshop, ['size' => 'medium_large']) ?>></a>
      </div>
      <div class="one-half -right">
        <h1><a href="<?= get_permalink($featured_workshop) ?>"><?= $featured_workshop->post_title ?></a></h1>
        <div class="user-content excerpt">
          <p><?= Firebelly\Utils\get_excerpt($featured_workshop) ?></p>
        </div>
        <a href="<?= get_permalink($featured_workshop) ?>" class="button -wide -red">Train With Us</a>
      </div>
    </div>
  </article>
  <?php endif; ?>

</section>

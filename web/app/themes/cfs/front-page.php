<?php
/*
  Template name: Homepage
*/
use jamiehollern\eventbrite\Eventbrite;

$header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
if (!$header_video) {
  $header_bg = \Firebelly\Media\get_header_bg($post);
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
$custom_featured_image_id = get_post_meta($post->ID, '_cmb2_custom_featured_image_id', true);
$custom_featured_link = get_post_meta($post->ID, '_cmb2_custom_featured_link', true);
$custom_featured_link_text = get_post_meta($post->ID, '_cmb2_custom_featured_link_text', true);

$eventbrite = new Eventbrite(getenv('EVENTBRITE_OAUTH_TOKEN'));
$events = $eventbrite->get('users/me/owned_events/', ['expand' => 'ticket']);
print_r($events['body']['events']);
exit;

?>

<header class="page-header homepage">
  <div class="bg-image" <?= $header_bg ?>>
    <?php if ($header_video): ?>
    <div class="background-video-wrapper">
      <video class="background-video" playsinline autoplay muted loop poster="">
        <source src="<?= $header_video ?>" type="video/mp4">
      </video>
    </div>
    <?php endif; ?>
    <section class="page-intro">
      <h1><?php bloginfo('description'); ?></h1>
      <div class="page-content user-content grid">
        <div class="one-half -left">
          <p class="p-intro"><?= $page_intro_quote; ?></p>
        </div>
        <div class="one-half -right">
          <?= apply_filters('the_content', $post->post_content); ?>
        </div>
      </div>
    </section>
    <svg class="icon icon-notch bottom-left" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
    <svg class="icon icon-notch bottom-right" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
  </div>
</header>

<section class="featured">
  <?php if ($featured_program): ?>
  <article class="feature program-post bigclicky">
    <div class="wrap grid">
      <div class="one-half -left">
        <div class="image" <?= \Firebelly\Media\get_header_bg($featured_program, ['size' => 'medium_large']) ?>></div>
      </div>
      <div class="one-half -right">
        <h1><?= $featured_program->post_title ?></h1>
        <div class="user-content excerpt">
          <p><?= Firebelly\Utils\get_excerpt($featured_program) ?></p>
        </div>
        <a href="<?= get_permalink($featured_program) ?>" class="button -wide -white">Get the Details</a>
      </div>
    </div>
  </article>
  <?php endif; ?>

  <?php if ($custom_featured_title): ?>
    <article class="feature custom-link bigclicky">
      <div class="wrap grid">
        <div class="one-half -left details">
          <h1><?= $custom_featured_title ?></h1>
          <div class="user-content excerpt">
            <?= apply_filters('the_content', $custom_featured_body) ?>
          </div>
          <a href="<?= $custom_featured_link ?>" class="button -wide -red"><?= $custom_featured_link_text ?></a>
        </div>
        <div class="one-half -right">
          <?php if ($custom_featured_image): ?>
            <div class="image" <?= \Firebelly\Media\get_header_bg($custom_featured_image, ['thumb_id' => $custom_featured_image_id, 'size' => 'medium_large']) ?>></div>
          <?php endif; ?>
        </div>
      </div>
    </article>
  <?php endif; ?>

  <?php if ($featured_workshop): ?>
  <article class="feature workshop-post bigclicky">
    <div class="wrap grid">
      <div class="one-half -left">
        <div class="image" <?= \Firebelly\Media\get_header_bg($featured_workshop, ['size' => 'medium_large']) ?>></div>
      </div>
      <div class="one-half -right">
        <h1><?= $featured_workshop->post_title ?></h1>
        <div class="user-content excerpt">
          <p><?= Firebelly\Utils\get_excerpt($featured_workshop) ?></p>
        </div>
        <a href="<?= get_permalink($featured_workshop) ?>" class="button -wide -red">Train With Us</a>
      </div>
    </div>
  </article>
  <?php endif; ?>

</section>

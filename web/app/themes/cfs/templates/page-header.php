<?php
use Roots\Sage\Titles;

// Pull 404 page for content
if (is_404()) {
	$post = get_page_by_path('/404-error/');
}

// Defaults
$accordions_html = $registration_html = $page_intro_quote = $header_video = $header_bg = '';

if (is_post_type_archive('workshop')) {
  // Workshop listings page pulls info from "Upcoming Workshops"
  $workshop_page = get_page_by_title('Upcoming Workshops');
  $header_video = get_post_meta($workshop_page->ID, '_cmb2_featured_video', true);
  if (!$header_video) {
    $header_bg = \Firebelly\Media\get_header_bg($workshop_page);
  } else {
    $header_bg = '';
  }
  $page_intro_quote = get_post_meta($workshop_page->ID, '_cmb2_intro_quote', true);

} else if (!empty($post)) {
  // Otherwise get header data from single post
  $header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
  if (!$header_video) {
    $header_bg = \Firebelly\Media\get_header_bg($post);
  } else {
    $header_bg = '';
  }
  $page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);
}

// Pull post accordions
$accordions_html = Firebelly\Utils\get_accordions($post);

if (is_singular('workshop') || is_singular('program')) {
  $registration_html = \Firebelly\Utils\get_registration_details($post);
}
?>

<header class="page-header half">
  <?php if (!empty($header_bg)): ?>
  <div class="bg-image" <?= $header_bg ?>>
    <?php if ($header_video): ?>
    <div class="background-video-wrapper">
      <video class="background-video" playsinline autoplay muted loop poster="">
        <source src="<?= $header_video ?>" type="video/mp4">
      </video>
    </div>
    <?php endif; ?>
    <svg class="icon icon-notch bottom-left" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
    <svg class="icon icon-notch bottom-right" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
  </div>
  <?php endif; ?>
  <div class="page-intro"><div class="color-wrap">
    <div class="page-content">
      <div class="page-titles">
        <?= Firebelly\Utils\fb_crumbs() ?>
        <h1><?= Titles\title(); ?></h1>
        <div class="intro-wrap">
          <p class="p-intro"><?= $page_intro_quote; ?></p>
          <?= apply_filters('the_content', $post->post_content); ?>
          <?= $registration_html ?>
        </div>
        <?= $accordions_html ?>
      </div>
    </div>
  </div></div>
</header>

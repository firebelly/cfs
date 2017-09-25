<?php
use Roots\Sage\Titles;
if (!empty($post)) {
  $header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
  if (!$header_video) {
    $header_bg = \Firebelly\Media\get_header_bg($post, false, '', 'bw', 'banner');
  } else {
    $header_bg = '';
  }
  $page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);
} else {
  $page_intro_quote = $header_video = $header_bg = '';
}
?>

<header class="page-header wide">
  <div class="bg-image" <?= $header_bg ?>>
    <?php if ($header_video): ?>
    <div class="background-video-wrapper">
      <video class="background-video" playsinline autoplay muted loop poster="">
        <source src="<?= $header_video ?>" type="video/mp4">
      </video>
    </div>
    <?php endif; ?>
    <?= Firebelly\Utils\fb_crumbs() ?>
    <svg class="icon icon-notch bottom-left" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
  </div>
  <div class="page-intro">
    <div class="page-content grid">
      <div class="one-half -left page-titles">
        <h1><?= Titles\title(); ?></h1>
        <p class="p-intro"><?= $page_intro_quote; ?></p>
      </div>
      <div class="one-half -right intro-text user-content">
        <?= apply_filters('the_content', $post->post_content); ?>
      </div>
    </div>
  </div>
</header>

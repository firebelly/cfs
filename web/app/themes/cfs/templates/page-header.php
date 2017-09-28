<?php
use Roots\Sage\Titles;
if (is_404()) {
	$post = get_page_by_path('/404-error/');
}
if (!empty($post)) {
  $header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
  if (!$header_video) {
    $header_bg = \Firebelly\Media\get_header_bg($post, false, '', 'bw', 'large');
  } else {
    $header_bg = '';
  }
  $page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);
} else {
  $page_intro_quote = $header_video = $header_bg = '';
}
// Pull post accordions
$accordions_html = Firebelly\Utils\get_accordions($post);
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
        <p class="p-intro"><?= $page_intro_quote; ?></p>
        <?= apply_filters('the_content', $post->post_content); ?>
        <?= $accordions_html ?>
      </div>
    </div>
  </div></div>
</header>

<?php
use Roots\Sage\Titles;
if (!empty($post)) {
  $page_intro_title = get_post_meta($post->ID, '_cmb2_intro_title', true);
  $page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);
} else {
  $page_intro_quote = $page_intro_title = $header_video = $header_bg = '';
}
?>

<header class="page-header -wide">
  <?php if (!empty($header_bg)): ?>
  <div class="bg-image" <?= $header_bg ?>>
    <div class="gradient-l"></div><div class="gradient-b"></div>
    <?= Firebelly\Utils\fb_crumbs() ?>
      <svg class="icon icon-notch bottom-left" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
  </div>
  <?php endif; ?>
    <div class="page-intro"><div class="color-wrap">
      <div class="page-content grid">
        <div class="intro-text user-content">
          <?php if (get_post_type($post) == 'job'): ?>
            <?= Firebelly\Utils\fb_crumbs() ?>
          <?php endif; ?>
          <h1><?= !empty($page_intro_title) ? $page_intro_title : Titles\title(); ?></h1>
          <p class="p-intro"><?= $page_intro_quote; ?></p>
          <?= apply_filters('the_content', $post->post_content); ?>
        </div>
      </div>
    </div>
  </div>
</header>

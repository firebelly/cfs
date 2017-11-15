<?php
/*
  Template name: Thank You
*/

$header_bg = \Firebelly\Media\get_header_bg($post);
?>

<header class="page-header thank-you">
  <div class="bg-image" <?= $header_bg ?>>
    <section class="page-intro">
      <h1><?= \Roots\Sage\Titles\title(); ?></h1>
      <div class="page-content user-content">
        <?= apply_filters('the_content', $post->post_content); ?>
      </div>
    </section>
    <svg class="icon icon-notch bottom-left" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
    <svg class="icon icon-notch bottom-right" aria-hidden="hidden" role="image"><use xlink:href="#icon-notch"/></svg>
  </div>
  <div class="window-sill">
    <div class="color-wrap"></div>
  </div>
</header>

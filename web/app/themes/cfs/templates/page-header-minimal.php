<?php
$header_bg = \Firebelly\Media\get_header_bg($post);
$page_intro_title = get_post_meta($post->ID, '_cmb2_intro_title', true);
?>
<header class="page-header minimal">
  <div class="bg-image" <?= $header_bg ?>>
    <div class="gradient-l"></div><div class="gradient-b"></div>
    <section class="page-intro">
      <h1><?= !empty($page_intro_title) ? $page_intro_title : \Roots\Sage\Titles\title(); ?></h1>
      <div class="page-content user-content">
        <?= apply_filters('the_content', $post->post_content); ?>
        <?php
        if (is_404()) {
          echo get_search_form();
        }
        ?>
      </div>
    </section>
    <svg class="icon icon-notch bottom-left" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
    <svg class="icon icon-notch bottom-right" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
  </div>
  <div class="window-sill">
    <div class="color-wrap"></div>
  </div>
</header>

<?php
// Pull 404 page for content areas
if (is_404()) {
	$post = get_page_by_path('/404-error/');
}

// Default values
$header_bg = '';
$page_title = \Roots\Sage\Titles\title();

$background_image = get_the_post_thumbnail_url($post, 'medium');
$header_bg = ' style="background-image:url(' . $background_image . ');"';
$page_title = $post->post_title;

// Pull custom title / intro fields
$page_intro_title = get_post_meta($post->ID, '_cmb2_intro_title', true);
$page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);

// Pull post's "accordions" — repeating blocks of content with attached media/quote
$accordions_html = Firebelly\Utils\get_accordions($post);

?>

<header class="page-header -half">
  <div class="bg-image" <?= $header_bg ?>>
    <div class="gradient-l"></div><div class="gradient-b"></div>
  </div>

  <div class="page-intro"><div class="color-wrap">
    <div class="page-content">
      <div class="page-titles">
        <?= Firebelly\Utils\fb_crumbs() ?>
        <h1><?= !empty($page_intro_title) ? $page_intro_title : $page_title; ?></h1>
        <div class="intro-wrap">
          <p class="p-intro"><?= $page_intro_quote; ?></p>
        </div>
      </div>

      <?php if (!empty($post->post_content) || !empty($accordions_html)): ?>
        <div class="page-meat user-content">
          <?= empty($post->post_content) ? '' : apply_filters('the_content', $post->post_content);?>
          <?= $accordions_html ?>
        </div>
      <?php endif; ?>
    </div>
  </div></div><!-- /.page-intro .color-wrap -->
</header>

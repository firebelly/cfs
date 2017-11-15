<?php
// Pull 404 page for content areas
if (is_404()) {
	$post = get_page_by_path('/404-error/');
}

// Default values
$accordions_html = $registration_html = $page_intro_quote = $header_video = $header_bg = '';
$page_title = \Roots\Sage\Titles\title();

// Workshop archive/taxonomy pages pull content areas from "Upcoming Workshops" page
if (is_post_type_archive('workshop') || is_tax('workshop_series')) {

  // Workshop listings page pulls info from "Upcoming Workshops"
  $post = get_page_by_title('Upcoming Workshops');
  $header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
  if (!$header_video) {
    $header_bg = \Firebelly\Media\get_header_bg($post);
  } else {
    $header_bg = '';
  }
  $page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);
  $page_title = $post->post_title;

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

// Pull post's "accordions" — repeating blocks of content with attached media/quote
$accordions_html = Firebelly\Utils\get_accordions($post);

// Pull registration details for Workshop or Program posts
if (is_singular('workshop') || is_singular('program')) {
  $registration_html = \Firebelly\Utils\get_registration_details($post);
}
?>

<?php // Use text-only wide header for workshops/pages that have no featured image ?>
<?php if (empty($header_bg) && in_array($post->post_type, ['workshop','page'])): ?>

<header class="page-header -wide -text-only">
  <div class="page-intro"><div class="color-wrap">
    <div class="grid">
      <div class="page-titles">
        <?= Firebelly\Utils\fb_crumbs() ?>
        <h1><?= $page_title; ?></h1>
      </div>
    </div>

    <div class="page-content">
      <div class="color-wrap-secondary">
        <div class="intro-wrap">
          <?= $registration_html ?>
          <p class="p-intro"><?= $page_intro_quote; ?></p>
          <?= empty($post->post_content) ? '' : apply_filters('the_content', $post->post_content);?>
        </div>
        <?= $accordions_html ?>
      </div>
    </div>
  </div><!-- /.page-intro .color-wrap -->
</header>

<?php else: ?>

<header class="page-header -half">
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

  <div class="page-intro"><div class="color-wrap">
    <div class="page-content">
      <div class="page-titles">
        <?= Firebelly\Utils\fb_crumbs() ?>
        <h1><?= $page_title; ?></h1>
        <div class="intro-wrap">
          <?= $registration_html ?>
          <p class="p-intro"><?= $page_intro_quote; ?></p>
          <?= empty($post->post_content) ? '' : apply_filters('the_content', $post->post_content);?>
        </div>
        <?= $accordions_html ?>
      </div>
    </div>
  </div></div><!-- /.page-intro .color-wrap -->
</header>

<?php endif; ?>

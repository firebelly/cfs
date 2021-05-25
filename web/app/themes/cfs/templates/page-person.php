<?php
global $post;
// Pull 404 page for content areas
if (is_404()) {
	$post = get_page_by_path('/404-error/');
}

// Default values
$accordions_html = $page_intro_quote = $header_video = $header_bg = '';
$page_title = \Roots\Sage\Titles\title();

if (is_post_type_archive('person') || is_tax('person_category') ) {

    $header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
    if (!$header_video) {
        $header_bg = \Firebelly\Media\get_header_bg($post);
    } else {
        $header_bg = '';
    }
    $page_title = $post->post_title;

} else if (!empty($post)) {
    // Otherwise get header data from single post
    $header_video = get_post_meta($post->ID, '_cmb2_featured_video', true);
    if (!$header_video) {
        if (has_post_thumbnail($post)) {
            // Youth Program sections get untreated image
            $background_image = get_the_post_thumbnail_url($post, 'large');
            $header_bg = ' style="background-image:url(' . $background_image . ');"';
        } else {
            $header_bg = \Firebelly\Media\get_header_bg($post);
        }
    } else {
        $header_bg = '';
    }
}

// Pull custom title / intro fields
$page_intro_title = get_post_meta($post->ID, '_cmb2_intro_title', true);
$page_intro_quote = get_post_meta($post->ID, '_cmb2_intro_quote', true);
$person_email = get_post_meta($post->ID, '_cmb2_person_email', true);

// Pull post's "accordions" — repeating blocks of content with attached media/quote
$accordions_html = Firebelly\Utils\get_accordions($post);

?>

<?php if (empty($header_bg) && in_array($post->post_type, ['people', 'page'])): ?>

    <header class="page-header -wide -text-only">
    <div class="page-intro"><div class="color-wrap">
        <div class="grid">
        <div class="page-titles">
            <?= Firebelly\Utils\fb_crumbs() ?>
            <h1><?= !empty($page_intro_title) ? $page_intro_title : $page_title; ?></h1>
        </div>
        </div>

        <div class="page-content">
        <div class="color-wrap-secondary">
            <div class="intro-wrap">
            <?= $registration_html ?>
            <p class="p-intro"><?= $page_intro_quote; ?></p>
            </div>

            <?php if (!empty($post->post_content) || !empty($accordions_html)): ?>
            <div class="page-meat user-content">
                <?= empty($post->post_content) ? '' : apply_filters('the_content', $post->post_content);?>
                <?= $accordions_html ?>
            </div>
            <?php endif; ?>
        </div>
        </div>
    </div><!-- /.page-intro .color-wrap -->
    </header>

<?php else: ?>

    <header class="page-header -half">
    <div class="bg-image" <?= $header_bg ?>>
        <div class="gradient-l"></div><div class="gradient-b"></div>
        <?php if ($header_video): ?>
        <div class="background-video-wrapper">
        <video class="background-video" playsinline autoplay muted loop poster="">
            <source src="<?= $header_video ?>" type="video/mp4">
        </video>
        </div>
        <?php endif; ?>
        <svg class="icon icon-notch bottom-left" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
        <svg class="icon icon-notch bottom-right" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
    </div>

    <div class="page-intro"><div class="color-wrap">
        <div class="page-content">
        <div class="page-titles">
            <?= Firebelly\Utils\fb_crumbs() ?>
            <h1><?= !empty($page_intro_title) ? $page_intro_title : $page_title; ?></h1>
            <?php if (!empty($person_email)): ?>
                <a href="mailto:<?= $person_email ?>" class="email"><?= $person_email ?></a>
            <?php endif; ?>
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

<?php endif; ?>

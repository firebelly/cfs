<?php
/**
 * Template Name: Landing Page
 */
?>
<?php get_template_part('templates/page', 'header-wide'); ?>

<?php
$child_pages = get_children([
  'post_parent' => $post->ID,
  'post_type'   => 'page',
  'num_posts'   => 4,
]);
if (count($child_pages)==2) {
  $column_class = 'one-half';
} else {
  $column_class = count($child_pages)==3 ? 'one-third' : 'one-fourth';
}
?>

<div class="secondary-nav">
  <div class="wrap"><div class="grid">
    <?php foreach ($child_pages as $child_page): ?>
      <article class="child-page <?= $column_class ?> bigclicky"><div class="wrap">
        <?php
        $nav_excerpt = get_post_meta($child_page->ID, '_cmb2_nav_excerpt', true);
        if (empty($nav_excerpt)) {
          $nav_excerpt = Firebelly\Utils\get_excerpt($child_page);
        }
        $nav_button_text = get_post_meta($child_page->ID, '_cmb2_nav_button_text', true);
        ?>
        <?php if ( $header_bg = \Firebelly\Media\get_header_bg($child_page, false, '', 'bw', 'banner')): ?>
          <div class="image" <?= $header_bg ?>></div>
        <?php endif; ?>
        <h1><?= $child_page->post_title ?></h1>
        <p class="excerpt"><?= $nav_excerpt ?></p>
        <a href="<?= get_permalink($child_page->ID) ?>" class="read-more"><?= $nav_button_text ?></a>
      </div></article>
    <?php endforeach; ?>
  </div></div>
</div>

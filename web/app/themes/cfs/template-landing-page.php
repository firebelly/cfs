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
  'post_status' => 'publish'
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
      <article class="child-page <?= $column_class ?>"><div class="wrap">
        <?= \Firebelly\Utils\admin_edit_link($child_page) ?>
        <?php
        $nav_excerpt = get_post_meta($child_page->ID, '_cmb2_nav_excerpt', true);
        if (empty($nav_excerpt)) {
          $nav_excerpt = Firebelly\Utils\get_excerpt($child_page);
        }
        $nav_button_text = get_post_meta($child_page->ID, '_cmb2_nav_button_text', true);
        $nav_button_link = get_post_meta($child_page->ID, '_cmb2_nav_button_link', true);
        if (empty($nav_button_link)) {
          $nav_button_link = get_permalink($child_page->ID);
        }
        ?>
        <?php if ($header_bg = \Firebelly\Media\get_header_bg($child_page, ['size' => 'medium'])): ?>
          <a href="<?= $nav_button_link ?>" class="image" <?= $header_bg ?>></a>
        <?php endif; ?>
        <h1><a href="<?= $nav_button_link ?>"><?= $child_page->post_title ?></a></h1>
        <p class="excerpt"><?= $nav_excerpt ?></p>
        <a href="<?= $nav_button_link ?>" class="read-more"><?= $nav_button_text ?></a>
      </div></article>
    <?php endforeach; ?>
  </div></div>
</div>

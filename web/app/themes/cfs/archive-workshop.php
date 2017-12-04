<?php get_template_part('templates/page', 'header'); ?>

<div class="column-wrap"><div class="color-wrap">

  <?php
  $featured_workshop_series = \Firebelly\PostTypes\Workshop\get_featured_workshop_series();
  if ($featured_workshop_series):
    // Get featured image from
    if (!empty($featured_workshop_series->image)) {
      $featured_image = \Firebelly\Media\get_header_bg($featured_workshop_series->image, ['size' => 'medium_large', 'thumb_id' => $featured_workshop_series->image_id]);
    } else {
      $series_posts = \Firebelly\PostTypes\Workshop\get_workshops(['workshop_series' => $featured_workshop_series->term_id , 'output' => 'array']);
      foreach($series_posts as $workshop_post) {
        if (!empty($featured_image)) continue;
        $featured_image = \Firebelly\Media\get_header_bg($workshop_post, ['size' => 'medium_large']);
      }
    }
  ?>

  <article class="workshop feature"><div class="wrap">
    <?= \Firebelly\Utils\admin_edit_link($featured_workshop_series) ?>
    <div class="wrap grid">
      <div class="one-half -left">
        <h1><?= $featured_workshop_series->name ?></h1>
        <div class="user-content excerpt">
          <p><?= $featured_workshop_series->description ?></p>
        </div>
        <a href="<?= get_term_link($featured_workshop_series) ?>" class="button -wide -black">Show All <?= $featured_workshop_series->name ?></a>
      </div>
      <div class="one-half -right">
        <div class="image" <?= $featured_image ?>></div>
      </div>
    </div>
  </div></article>
  <?php endif; ?>

  <div class="article-list"><div class="grid">
  <?php while (have_posts()) : the_post(); ?>
    <?php
    $workshop_post = $post;
    include(locate_template('templates/article-workshop.php'));
    ?>
  <?php endwhile; ?>
  </div></div>

  <?= \Firebelly\Utils\pagination(); ?>

</div></div><!-- /.column-wrap -->

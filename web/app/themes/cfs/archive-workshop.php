<?php get_template_part('templates/page', 'header'); ?>

<div class="column-wrap"><div class="color-wrap">

  <?php
  $featured_workshop_series = \Firebelly\PostTypes\Workshop\get_featured_workshop_series();
  if ($featured_workshop_series):
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
        <div class="image" <?= $featured_workshop_series->image ?>></div>
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

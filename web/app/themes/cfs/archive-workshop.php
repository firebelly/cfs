<?php get_template_part('templates/page', 'header'); ?>

<div class="column-wrap"><div class="color-wrap">

  <?php
  $featured_workshop = Firebelly\PostTypes\Workshop\get_featured_workshops(['num_posts' => 1]);
  if ($featured_workshop):
    $workshop_post = $featured_workshop[0];
    $workshop_post->series = \Firebelly\PostTypes\Workshop\get_series($workshop_post);
    $post_image = \Firebelly\Media\get_header_bg($workshop_post, ['size' => 'medium_large']);
  ?>

  <article class="workshop feature"><div class="wrap">
    <div class="wrap grid">
      <div class="one-half -left">
        <h1><?= $workshop_post->post_title ?></h1>
        <div class="user-content excerpt">
          <p><?= Firebelly\Utils\get_excerpt($workshop_post, 100) ?></p>
        </div>
        <?php if (!empty($workshop_post->series)): ?>
          <a href="<?= get_term_link($workshop_post->series) ?>" class="button -wide -black">Show All</a>
        <?php endif; ?>
      </div>
      <div class="one-half -right">
        <div class="image" <?= $post_image ?>></div>
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

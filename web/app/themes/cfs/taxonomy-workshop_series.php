<?php get_template_part('templates/page', 'header'); ?>

<div class="column-wrap"><div class="color-wrap">

  <div class="article-list"><div class="grid">
  <?php while (have_posts()) : the_post(); ?>
    <?php
    $workshop_post = $post;
    include(locate_template('templates/article-workshop.php'));
    ?>
  <?php endwhile; ?>
  </div></div>

  <?php the_posts_navigation(); ?>

</div></div><!-- /.column-wrap -->

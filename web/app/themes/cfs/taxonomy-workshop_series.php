<?php
$taxonomy = get_queried_object();
get_template_part('templates/page', 'header');
?>

<div class="column-wrap"><div class="color-wrap">

  <div class="taxonomy-header">
    <div class="grid">
      <div class="taxonomy-title grid-item">
        <h3>Showing <?= $taxonomy->name ?> workshops</h3>
      </div>
      <div class="taxonomy-clear grid-item">
        <h3>
          <a href="/workshops/"><span>Show All Workshops</span> <svg class="icon icon-x" aria-hidden="hidden" role="image"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
        </h3>
      </div>
    </div>
  </div>

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

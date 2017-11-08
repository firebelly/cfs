<article <?php post_class('search-result'); ?>>
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p class="url"><?php the_permalink(); ?></p>
  </header>
  <div class="entry-summary">
    <p><?= \Firebelly\Utils\get_excerpt($post, 25); ?></p>
  </div>
</article>

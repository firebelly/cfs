<?php
// Send individual articles to base page for various types
if ($post->post_type == 'person') {
	$article_url = '/about-us/people/';
} else if ($post->post_type == 'job') {
	$article_url = '/about-us/join-us/';
} else if ($post->post_type == 'partner') {
	$article_url = '/support-us/partners-supporters/';
} else {
	$article_url = get_permalink();
}
?>
<article <?php post_class('search-result'); ?>>
  <header>
    <h2 class="entry-title"><a href="<?= $article_url ?>"><?php the_title(); ?></a></h2>
    <p class="url"><?= $article_url ?></p>
  </header>
  <div class="entry-summary">
    <p><?= \Firebelly\Utils\get_excerpt($post, 25); ?></p>
  </div>
</article>

<?php
$workshop_post->meta = get_post_meta($workshop_post->ID);
$post_image = \Firebelly\Media\get_header_bg($workshop_post, ['size' => 'medium']);
$workshop_post->series = \Firebelly\PostTypes\Workshop\get_series($workshop_post);
?>
<article class="workshop"><div class="wrap">
  <?php if ($post_image): ?>
    <div class="image" <?= $post_image ?>></div>
  <?php endif; ?>
  <?php if (!empty($workshop_post->series)): ?>
    <h3 class="series"><a href="<?= get_term_link($workshop_post->series) ?>"><?= $workshop_post->series->name ?></a></h3>
  <?php endif; ?>
  <h1 class="h2"><a href="<?= get_permalink($workshop_post) ?>"><?= $workshop_post->post_title ?></a></h1>
  <?php if (!empty($workshop_title)): ?>
    <p class="workshop-title"><?= $workshop_title ?></p>
  <?php endif; ?>
  <?= Firebelly\PostTypes\Workshop\get_workshop_date($workshop_post); ?>
  <?php if ($workshop_post->sold_out): ?>
    <a class="button black disabled" href="#">Sold Out</a>
  <?php else: ?>
    <a class="button black" href="<?= $workshop_post->meta['_cmb2_eventbrite_url'][0] ?>">Register</a>
  <?php endif; ?>
</div></article>

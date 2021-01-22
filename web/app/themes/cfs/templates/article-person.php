<?php
$person_post->meta = get_post_meta($person_post->ID);
$person_title = get_post_meta($person_post->ID, '_cmb2_person_title', true);
$person_show_page = get_post_meta($person_post->ID, '_cmb2_show_link', true);
$person_image = \Firebelly\Media\get_header_bg($person_post, ['size' => 'medium']);
$person_desc = strip_shortcodes( $person_post->ID );
$person_desc = apply_filters('the_content', $person_post->post_content);
$person_desc = str_replace(']]>', ']]&gt;', $person_desc);
$excerpt_length = apply_filters( 'excerpt_length', 25 );
$person_desc = wp_trim_words( $person_desc, $excerpt_length);

?>
<article class="person <?= $person_post->column_width ?>"><div class="wrap">
  <?= \Firebelly\Utils\admin_edit_link($person_post) ?>
  <?php if ($person_image): ?>
    <div class="image" <?= $person_image ?>></div>
  <?php endif; ?>
  <?php if ( $person_show_page === 'on'): ?>
    <h1 class="h3"><a href="<?= get_permalink($person_post) ?>" title="<?= $person_title?>"><?= $person_post->post_title ?></a></h1>
  <?php else: ?>
    <h1 class="h3"><?= $person_post->post_title ?></h1>
  <?php endif; ?>
  <?php if (!empty($person_title)): ?>
    <p class="title"><?= $person_title ?></p>
  <?php endif; ?>
  <?php if (!empty($person_desc) && !empty($person_image)): ?>
    <div class="excerpt">
      <?= $person_desc ?>
    </div>
    <a class="read-more" href="<?= get_permalink($person_post) ?>" title="<?= $person_title?>">Continue</a>
  <?php endif; ?>
</div></article>

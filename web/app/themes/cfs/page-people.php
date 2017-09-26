<?php get_template_part('templates/page', 'header-wide'); ?>

<?php
$people = get_posts([
  'post_type'   => 'person',
  'num_posts'   => -1,
  'tax_query'   => [
    [
      'taxonomy' => 'person_category',
      'field' => 'slug',
      'terms' => 'staff',
    ]
  ]
]);

?>

<div class="secondary-nav">
  <div class="wrap"><div class="grid">
    <?php foreach ($people as $person): ?>
      <article class="person"><div class="wrap">
        <?php
        $person_title = get_post_meta($person->ID, '_cmb2_person_title', true);
        $person_desc = apply_filters('the_content', $person->post_content);
        ?>
        <?php if ($header_bg = \Firebelly\Media\get_header_bg($person, false, '', 'bw', 'large')): ?>
          <div class="image" <?= $header_bg ?>></div>
        <?php endif; ?>
        <h1 class="h3"><?= $person->post_title ?></h1>
        <?php if (!empty($person_title)): ?>
          <p class="person-title"><?= $person_title ?></p>
        <?php endif ?>
        <p class="excerpt user-content"><?= $person_desc ?></p>
      </div></article>
    <?php endforeach; ?>
  </div></div>
</div>

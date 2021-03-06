<?php get_template_part('templates/page', 'header'); ?>

<div class="secondary-nav">
  <div class="wrap article-grid-category staff">
    <h3>Staff</h3>
    <div class="grid">
      <?= Firebelly\PostTypes\Person\get_people(['category' => 'staff', 'column-width' => 'one-third']) ?>
    </div>
  </div>
  <div class="wrap article-grid-category">
    <h3>Youth Leadership Board</h3>
    <div class="grid">
    <?= Firebelly\PostTypes\Person\get_people(['category' => 'youth-leadership-board', 'column-width' => 'one-fourth']) ?>
    </div>
  </div>
  <div class="wrap article-grid-category">
    <h3>Board of Directors</h3>
    <div class="grid">
      <?= Firebelly\PostTypes\Person\get_people(['category' => 'board-of-directors', 'column-width' => 'one-fourth']) ?>
    </div>
  </div>
</div>

<?php
get_template_part('templates/page', 'header');
$partner_categories = get_terms(['taxonomy' => 'partner_category', 'hide_empty' => 0]);
?>

<div class="secondary-nav">
  <?php foreach($partner_categories as $partner_category): ?>
  <div class="wrap article-grid-category">
    <h3><?= $partner_category->name ?></h3>
    <div class="grid">
      <?= Firebelly\PostTypes\Partner\get_partners(['category' => $partner_category->slug, 'column-width' => 'one-fourth']) ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

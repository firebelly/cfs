<?php
$partner_title = get_post_meta($partner_post->ID, '_cmb2_partner_title', true);
$partner_desc = apply_filters('the_content', $partner_post->post_content);
$partner_image = \Firebelly\Media\get_header_bg($partner_post, ['size' => 'medium'])
?>
<article class="partner <?= $partner_post->column_width ?>"><div class="wrap">
  <?php if ($partner_image): ?>
    <div class="image" <?= $partner_image ?>></div>
  <?php endif; ?>
  <h1 class="h3"><?= $partner_post->post_title ?></h1>
  <?php if (!empty($partner_title)): ?>
    <p class="partner-title"><?= $partner_title ?></p>
  <?php endif; ?>
  <?php if (!empty($partner_desc)): ?>
    <div class="user-content"><?= $partner_desc ?></div>
  <?php endif; ?>
</div></article>

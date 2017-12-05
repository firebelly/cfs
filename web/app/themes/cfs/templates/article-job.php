<?php
$job_type = get_post_meta($job_post->ID, '_cmb2_job_type', true);
$job_desc = apply_filters('the_content', $job_post->post_content);
?>
<article class="job <?= $job_post->column_width ?>"><div class="wrap">
	<?= \Firebelly\Utils\admin_edit_link($job_post) ?>
  <h1 class="h3"><?= $job_post->post_title ?></h1>
  <?php if (!empty($job_type)): ?>
    <p class="title"><?= $job_type ?></p>
  <?php endif; ?>
  <?php if (!empty($job_desc)): ?>
    <div class="excerpt user-content"><?= $job_desc ?></div>
  <?php endif; ?>
</div></article>

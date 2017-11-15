<?php
get_template_part('templates/page', 'header');
$jobs = Firebelly\PostTypes\Job\get_jobs(['column-width' => 'one-fourth']);
?>

<?php if ($jobs): ?>
<div class="secondary-nav">
  <div class="wrap article-grid-category">
    <h3>Available Opportunities</h3>
    <div class="grid">
			<?= $jobs ?>
    </div>
  </div>
</div>
<?php endif; ?>

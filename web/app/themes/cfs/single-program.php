<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?= \Firebelly\Fields\Posts\get_post_slideshow($post); ?>
<?php endwhile; ?>
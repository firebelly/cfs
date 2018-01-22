<?php while (have_posts()) : the_post(); ?>
	<?php
	// Redirect to /support-us/moments-of-justice/ if title matches "moments of justice"
	if (preg_match('/moments of justice/i', $post->post_title)) {
		wp_redirect('/support-us/moments-of-justice/');
	}
	?>
	<?php get_template_part('templates/page', 'header'); ?>
	<?= \Firebelly\Fields\Posts\get_post_slideshow($post, ['treated_images' => true]); ?>
<?php endwhile; ?>
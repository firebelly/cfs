<?php while (have_posts()) : the_post(); 
    get_template_part('templates/page-header', 'fullwidth');
endwhile; ?>
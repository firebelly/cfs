<?php
get_template_part('templates/page', 'header');
$jobs = Firebelly\PostTypes\Job\get_jobs(['column-width' => 'one-fourth']);
?>
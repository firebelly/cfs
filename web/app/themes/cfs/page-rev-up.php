<?php
// Redirect to most recent Rev Up workshop (or homepage if none is available)
$revup_post = \Firebelly\PostTypes\Workshop\get_workshop_like_title('Rev Up');
if ($revup_post) {
  $permalink = get_the_permalink($revup_post);
} else {
  $permalink = get_home_url();
}
wp_redirect($permalink);

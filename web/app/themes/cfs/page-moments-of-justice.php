<?php
/*
  Template name: Moments of Justice
*/

$post = \Firebelly\PostTypes\Workshop\get_workshop_by_slug('moments-of-justice');
if (!$post):
  echo '<div class="alert"><p>Moments of Justice workshop not found.</p></div>';
else:
  get_template_part('templates/page', 'header');

$sponsorship_packet = get_post_meta($post->ID, '_cmb2_sponsorship_packet', true);
?>
<div class="secondary-content"><div class="color-wrap">
  <div class="wrap">
    <div class="grid button-twins">
      <div class="one-half -left">
        <a href="/support-us/donate/" class="button -red -wide">Donate</a>
      </div>
      <div class="one-half -right">
        <?php if(!empty($sponsorship_packet)): ?>
          <a href="<?= $sponsorship_packet ?>" class="button -red -wide">Download Sponsorship Packet</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div></div>
<?php endif; ?>
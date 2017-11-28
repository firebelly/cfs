<?php
$post = \Firebelly\PostTypes\Workshop\get_workshop_by_slug('moments-of-justice');
if (!$post):
  echo '<div class="alert"><p>Moments of Justice workshop not found.</p></div>';
else:
  get_template_part('templates/page', 'header');
?>
<div class="secondary-content"><div class="color-wrap">
  <div class="wrap">
    <div class="grid button-twins">
      <div class="one-half -left">
        <a href="/support-us/donate/" class="button -red -wide">Donate</a>
      </div>
      <div class="one-half -right">
        <a href="" class="button -red -wide">Download Sponsorship Packet</a>
      </div>
    </div>
  </div>
</div></div>
<?php endif; ?>
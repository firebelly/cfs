<form action="<?= admin_url('admin-ajax.php') ?>" class="newsletter-form">
  <div class="grid">
    <div class="grid-item one-half input-name input-item">
      <input type="text" name="cc_name" autocomplete="section-footer name">
      <label>Name</label>
    </div>
    <div class="grid-item one-half input-email input-item">
      <input type="text" name="cc_email" class="required email" required autocomplete="section-footer email">
      <label>Email Address</label>
    </div>
    <div class="clear input-submit"><button type="submit" class="button">
      <svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg>
      <span class="hide-for-medium-up">Subscribe</span></button>
    </div>
  </div>
  <input name="action" type="hidden" value="newsletter_subscribe">
  <input name="cc_list_id" type="hidden" value="<?= \Firebelly\SiteOptions\get_option('default_cc_list_id') ?>">
  <?php wp_nonce_field( 'newsletter_form', 'newsletter_form_nonce' ); ?>
</form>

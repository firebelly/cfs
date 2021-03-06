<footer class="site-footer" id="site-footer">
  <div class="wrap grid">

    <h1 class="brand"><a href="/">
      <svg class="icon icon-logo" aria-hidden="true"><use xlink:href="#icon-logo"/></svg>
      <svg class="icon icon-logo-wordmark" aria-hidden="true"><use xlink:href="#icon-logo-wordmark"/></svg>
      <span class="sr-only"><?= get_bloginfo('name'); ?></span>
    </a></h1>

      <div class="subscribe">

        <div class="social">
          <ul>
            <li><a class="button" href="https://www.facebook.com/<?= \Firebelly\SiteOptions\get_option('facebook_id'); ?>"><svg class="icon icon-facebook" aria-hidden="true"><use xlink:href="#icon-facebook"/></svg><span class="sr-only">Facebook</span></a></li>
            <li><a class="button" href="https://www.twitter.com/<?= \Firebelly\SiteOptions\get_option('twitter_id'); ?>"><svg class="icon icon-twitter" aria-hidden="true"><use xlink:href="#icon-twitter"/></svg><span class="sr-only">Twitter</span></a></li>
            <li><a class="button" href="https://www.vimeo.com/<?= \Firebelly\SiteOptions\get_option('vimeo_id'); ?>"><svg class="icon icon-vimeo" aria-hidden="true"><use xlink:href="#icon-vimeo"/></svg><span class="sr-only">Vimeo</span></a></li>
          </ul>
        </div>

        <div class="newsletter">
          <h3>Stay Updated</h3>
          <?php include ('newsletter-form.php'); ?>
        </div>

        <div id="contact">
          <div class="grid">
            <div class="address grid-item one-half">
              <address class="vcard">
                <span class="street-address"><?= \Firebelly\SiteOptions\get_option('contact_address'); ?></span>
                <span class="locality"><?= \Firebelly\SiteOptions\get_option('contact_locality'); ?></span>
              </address>
            </div>
            <div class="contact-methods grid-item one-half">
              <p><?= \Firebelly\SiteOptions\get_option('contact_phone'); ?></p>
              <p><a href="mailto:<?= \Firebelly\SiteOptions\get_option('contact_email'); ?>"><?= \Firebelly\SiteOptions\get_option('contact_email'); ?></a></p>
            </div>
          </div>
          <div class="contact-notice">
            <p><?= \Firebelly\SiteOptions\get_option('contact_notice'); ?></p>
          </div>
          <div class="copyright">
            <p>&copy; <?= date("Y") ?> <?= get_bloginfo('name'); ?></p>
          </div>
        </div>

      </div><!-- /.subscribe -->
    </div><!-- /.wrap.grid -->
    <div class="footer-flash">
      <h3 class="h1">Thank you for<br>Subscribing!</h3>
      <p>This message will close in <span class="flash-count">3</span>.</p>
      <a href="#" class="close"><svg class="icon icon-x" aria-hidden="true"><use xlink:href="#icon-x"/></svg></a>
    </div>
</footer>
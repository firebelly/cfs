<footer class="site-footer" role="contentinfo">
  <div class="wrap grid">

    <h1 class="brand"><a href="/">
      <svg class="icon icon-logo" aria-hidden="hidden" role="image"><use xlink:href="#icon-logo"/></svg>
      <svg class="icon icon-logo-wordmark" aria-hidden="hidden" role="image"><use xlink:href="#icon-logo-wordmark"/></svg>
      <span class="sr-only"><?= get_bloginfo('name'); ?></span>
    </a></h1>

      <div class="subscribe">

        <div class="newsletter">
          <h3>Subscribe to our newsletter</h3>
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

      </div>

      <div class="social">
        <ul>
          <li><a class="button" href="https://www.facebook.com/<?= \Firebelly\SiteOptions\get_option('facebook_id'); ?>"><svg class="icon icon-facebook" aria-hidden="hidden" role="image"><use xlink:href="#icon-facebook"/></svg><span class="sr-only">Facebook</span></a></li>
          <li><a class="button" href="https://www.twitter.com/<?= \Firebelly\SiteOptions\get_option('twitter_id'); ?>"><svg class="icon icon-twitter" aria-hidden="hidden" role="image"><use xlink:href="#icon-twitter"/></svg><span class="sr-only">Twitter</span></a></li>
          <li><a class="button" href="https://www.vimeo.com/<?= \Firebelly\SiteOptions\get_option('vimeo_id'); ?>"><svg class="icon icon-vimeo" aria-hidden="hidden" role="image"><use xlink:href="#icon-vimeo"/></svg><span class="sr-only">Vimeo</span></a></li>
        </ul>
      </div>

    </div><!-- .grid -->

  </div><!-- .wrap -->
</footer>
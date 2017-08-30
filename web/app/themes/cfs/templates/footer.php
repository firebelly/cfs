<footer class="site-footer" role="contentinfo">
  <div class="wrap grid">

    <h1 class="brand"><a href="/"><svg class="icon icon-logo-footer" role="image"><use xlink:href="#icon-logo-footer"/></svg><span class="sr-only">CFS</span></a></h1>

    <div class="grid">

      <div class="grid-item one-half -left">

        <div class="newsletter">
          <h3>Subscribe to our newsletter</h3>
          <?php include ('newsletter-form.php'); ?>
        </div>

        <ul class="social">
          <li><a href="https://www.facebook.com/<?= \Firebelly\SiteOptions\get_option('facebook_id'); ?>"><svg class="icon icon-facebook" aria-hidden="hidden" role="image"><use xlink:href="#icon-facebook"/></svg><span class="sr-only">Facebook</span></a></li>
          <li><a href="https://www.twitter.com/<?= \Firebelly\SiteOptions\get_option('twitter_id'); ?>"><svg class="icon icon-twitter" aria-hidden="hidden" role="image"><use xlink:href="#icon-twitter"/></svg><span class="sr-only">Twitter</span></a></li>
          <li><a href="https://www.instagram.com/<?= \Firebelly\SiteOptions\get_option('instagram_id'); ?>"><svg class="icon icon-instagram" aria-hidden="hidden" role="image"><use xlink:href="#icon-instagram"/></svg><span class="sr-only">Instagram</span></a></li>
        </ul>

      </div><!-- .-left -->

      <div class="grid-item one-half -right">

        <div id="contact" class="contact grid">
          <div class="address grid-item one-third">
            <address class="vcard">
              <span class="street-address"><?= \Firebelly\SiteOptions\get_option('contact_address'); ?></span>
              <span class="locality"><?= \Firebelly\SiteOptions\get_option('contact_locality'); ?></span>
            </address>
          </div>
          <div class="contact-methods grid-item one-third">
            <p><?= \Firebelly\SiteOptions\get_option('contact_phone'); ?></p>
            <p><a href="mailto:<?= \Firebelly\SiteOptions\get_option('contact_email'); ?>"><?= \Firebelly\SiteOptions\get_option('contact_email'); ?></a></p>
          </div>
          <div class="copyright grid-item one-third">
            <p>Copyright CFS <?= date("Y") ?></p>
            <p>Design & Dev by <a href="http://firebellydesign.com" target="_blank">Firebelly</a></p>
          </div>
        </div>

      </div><!-- .-right -->

    </div><!-- .grid -->

  </div><!-- .wrap -->
</footer>
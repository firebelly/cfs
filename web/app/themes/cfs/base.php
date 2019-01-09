<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <div class="hide" aria-hidden="true"><?php include_once(get_template_directory().'/dist/svgs/build/svgs-defs.svg'); ?></div>
    <?php
      do_action('get_header');
      get_template_part('templates/header');
    ?>
    <div class="wrap container" role="document">
      <div class="content row">
        <main class="main">
          <!--[if IE]>
            <div class="alert alert-warning">
              <p><?php _e('You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?></p>
            </div>
          <![endif]-->
          <?php include Wrapper\template_path(); ?>
        </main><!-- /.main -->
        <?php if (Setup\display_sidebar()) : ?>
          <aside class="sidebar">
            <?php include Wrapper\sidebar_path(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>
    <?php if (WP_ENV === 'development'): ?>
    <script id="__bs_script__">//<![CDATA[
        document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.18.13'><\/script>".replace("HOST", location.hostname));
    //]]></script>
    <?php endif; ?>
  </body>
</html>

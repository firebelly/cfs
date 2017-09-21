<header class="site-header -sticky" role="banner">
  <div class="wrap">
    <nav class="site-nav" role="navigation">
      <h1 class="brand"><a href="<?= esc_url(home_url('/')); ?>">
        <svg class="icon icon-logo" aria-hidden="hidden" role="image"><use xlink:href="#icon-logo"/></svg>
        <span class="sr-only"><?= get_bloginfo('name'); ?></span>
      </a></h1>
      <div class="logo-wordmark"><a href="<?= esc_url(home_url('/')); ?>">
        <svg class="icon icon-logo-wordmark" aria-hidden="hidden" role="image"><use xlink:href="#icon-logo-wordmark"/></svg>
      </a></div>
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
</header>

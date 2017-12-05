<header class="site-header">
  <div class="wrap">
    <h1 class="brand"><a href="<?= esc_url(home_url('/')); ?>">
      <svg class="icon icon-logo" aria-hidden="true"><use xlink:href="#icon-logo"/></svg>
      <span class="sr-only"><?= get_bloginfo('name'); ?></span>
    </a></h1>
    <nav class="site-nav">
      <div class="logo-wordmark"><a href="<?= esc_url(home_url('/')); ?>">
        <svg class="icon icon-logo-wordmark" aria-hidden="true"><use xlink:href="#icon-logo-wordmark"/></svg>
      </a></div>
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
      <div class="nav-search">
        <?= get_search_form() ?>
        <a href="#" class="search-close button"><svg class="icon icon-x" aria-hidden="true"><use xlink:href="#icon-x"/></svg></a>
      </div>
    </nav>
  </div>
</header>

<header class="site-header" role="banner">
  <div class="wrap">
    <h1 class="brand"><a href="<?= esc_url(home_url('/')); ?>">
      <svg class="icon icon-logo" aria-hidden="hidden" role="image"><use xlink:href="#icon-logo"/></svg>
      <span class="sr-only"><?= get_bloginfo('name'); ?></span>
    </a></h1>
    <nav class="site-nav" role="navigation">
      <div class="logo-wordmark"><a href="<?= esc_url(home_url('/')); ?>">
        <svg class="icon icon-logo-wordmark" aria-hidden="hidden" role="image"><use xlink:href="#icon-logo-wordmark"/></svg>
      </a></div>
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
      <div class="nav-search">
        <form role="search" method="get" class="search-form" action="/">
          <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type="search" class="search-field" placeholder="Keyword(s)" value="" name="s" required>
          <input type="submit" class="go-button" value="Search">
        </form>
        <a href="#" class="search-close"><svg class="icon icon-x" aria-hidden="hidden" role="image"><use xlink:href="#icon-x"/></svg></a>
      </div>
    </nav>
  </div>
</header>

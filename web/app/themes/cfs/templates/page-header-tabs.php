<?php
use Roots\Sage\Titles;
if (!empty($post)) {
  $header_bg = \Firebelly\Media\get_header_bg($post);
  $page_intro_title = get_the_title($post->post_parent);
  $logos = get_post_meta( $post->ID, '_cmb2_assets', true );
  $news = get_post_meta( $post->ID, '_cmb2_news_tabs', true );
  $press_releases = get_post_meta( $post->ID, '_cmb2_press_release_section', true );
}

?>
<header class="page-header -wide press">
  <?php if (!empty($header_bg)): ?>
  <div class="bg-image" <?= $header_bg ?>>
    <div class="gradient-l"></div>
    <div class="gradient-b"></div>
    <?= Firebelly\Utils\fb_crumbs() ?>
    <svg class="icon icon-notch bottom-left" aria-hidden="true"><use xlink:href="#icon-notch"/></svg>
  </div>
  <?php endif; ?>

  <div class="page-intro">
    <div class="color-wrap">
      <div class="page-content grid">

        <div class="one-half -left media page-titles">
          <h2 class="media_class">Press Assets</h2>

          <?php
          if ($logos) :
            echo '<h3>Logos</h3>';
            $images = array();
            foreach ( (array) $logos as $logo_id => $logo_url ):
              if ($logo_url) : ?>
              <a class="image" href="<?= $logo_url['_cmb2_brand_logo'] ?>" download>
                <?= wp_get_attachment_image($logo_url['_cmb2_brand_logo_id'], 'medium') ?>
              </a>
            <?php
          endif;
          endforeach;
          endif; ?>
        </div>

        <div class="one-half -right">
          <h1><?= !empty($page_intro_title) ? $page_intro_title : \Roots\Sage\Titles\title(); ?></h1>
          <div class="user-content">
            <?= apply_filters('the_content', $post->post_content); ?>
          </div>

          <div class="grid">
            <div class="tabs tabs-wrap">
              <ul class="tabs-nav">
                <li class="active">In The News</li>
                <li>Press Releases</li>
              </ul>

              <div class="tab-content">
                <?php if ($news) :?>
                <div class="wrap">
                  <div class="tab-pane">

                  <?php foreach ( (array) $news as $new ) :
                    if ($new) :
                      $headline = $new['_cmb2_headline'];
                      $subline = $new['_cmb2_subline'];
                      $link = $new['_cmb2_link'];
                      $images = $new['_cmb2_featured_img_id'];

                      echo '<article class="news-article">';

                      //$featured_image = wp_get_attachment_image( get_post_meta( get_the_ID(), $images, 1 ), 'medium' );
                      if ( !empty($images) ) : ?>
                        <?php if (!empty($link)): ?><a href="<?= $link ?>" class="image" target="_blank" rel="noopener"><?php endif; ?>
                          <?= wp_get_attachment_image($images, 'medium'); ?>
                        <?php if (!empty($link)): ?></a><?php endif; ?>
                      <?php endif;

                      if (!empty($headline)): ?>
                        <h2 class="series"><a href="<?= $link ?>" target="_blank" rel="noopener"><?= $headline ?></a></h2>
                        <?php if (!empty($subline)) : ?>
                          <div class="article-body user-content">
                            <p><?= $subline ?></p>
                          </div>
                        <?php endif; ?>
                      <?php endif;

                      echo '</article>';
                    endif;
                  endforeach; ?>
                    </div>
                  </div>
                <?php endif;

                if ($press_releases) : ?>
                <div class="wrap">

                  <div class="tab-pane">
                  <?php foreach ( (array) $press_releases as $press_release ) : ?>
                    <article class="press-release-article">

                      <?php $title = $press_release['_cmb2_title'];
                      $post_body = $press_release['_cmb2_post_body'];

                      if ( !empty($press_release['_cmb2_post_img']) && !empty($press_release['_cmb2_press_release_link']) ) :
                        $link = $press_release['_cmb2_press_release_link'];
                      ?>
                        <a href="<?= $link ?>" class="image" target="_blank" rel="noopener">
                          <?= wp_get_attachment_image($press_release['_cmb2_post_img_id'], 'medium'); ?>
                        </a>
                      <?php else:
                       echo '';
                      endif;
                      if (!empty($title)): ?>
                        <h2 class="series"><a href="<?= $link ?>" target="_blank" rel="noopener"><?= $title ?></a></h2>
                        <div class="article-body user-content">
                          <p><?= $post_body ?></p>
                        </div>
                      <?php endif; ?>
                    </article>
                  <?php endforeach; ?>
                  </div><!--/ .tab-pane -->

                </div><!--/ .wrap -->
                <?php endif; ?>
              </div><!-- .tab-content -->
            </div><!-- .tabs.tabs-wrap -->
          </div><!-- .grid -->

        </div>
      </div>
    </div>
  </div>
</header>

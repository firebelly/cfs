<header class="site-header" role="banner">
  <div class="wrap">
    <nav class="site-nav" role="navigation">
      <h1 class="brand"><a href="<?= esc_url(home_url('/')); ?>">
        <svg class="header-logo" xmlns="http://www.w3.org/2000/svg" width="139" height="139" viewBox="0 0 139 139"><g fill="#EB2332" fill-rule="evenodd"><path d="M70.438 31.938h-14a3.5 3.5 0 0 0-3.5 3.5v17.5h-17.5a3.5 3.5 0 0 0-3.5 3.5v14a3.5 3.5 0 0 0 3.5 3.5h17.5l21-21v-17.5a3.5 3.5 0 0 0-3.5-3.5M36.5 87.47l7 12.125a3.5 3.5 0 0 0 4.781 1.28l15.156-8.75 8.75 15.156a3.5 3.5 0 0 0 4.781 1.28l12.124-7a3.5 3.5 0 0 0 1.281-4.78l-8.75-15.156-28.686-7.686-15.156 8.75a3.5 3.5 0 0 0-1.28 4.78"/><path d="M101.56 89.094l7-12.125a3.5 3.5 0 0 0-1.28-4.781l-15.156-8.75 8.75-15.155a3.5 3.5 0 0 0-1.28-4.781l-12.125-7a3.5 3.5 0 0 0-4.781 1.281l-8.75 15.155 7.686 28.687 15.156 8.75a3.5 3.5 0 0 0 4.78-1.281M49.439 10.938h-14a3.5 3.5 0 0 0-3.5 3.5v17.5h-17.5a3.5 3.5 0 0 0-3.5 3.5v14a3.5 3.5 0 0 0 3.5 3.5h17.5l21-21v-17.5a3.5 3.5 0 0 0-3.5-3.5m40.128 117.126h14a3.5 3.5 0 0 0 3.5-3.5v-17.5l17.5-.001a3.5 3.5 0 0 0 3.5-3.5v-14a3.5 3.5 0 0 0-3.502-3.5h-17.5l-20.999 21 .001 17.5a3.5 3.5 0 0 0 3.5 3.5M7.472 57.597l-7 12.124a3.5 3.5 0 0 0 1.281 4.78l15.156 8.75-8.75 15.157a3.499 3.499 0 0 0 1.281 4.78l12.124 7a3.5 3.5 0 0 0 4.781-1.28l8.75-15.156-7.686-28.686-15.156-8.75a3.5 3.5 0 0 0-4.78 1.28M131.53 81.407l7-12.125a3.5 3.5 0 0 0-1.282-4.78l-15.156-8.75 8.75-15.156a3.5 3.5 0 0 0-1.282-4.78l-12.124-7a3.5 3.5 0 0 0-4.781 1.281l-8.75 15.156 7.688 28.686 15.156 8.75a3.5 3.5 0 0 0 4.781-1.282M28.817 117.44l7 12.124a3.5 3.5 0 0 0 4.78 1.28l15.157-8.75 8.75 15.157a3.5 3.5 0 0 0 4.78 1.28l12.125-7a3.5 3.5 0 0 0 1.28-4.78l-8.75-15.156-28.685-7.686-15.156 8.75a3.5 3.5 0 0 0-1.281 4.78m81.37-95.875L103.185 9.44a3.5 3.5 0 0 0-4.78-1.281l-15.156 8.75L74.5 1.754a3.5 3.5 0 0 0-4.782-1.28l-12.124 7a3.5 3.5 0 0 0-1.28 4.78l8.75 15.157 28.687 7.685 15.155-8.751a3.5 3.5 0 0 0 1.281-4.781"/></g></svg>
        <span class="sr-only"><?= get_bloginfo('name'); ?></span>
      </a></h1>
      <div class="logo-wordmark"><a href="<?= esc_url(home_url('/')); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="104" height="84" viewBox="0 0 104 84"><path d="M1.74 25.138C.578 23.928 0 22.263 0 20.148v-12.2c0-2.628.523-4.61 1.571-5.944C2.616.669 4.26 0 6.5 0c1.95 0 3.522.535 4.713 1.602 1.192 1.067 1.786 2.607 1.786 4.62v4.561H7.731V6.87c0-.759-.093-1.281-.277-1.57-.186-.287-.504-.43-.955-.43-.473 0-.796.165-.97.491-.175.33-.262.812-.262 1.447v13.278c0 .7.103 1.207.308 1.525.205.32.513.479.924.479.821 0 1.232-.669 1.232-2.004v-4.772h5.33V20.3c0 4.436-2.228 6.655-6.686 6.655-1.93 0-3.475-.607-4.636-1.817M15 26.46V0h5.267v9.455h2.495V0h5.267v26.46h-5.267V14.445h-2.495V26.46zm15 0h5.115V0H30zm8.74-1.322c-1.161-1.21-1.74-2.875-1.74-4.99v-12.2c0-2.628.523-4.61 1.571-5.944C39.616.669 41.26 0 43.5 0c1.95 0 3.522.535 4.713 1.602 1.192 1.067 1.786 2.607 1.786 4.62v4.561h-5.267V6.87c0-.759-.093-1.281-.277-1.57-.186-.287-.504-.43-.955-.43-.473 0-.796.165-.97.491-.175.33-.262.812-.262 1.447v13.278c0 .7.103 1.207.308 1.525.205.32.513.479.924.479.821 0 1.232-.669 1.232-2.004v-4.772h5.33V20.3c0 4.436-2.228 6.655-6.686 6.655-1.93 0-3.475-.607-4.636-1.817m18.112-7.181h2.402l-1.17-13.461h-.249l-.983 13.461zM51 26.46L53.554 0h8.966l2.524 26.46h-5.019l-.37-4.28h-3.204l-.308 4.28H51zm17.403-1.524C67.467 23.592 67 21.55 67 18.819V7.423c0-2.445.544-4.293 1.633-5.544C69.723.625 71.343 0 73.503 0c1.745 0 3.098.348 4.065 1.045.965.7 1.63 1.715 2 3.052.371 1.335.558 3.05.558 5.143h-5.177V6.87c0-.638-.087-1.13-.261-1.478-.175-.352-.489-.526-.94-.526-1.008 0-1.512.656-1.512 1.972v12.815c0 .8.106 1.406.311 1.817.202.41.563.616 1.077.616.513 0 .87-.206 1.08-.616.204-.41.307-1.018.307-1.817v-3.79h-1.419v-4.557h6.472v15.4h-2.128l-.893-2.218c-.943 1.645-2.402 2.467-4.375 2.467-1.91 0-3.332-.675-4.265-2.02m21.082-3.465c.165-.41.246-.986.246-1.724V6.807c0-.575-.075-1.042-.23-1.403-.153-.358-.467-.538-.94-.538-.883 0-1.325.669-1.325 2.003v12.908c0 .76.093 1.335.277 1.724.187.389.526.585 1.017.585.473 0 .79-.206.955-.616m-5.82 3.528C82.553 23.694 82 21.862 82 19.498V6.9c0-2.28.554-4 1.664-5.16C84.772.578 86.393 0 88.53 0c2.134 0 3.758.579 4.866 1.74 1.11 1.16 1.664 2.88 1.664 5.16v12.597c0 2.383-.553 4.222-1.664 5.516-1.108 1.295-2.732 1.942-4.866 1.942-2.137 0-3.758-.654-4.866-1.957M1 55.46V29h10.596v5.143H6.33v4.221h4.99v5.08H6.33V55.46zm18.655-16.663c.737 0 1.107-.803 1.107-2.402 0-.7-.03-1.223-.09-1.571-.062-.352-.174-.591-.34-.725-.164-.134-.4-.199-.708-.199h-1.232v4.897h1.263zM13 29h8.132c1.295 0 2.296.292 3.003.88.709.585 1.188 1.407 1.434 2.464.246 1.058.37 2.405.37 4.051 0 1.497-.196 2.67-.585 3.51-.392.843-1.07 1.427-2.034 1.754.802.168 1.38.57 1.739 1.204.36.638.541 1.5.541 2.589l-.062 10.008h-5.174V45.112c0-.74-.146-1.213-.432-1.418-.287-.206-.812-.308-1.572-.308V55.46H13V29zm15 26.46V29h10.596v5.112h-5.204v5.145h4.987v4.99h-4.987v6.067h5.544v5.146zm12 0V29h10.596v5.112h-5.204v5.145h4.99v4.99h-4.99v6.067h5.544v5.146zm19.253-4.682c.965 0 1.447-.473 1.447-1.416V35.714c0-.594-.044-1.042-.137-1.338-.093-.299-.261-.498-.51-.6-.246-.103-.625-.156-1.139-.156h-.585v17.158h.924zM53 29h7.302c1.888 0 3.304.523 4.25 1.571.942 1.045 1.428 2.598 1.446 4.651l.062 12.382c.022 2.61-.432 4.57-1.356 5.887-.92 1.313-2.452 1.969-4.589 1.969H53V29zm22.485 21.47c.165-.41.25-.986.25-1.724V35.807c0-.575-.079-1.042-.234-1.403-.153-.358-.467-.538-.94-.538-.883 0-1.325.666-1.325 2.003v12.905c0 .762.093 1.338.28 1.727.184.389.523.585 1.014.585.473 0 .79-.206.955-.616m-5.82 3.528c-1.111-1.304-1.665-3.14-1.665-5.5V35.9c0-2.28.554-4 1.664-5.16C70.772 29.578 72.396 29 74.53 29c2.138 0 3.758.579 4.87 1.74 1.107 1.16 1.66 2.88 1.66 5.16v12.597c0 2.383-.553 4.222-1.66 5.516-1.112 1.291-2.732 1.939-4.87 1.939-2.134 0-3.758-.65-4.866-1.954M83 55.46V29h8.07l2.218 16.14L95.507 29h8.132v26.46h-4.835V36.392L95.752 55.46h-4.74l-3.236-19.068V55.46zM1.602 82.169C.535 80.977 0 79.058 0 76.407v-2.585h5.208v3.294c0 1.316.42 1.973 1.26 1.973.473 0 .803-.14.986-.417.187-.277.28-.744.28-1.4 0-.865-.106-1.577-.308-2.14a4.682 4.682 0 0 0-.787-1.42c-.317-.379-.89-.97-1.711-1.77l-2.277-2.28C.884 67.936 0 66.028 0 63.932c0-2.26.52-3.98 1.556-5.159C2.592 57.591 4.106 57 6.1 57c2.38 0 4.097.635 5.143 1.895 1.048 1.263 1.57 3.241 1.57 5.933H7.427l-.03-1.82c0-.349-.1-.626-.293-.831-.196-.205-.47-.308-.819-.308-.41 0-.718.112-.924.339-.205.224-.308.535-.308.924 0 .862.495 1.755 1.478 2.679l3.083 2.958c.719.697 1.313 1.36 1.786 1.988.473.626.853 1.366 1.139 2.215.286.853.432 1.864.432 3.037 0 2.607-.479 4.583-1.434 5.93-.952 1.344-2.53 2.016-4.729 2.016-2.402 0-4.138-.595-5.205-1.786m15.139-.031c-1.16-1.21-1.742-2.875-1.742-4.99v-12.2c0-2.628.526-4.61 1.571-5.944C17.62 57.669 19.262 57 21.502 57c1.95 0 3.522.535 4.71 1.602 1.195 1.07 1.79 2.61 1.79 4.62v4.561h-5.268V63.87c0-.756-.093-1.281-.277-1.568-.186-.289-.504-.432-.955-.432-.473 0-.796.165-.97.491-.175.33-.262.812-.262 1.45v13.275c0 .7.103 1.207.305 1.525.205.32.513.479.927.479.822 0 1.232-.669 1.232-2.004v-4.772h5.33v4.99c0 4.433-2.231 6.652-6.686 6.652-1.93 0-3.475-.607-4.636-1.817M29 83.46V57h5.267v9.455h2.495V57h5.267v26.46h-5.267V71.445h-2.495V83.46zm22.485-4.99c.165-.41.246-.986.246-1.724V63.807c0-.575-.078-1.042-.23-1.403-.153-.358-.467-.538-.94-.538-.883 0-1.325.669-1.325 2.003v12.908c0 .76.093 1.335.277 1.724.187.389.526.585 1.017.585.473 0 .79-.206.955-.616m-5.824 3.528C44.554 80.694 44 78.862 44 76.498V63.9c0-2.28.554-4 1.661-5.16 1.111-1.161 2.732-1.74 4.87-1.74 2.133 0 3.757.579 4.865 1.74 1.11 1.16 1.664 2.88 1.664 5.16v12.597c0 2.383-.553 4.222-1.664 5.516-1.108 1.295-2.732 1.939-4.866 1.939-2.137 0-3.758-.65-4.869-1.954m20.824-3.528c.165-.41.25-.986.25-1.724V63.807c0-.575-.079-1.042-.234-1.403-.153-.358-.467-.538-.94-.538-.883 0-1.325.669-1.325 2.003v12.908c0 .76.093 1.335.28 1.724.184.389.523.585 1.014.585.473 0 .79-.206.955-.616m-5.82 3.528C59.553 80.694 59 78.862 59 76.498V63.9c0-2.28.554-4 1.664-5.16C61.772 57.578 63.396 57 65.53 57c2.138 0 3.758.579 4.87 1.74 1.107 1.16 1.66 2.88 1.66 5.16v12.597c0 2.383-.553 4.222-1.66 5.516-1.112 1.295-2.732 1.939-4.87 1.939-2.134 0-3.758-.65-4.866-1.954M75 83.46V57h5.298v21.961h5.454v4.499z" fill="#F0F0F0" fill-rule="evenodd"/></svg>
      </a></div>

      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
</header>

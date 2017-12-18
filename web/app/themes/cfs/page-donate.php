<?php
/*
  Template name: Donate
*/

get_template_part('templates/page', 'header');
?>

<div class="secondary-content"><div class="color-wrap">
  <div class="wrap">
    <div class="switches button-twins grid">
      <div class="one-half -left">
        <a href="#" class="button -red -wide" data-switch="recurring">Give Monthly</a>
      </div>
      <div class="one-half -right">
        <a href="#" class="button -red -wide" data-switch="single">Give Once</a>
      </div>
    </div>

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donate-form switch-pane" data-switch="single">
      <input type="hidden" name="cmd" value="_donations">
      <input type="hidden" name="business" value="<?= \Firebelly\SiteOptions\get_option('paypal_id'); ?>">
      <input type="hidden" name="item_name" value="Support CFS">
      <input type="hidden" name="item_number" value="One Time Donation">
      <input type="hidden" name="currency_code" value="USD">
      <input type="hidden" name="no_shipping" value="1">

      <hr>
      <h2>Give Once</h2>
      <hr>
      <h3>Choose an Amount</h3>
      <?php
      $options = get_post_meta($post->ID, 'donation_single_options', true );
      foreach ((array)$options as $key=>$option){
        $description = !empty($option['description']) ? $option['description'] : '';
        $amount = !empty($option['amount']) ? str_replace('$','',$option['amount']) : '';
        if ($amount) {
            echo '      <label class="control radio">
            <input type="radio" name="amount" value="'.$amount.'" required>
            <span class="control-indicator"></span>
            <span class="control-text">
              <span class="amount">$'.$amount.'</span>
              <span class="description">'.$description.'</span>
            </span>
          </label>';
        }
      }
      ?>
      <label class="control radio other-amount">
        <input type="radio" name="amount" value="" required>
        <span class="control-indicator"></span>
        <span class="control-text">
          <div class="input-item">
            <input type="text" value="" pattern="[\d\.]*">
            <label>Other (Enter Amount)</label>
          </div>
        </span>
      </label>
      <button class="button -red -wide">Complete Donation</button>
    </form>

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donate-form donate-recurring switch-pane" data-switch="recurring">
      <input type="hidden" name="cmd" value="_xclick-subscriptions">
      <input type="hidden" name="business" value="<?= \Firebelly\SiteOptions\get_option('paypal_id'); ?>">
      <input type="hidden" name="currency_code" value="USD">
      <input type="hidden" name="no_shipping" value="1">
      <input type="hidden" name="p3" value="1">
      <input type="hidden" name="t3" value="M">
      <input type="hidden" name="src" value="1">
      <input type="hidden" name="sra" value="1">

      <input type="hidden" name="item_name" value="Monthly CFS Donation">
      <input type="hidden" name="on0" value="Gift Level">
      <input type="hidden" name="os0" value="">

      <hr>
      <h2>Give Monthly</h2>
      <hr>
      <h3>Choose an Amount</h3>
      <?php
      $options = get_post_meta($post->ID, 'donation_multiple_options', true );
      foreach ((array)$options as $key=>$option){
        $description = !empty($option['description']) ? $option['description'] : '';
        $amount = !empty($option['amount']) ? str_replace('$','',$option['amount']) : '';
        if ($amount) {
            echo '      <label class="control radio">
            <input type="radio" name="a3" value="'.$amount.'" required>
            <span class="control-indicator"></span>
            <span class="control-text">
              <span class="amount">$'.$amount.'</span>
              <span class="description">'.$description.'</span>
            </span>
          </label>';
        }
      }
      ?>

      <div class="input-item select">
        <input type="hidden" name="on1" value="Thank you gift">
        <select name="os1">
          <?php
          $options = get_post_meta($post->ID, 'donation_gift_options', true );
          foreach ((array)$options as $key=>$option){
            $description = !empty($option['description']) ? $option['description'] : '';
            if ($amount) {
              echo '<option value="'.$description.'">'.$description.'</option>';
            }
          }
          ?>
        </select>
        <label>Choose a <span class="show-for-medium-up -inline">thank you</span> gift</span></label>
        <span class="arrow"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg></span>
      </div>

      <button class="button -red -wide">Complete via PayPal</button>
    </form>
  </div>
</div></div>

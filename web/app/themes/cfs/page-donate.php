<?php get_template_part('templates/page', 'header'); ?>

<div class="secondary-content"><div class="color-wrap">
  <div class="wrap">
    <div class="switches grid">
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
      <label class="control radio">
        <input type="radio" name="amount" value="25">
        <span class="control-indicator"></span>
        <span class="amount">$25</span>
        <span class="description">Pays for <strong>transit cards</strong> for a Freedom Fellow to participate our Summer Leadership Institute.</span>
      </label>
      <label class="control radio">
        <input type="radio" name="amount" value="60">
        <span class="control-indicator"></span>
        <span class="amount">$60</span>
        <span class="description">Covers the <strong>stipends</strong> for two youth to facilitate an "Understanding Adultism" training for adults.</span>
      </label>
      <label class="control radio">
        <input type="radio" name="amount" value="100" required>
        <span class="control-indicator"></span>
        <span class="amount">$100</span>
        <span class="description">Provides <strong>healthy meals</strong> for our Project HealUs program.</span>
      </label>
      <label class="control radio">
        <input type="radio" name="amount" value="250" required>
        <span class="control-indicator"></span>
        <span class="amount">$250</span>
        <span class="description">Underwrites the cost of an emerging organizer to attend our Rev Up Training Institute.</span>
      </label>
      <label class="control radio">
        <input type="radio" name="amount" value="500" required>
        <span class="control-indicator"></span>
        <span class="amount">$500</span>
        <span class="description">Sponsors a Freedom Fellow.</span>
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
      <label class="control radio">
        <input type="radio" name="a3" value="10" required>
        <span class="control-indicator"></span>
        <span class="amount">$10</span>
        <span class="description">Collaborator</span>
      </label>
      <label class="control radio">
        <input type="radio" name="a3" value="20" required>
        <span class="control-indicator"></span>
        <span class="amount">$20</span>
        <span class="description">Organizer</span>
      </label>
      <label class="control radio">
        <input type="radio" name="a3" value="30" required>
        <span class="control-indicator"></span>
        <span class="amount">$30</span>
        <span class="description">Partner</span>
      </label>
      <label class="control radio">
        <input type="radio" name="a3" value="50" required>
        <span class="control-indicator"></span>
        <span class="amount">$50</span>
        <span class="description">Catalyst</span>
      </label>
      <label class="control radio">
        <input type="radio" name="a3" value="100" required>
        <span class="control-indicator"></span>
        <span class="amount">$100</span>
        <span class="description">Visionary</span>
      </label>

      <div class="input-item select">
        <input type="hidden" name="on1" value="Thank you gift">
        <select name="os1">
          <option value="No thanks">No gift, thanks</option>
          <option value="CFS Tote Bag">CFS Tote Bag</option>
          <option value="CFS T-shirt S">CFS T-shirt S</option>
          <option value="CFS T-shirt M">CFS T-shirt M</option>
          <option value="CFS T-shirt L">CFS T-shirt L</option>
          <option value="CFS T-shirt XL">CFS T-shirt XL</option>
          <option value="CFS T-shirt 2XL">CFS T-shirt 2XL</option>
        </select>
        <label>Choose a thank you gift</label>
        <span class="arrow"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg></span>
      </div>

      <button class="button -red -wide">Complete Donation</button>
    </form>
  </div>
</div></div>

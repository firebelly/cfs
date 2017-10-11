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

    <form action="#" method="post" class="donate-form switch-pane" data-switch="single">
      <h2>Give Once</h2>
      <hr>
      <h3>Choose an Amount</h3>
      <label class="control radio">
        <input type="radio" id="radio1" name="radio">
        <span class="control-indicator"></span>
        <span class="amount">$100</span>
        <span class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</span>
      </label>
      <label class="control radio">
        <input type="radio" id="radio2" name="radio">
        <span class="control-indicator"></span>
        <span class="amount">$500</span>
        <span class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</span>
      </label>
    </form>

    <form action="#" method="post" class="donate-form switch-pane" data-switch="recurring">
      <h2>Give Monthly</h2>
      <hr>
      <h3>Choose an Amount</h3>
      <label class="control radio">
        <input type="radio" id="radio1" name="radio">
        <span class="control-indicator"></span>
        <span class="amount">$100</span>
        <span class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</span>
      </label>
      <label class="control radio">
        <input type="radio" id="radio2" name="radio">
        <span class="control-indicator"></span>
        <span class="amount">$500</span>
        <span class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</span>
      </label>
    </form>
  </div>
</div></div>

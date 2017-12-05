<?php get_template_part('templates/page', 'header'); ?>

<div class="secondary-content user-content"><div class="color-wrap">
  <div class="wrap">
    <form action="#" method="post" class="custom-trainings-form">
      <hr>
      <h2>Request Form</h2>

      <fieldset class="active">
        <h3>Which workshop(s) are you interested in? <span class="note">Check all that apply.</span></h3>

        <label class="control checkbox">
          <input type="checkbox" name="checkbox">
          <span class="control-indicator"></span>
          <span class="control-text">Understanding Adultism & Building Partnership with Youth</span>
        </label>
        <label class="control checkbox">
          <input type="checkbox" name="checkbox">
          <span class="control-indicator"></span>
          <span class="control-text">Identity, Power & Oppression</span>
        </label>
        <label class="control checkbox">
          <input type="checkbox" name="checkbox">
          <span class="control-indicator"></span>
          <span class="control-text">Working with LGBTQ Youth</span>
        </label>
      </fieldset>

      <fieldset>
        <h3>What dates are you interested in?</h3>
        <div class="input-item">
          <input type="text" name="dates">
          <label for="">Enter Date Here</label>
        </div>
      </fieldset>

      <fieldset>
        <h3>Basic information</h3>
        <div class="input-item">
          <input type="text" name="dates">
          <label for="">Organization Name</label>
        </div>
        <div class="input-item">
          <input type="text" name="dates" required>
          <label for="">Contact Name</label>
        </div>

        <div class="grid">
          <div class="one-half -left">
            <div class="input-item">
              <input type="text" name="dates">
              <label for="">Contact Phone</label>
            </div>
          </div>
          <div class="one-half -right">
            <div class="input-item">
              <input type="text" name="dates">
              <label for="">Contact Email</label>
            </div>
          </div>
        </div>

        <div class="grid">
          <div class="one-half -left">
            <div class="input-item">
              <input type="text" name="dates">
              <label for="">City</label>
            </div>
          </div>
          <div class="one-half -right">
            <div class="input-item">
              <input type="text" name="dates">
              <label for="">State</label>
            </div>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <h3>Additional Information</h3>
        <div class="input-item select">
          <select name="type-of-organization">
            <option value="Foundation">Foundation</option>
            <option value="Other">Other</option>
          </select>
          <label for="">Type of Organization</label>
          <span class="arrow"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg></span>
        </div>

        <div class="input-item select">
          <select name="size-of-group">
            <option value="1–5">1–5</option>
            <option value="6–10">6—10</option>
            <option value="11–20">11–20</option>
            <option value="21–30">21—30</option>
            <option value="31–50">31—50</option>
            <option value="51+">51+</option>
          </select>
          <label for="">Size of Group</label>
          <span class="arrow"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg></span>
        </div>

        <div class="input-item select">
          <select name="organization-budget">
            <option value="$0–$50,000">$0–$50,000</option>
            <option value="$50,001–$100,000">$50,001–$100,000</option>
            <option value="$100,001–$250,000">$100,001–$250,000</option>
            <option value="$250,001–$500,000">$250,001–$500,000</option>
          </select>
          <label for="">Organization Budget</label>
          <span class="arrow"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg></span>
        </div>
      </fieldset>

      <button type="submit" class="button -red -wide">Submit</button>
    </form>
  </div>
</div></div>

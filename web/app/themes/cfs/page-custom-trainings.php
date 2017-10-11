<?php get_template_part('templates/page', 'header'); ?>

<div class="secondary-content"><div class="color-wrap">
  <div class="wrap">
    <form action="#" method="post" class="custom-trainings-form">
      <fieldset class="active">
        <h3>Which workshop(s) are you interested in? <span class="note">Check all that apply.</span></h3>

        <label class="control checkbox">
          <input type="checkbox" name="checkbox">
          <span class="control-indicator"></span>
          Understanding Adultism & Building Partnership with Youth
        </label>
        <label class="control checkbox">
          <input type="checkbox" name="checkbox">
          <span class="control-indicator"></span>
          Identity, Power & Oppression
        </label>
        <label class="control checkbox">
          <input type="checkbox" name="checkbox">
          <span class="control-indicator"></span>
          Working with LGBTQ Youth
        </label>
      </fieldset>

      <fieldset>
        <h3>What dates are you interested in?</h3>
        <div class="input-item">
          <input type="text" name="dates" required>
          <label for="">Enter Date Here</label>
        </div>
      </fieldset>

      <fieldset>
        <h3>Basic information</h3>
        <div class="input-item">
          <input type="text" name="dates" required>
          <label for="">Organization Name</label>
        </div>
        <div class="input-item">
          <input type="text" name="dates" required>
          <label for="">Contact Name</label>
        </div>

        <div class="grid">
          <div class="one-half -left">
            <div class="input-item">
              <input type="text" name="dates" required>
              <label for="">Contact Phone</label>
            </div>
          </div>
          <div class="one-half -right">
            <div class="input-item">
              <input type="text" name="dates" required>
              <label for="">Contact Email</label>
            </div>
          </div>
        </div>

        <div class="grid">
          <div class="one-half -left">
            <div class="input-item">
              <input type="text" name="dates" required>
              <label for="">City</label>
            </div>
          </div>
          <div class="one-half -right">
            <div class="input-item">
              <input type="text" name="dates" required>
              <label for="">State</label>
            </div>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <h3>Additional Information</h3>
        <div class="input-item select">
          <select name="foo">
            <option value="#">One</option>
            <option value="#">Two</option>
          </select>
          <label for="">Type of Organization</label>
        </div>

        <div class="input-item select">
          <select name="foo">
            <option value="#">One</option>
            <option value="#">Two</option>
          </select>
          <label for="">Size of Group</label>
        </div>

        <div class="input-item select">
          <select name="foo">
            <option value="#">One</option>
            <option value="#">Two</option>
          </select>
          <label for="">Organization Budget</label>
        </div>
      </fieldset>

      <button class="button -red -wide">Submit</button>
    </form>
  </div>
</div></div>

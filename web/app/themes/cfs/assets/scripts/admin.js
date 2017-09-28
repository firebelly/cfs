/*
 * Firebelly custom admin behavior
 */

// Good design for good reason for good namespace
var FB_admin = (function($) {

  var _updateTimer;

  function _init() {

    // Hack the update from bottom plugin to show it earlier
    $(window).scroll(function(){
      clearTimeout(_updateTimer);
      _updateTimer = setTimeout(function() {
        $('#updatefrombottom').toggle( $(window).scrollTop() > $('#submitdiv').height() );
      }, 150);
    });

    // Custom tabbed groups in CMB2 (see https://gist.github.com/natebeaty/39672b0d96eedf621dadf24c8ddc9a32)
    $('body').on('click', '.tabs-nav a', function(e) {
      e.preventDefault();
      var $tabs = $(this).closest('.cmb2-tabs');
      $tabs.find('.tabs-nav li,.tab-content').removeClass('current');
      $(this).parent('li').addClass('current');
      $tabs.find($(this).attr('href').replace('#', '.')).addClass('current');
    });
  }
  // public functions
  return {
    init: _init
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(FB_admin.init);

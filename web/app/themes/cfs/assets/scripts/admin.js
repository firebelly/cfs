/*
 * Firebelly custom admin behavior
 */

// Good design for good reason for good namespace
var FB_admin = (function($) {

  var _updateTimer,
  _submitDivHeight;

  function _init() {

    // Hack the update from bottom plugin to show it earlier
    _submitDivHeight = $('#submitdiv').height();
    $(window).scroll(function(){
      clearTimeout(_updateTimer);
      _updateTimer = setTimeout(function() {
        $('#updatefrombottom').toggle( $(window).scrollTop() > _submitDivHeight );
      }, 150);
    });

    // Eventbrite Importer
    $('#eventbrite-import-form').on('submit', function(e) {
      e.preventDefault();
      window.scrollTo(0,0);

      // Show spinner + Working text after submitting
      var $log = $('#eventbrite-import-form .log-output')
      $log.html('<p><img src="/wp/wp-admin/images/spinner-2x.gif" style="display:inline-block; width:20px; height:auto;"> Working... (be patient, can take a long time)</p>');

      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: $(this).serialize(),
        success: function(data) {
          // Display log messages from import script
          if (data.notice && data.notice.length > 0) {
            $log.html('<h3>Notices:</h3><ul><li>' + data.notice.join('</li><li>') + '</li></ul>');
          }
          if (data.error && data.error.length > 0) {
            $('<h3>Errors:</h3><ul><li>' + data.error.join('</li><li>') + '</li></ul>').appendTo($log);
          }
          if (data.stats.exec_time) {
            $('<p><b>Import took ' + data.stats.exec_time + ' seconds.</b></p>').appendTo($log)
          }
        }
      });

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

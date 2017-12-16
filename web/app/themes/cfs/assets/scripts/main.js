// CFS - Firebelly 2017
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var CFS = (function($) {

  var breakpoint_md = false,
      breakpoint_nav = false,
      breakpoint_array = [768, 950],
      headerOffset;

  function _init() {
    // Set screen size vars
    _resize();

    // Fit them vids!
    $('main').fitVids();

    _initNav();
    _initBigClicky();
    _initAccordions();
    _initForms();
    _initSlickSliders();
    // _initStickyElements();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        _hideMobileNav();
      }
    });
    $('body').on('click', 'a[href="#"]', function(e) {
      e.preventDefault();
    });

    // Smoothscroll links
    $(document).on('click', 'a.smoothscroll', function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      _scrollBody($(href));
    });

    // Scroll down to hash after page load
    $(window).load(function() {
      if (window.location.hash) {
        _scrollBody($(window.location.hash), 500);
      }
    });

  } // end init()

  function _scrollBody(el, duration) {
    $('html, body').animate({scrollTop: $(el).offset().top + headerOffset}, duration, 'easeOutSine');
  }

  function _initAccordions() {
    // Add SVG arrow to accordion_shortcode titles
    $('<svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg>').appendTo('.accordion:not(.fb-accordion) .accordion-title');

    // Custom fb-accordions
    $('.fb-accordion').each(function() {
      $(this).find('.accordion-title:not(.always-open)').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('open')) {
          _closeAccordion(this);
        } else {
          _openAccordion(this);
        }
      });
    });
  }
  function _openAccordion(el) {
    var $el = $(el);
    // _scrollBody($el, 250);
    $el.next().addClass('open').clearQueue().stop().slideDown(250, function() {
      $el.next().find('.media-block').addClass('active');
    });
    $el.addClass('open read')
      .attr({
        'aria-selected': 'true',
        'aria-expanded': 'true'
      })
      .next().attr({
        'aria-hidden': 'false'
      });
  }
  function _closeAccordion(el) {
    var $el = $(el);
    if ($el.next().find('.media-block')) {
      $el.next().find('.media-block').removeClass('active');
      setTimeout(function() {
        $el.removeClass('open').next().removeClass('open').slideUp(250);
      }, 200);
    } else {
      $el.removeClass('open').next().removeClass('open').slideUp(250);
    }

    // Set accessibility attributes
    $el.attr({
      'aria-selected': 'false',
      'aria-expanded': 'false'
    })
    .next().attr({
      'aria-hidden': 'true'
    });
  }

  // Large click areas by adding "bigclicky" class
  function _initBigClicky() {
    $(document).on('click', '.bigclicky', function(e) {
      if (!$(e.target).is('a')) {
        e.preventDefault();
        var link = $(this).find('a:not(.edit-link):first');
        var href = link.attr('href');
        if (href) {
          if (e.metaKey || link.attr('target')) {
            window.open(href);
          } else {
            location.href = href;
          }
        }
      }
    });
  }

  function _initNav() {
    // Search Icon
    $('<svg class="icon icon-search" aria-hidden="true"><title>Search</title><use xlink:href="#icon-search"/></svg>').appendTo('#menu-main-nav .menu-search a');
    $('body').on('click', '.menu-search a,.nav-search a', function(e) {
      e.preventDefault();
      $('.site-header').toggleClass('search-active');
      if ($('.site-header').hasClass('search-active')) {
        $('.site-header .search-field').focus();
      }
    });
    // Add svg arrows for mobile nav submenus
    $('#menu-main-nav > li.menu-item-has-children > a').append('<svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg>');
    $('#menu-main-nav > li.menu-item-has-children > a svg').on('click', function(e) {
      e.preventDefault();
      $(this).parents('li:first').toggleClass('-active');
    });
    // Mobile menu toggle
    $('<button class="menu-toggle"><span>Menu</span> <svg class="icon icon-x" aria-hidden="true"><title>Close</title><use xlink:href="#icon-x"/></svg></button>')
      .prependTo('.site-header .wrap')
      .on('click', function(e) {
      if (!$('.site-nav').is('.-active')) {
        _showMobileNav();
      } else {
        _hideMobileNav();
      }
    });
  }
  function _showMobileNav() {
    $('.menu-toggle, body').addClass('menu-open');
    $('.site-nav').addClass('-active');
  }
  function _hideMobileNav() {
    $('.menu-toggle, body').removeClass('menu-open');
    $('.site-nav').removeClass('-active');
  }

  // Init slideshows with custom buttons
  function _initSlickSliders() {
    $('.slider').slick({
      slide: '.slide-item',
      autoplay: true,
      arrows: true,
      prevArrow: '<div class="button previous-item button-prev nav-button"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg><span class="sr-only">Prev</span></div>',
      nextArrow: '<div class="button next-item button-next nav-button"><svg class="icon icon-arrow-right" aria-hidden="true"><use xlink:href="#icon-arrow-right"/></svg><span class="sr-only">Next</span></div>',
      dots: false,
      autoplaySpeed: 6000,
      speed: 300,
      lazyLoad: 'ondemand'
    });
  }

  // Init form behavior
  function _initForms() {
    // Program page subscribe links scroll down to footer, change cc_list_id, and give focus to Name field
    $('body').on('click', 'a.subscribe-to-newsletter', function(e) {
      e.preventDefault();
      var program_title = $('.page-titles h1').text();
      $('#site-footer .newsletter h3').text('Stay Updated on ' + program_title);
      var cc_list_id = $(this).attr('data-cc-list-id');
      $('#site-footer .newsletter-form input[name="cc_list_id"]').val(cc_list_id);
      _scrollBody($('#site-footer'));
      $('#site-footer input[name="cc_name"]').focus();
    });

    // Add SVGs to required form elements for error states
    $('form input').each(function() {
      var $this = $(this);
      // Initial state of inputs with value
      if ($this.val()!=='' && $this.attr('type')!=='hidden') {
        $this.addClass('has-input').parent().addClass('has-input');
      }
      if ($this.prop('required') && $this.attr('type')!=='radio' && !$this.hasClass('no-error-styles')) {
        $('<svg class="icon icon-circle-x" aria-hidden="true" role="presentation"><use xlink:href="#icon-circle-x"/></svg>').appendTo($(this).parent());
      }
    });

    // Clicking on label gives input focus
    $('body').on('click', '.input-item label', function(e) {
      $(this).prev('input').focus();
    });

    // Buttons that switch between showing two forms (Donate page)
    $('body').on('click', '.switches a', function(e) {
      e.preventDefault();
      var sw = $(this).attr('data-switch');
      $('.switches a').removeClass('active');
      $(this).addClass('active');
      $('.switch-pane').removeClass('active');
      $('.switch-pane[data-switch=' + sw + ']').addClass('active');
    });

    // Set additional hidden input for donation description as you select radio buttons
    $('.donate-recurring input[type=radio]').on('change', function() {
      var level = $(this).parent().find('.description').text();
      $('.donate-recurring input[name=os0]').val(level);
    });

    // Other value handling in donation form
    $('.donate-form .other-amount input[type=text]').on('focus change keyup', function(e) {
      var val = $(this).val();
      $(this).parents('.control:first').find('input[type=radio]').prop('checked', true).val(val);
    });

    // Add .has-input for styling when field is changed
    $('form input,select').on('keyup change blur', _checkFormInput);

    // Add .has-touched for styling errors (otherwise :invalid shows error styling before form is interacted with)
    $('form input[required]').one('blur keydown', function() {
      if (!$(this).hasClass('no-error-styles')) {
        $(this).addClass('has-touched').parent('form').addClass('has-touched');
      }
    });
    $('form [type=submit]').on('click', function() {
      console.log('foo');
      // Add has-touched to trigger styles on submit
      $(this).parent('form').find('input[required]:not(.no-error-styles)').each(function() {
        $(this).toggleClass('has-input', has_input).parent().toggleClass('has-input', has_input);
        $(this).parent().toggleClass('invalid', ($(this).prop('required') && $(this).val() === ''));
      });
    });

    // Newsletter form in footer
    $('footer form.newsletter-form').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        url: wp_ajax_url,
        method: 'post',
        dataType: 'json',
        data: $(this).serialize()
      }).done(function(response) {
        alert(response.data.message);
      });
    });
  }
  function _checkFormInput(e) {
    // Ignore tab keyup (would trigger error class when tabbing into field for the first time)
    if (e.which === 9) {
      return;
    }

    var has_input = $(e.target).val() !== '';
    $(e.target).toggleClass('has-input', has_input).parent().toggleClass('has-input', has_input);
    $(e.target).parent().toggleClass('invalid', ($(e.target).prop('required') && $(e.target).val() === ''));
  }

  // Not using this yet, but registration boxes should be sticky as you scroll
  function _initStickyElements() {
    if ($('.scroll-stick').length) {
      var sticky = new Waypoint.Sticky({
        element: $('.scroll-stick')
      });
    }
  }

  // Track ajax pages in Analytics
  function _trackPage() {
    if (typeof ga !== 'undefined') { ga('send', 'pageview', document.location.href); }
  }

  // Track events in Analytics
  function _trackEvent(category, action) {
    if (typeof ga !== 'undefined') { ga('send', 'event', category, action); }
  }

  // Called in quick succession as window is resized
  function _resize() {
    screenWidth = document.documentElement.clientWidth;
    breakpoint_md = (screenWidth > breakpoint_array[0]);
    breakpoint_nav = (screenWidth > breakpoint_array[1]);
    if (breakpoint_nav) {
      // Hide mobile nav on larger screens
      _hideMobileNav();
    } else {
      // Hide nav search form on smaller screens
      $('.site-header').removeClass('search-active');
    }
    _setHeaderOffset();
  }

  // Header offset w/wo wordpress admin bar
  function _setHeaderOffset() {
    if (breakpoint_md) {
      if ($('body').hasClass('admin-bar')) {
        wpAdminBar = true;
        headerOffset = $('#wpadminbar').outerHeight() + $('.site-header').outerHeight();
      } else {
        headerOffset = $('.site-header').outerHeight();
      }
    } else {
      headerOffset = 0;
    }
  }

  // Public functions
  return {
    init: _init,
    resize: _resize
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(CFS.init);

// Zig-zag the mothership
jQuery(window).resize(CFS.resize);

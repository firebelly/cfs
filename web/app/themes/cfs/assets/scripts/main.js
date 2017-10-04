// CFS - Firebelly 2017
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var CFS = (function($) {

  var screen_width = 0,
      breakpoint_small = false,
      breakpoint_medium = false,
      breakpoint_large = false,
      breakpoint_array = [480,1000,1200],
      $siteHeader = $('.site-header'),
      $siteNav = $('.site-nav'),
      $document,
      $sidebar,
      headerOffset,
      loadingTimer,
      History = window.History,
      State,
      root_url = History.getRootUrl(),
      relative_url,
      original_url,
      original_page_title = document.title,
      page_cache = {},
      ajax_handler_url = '/app/themes/cfs/lib/ajax-handler.php',
      page_at;

  function _init() {
    // Cache some common DOM queries
    $document = $(document);
    $('body').addClass('loaded');

    // Set screen size vars
    _resize();
    _setHeaderOffset();

    // Fit them vids!
    $('main').fitVids();

    _initNav();
    // _initSearch();
    // _initLoadMore();
    _initBigClicky();
    _initAccordions();
    // _initFormActions();
    // _initBadgeOverlay();
    // _initItemGrid();
    // _initProgramOverlay();
    // _initStateHandling();
    // _initDraggableElements();
    // _initSlickSliders();
    // _initStickyElements();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        _hideMobileNav();
      }
    });

    // Smoothscroll links
    $('a.smoothscroll').click(function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      _scrollBody($(href));
    });

    // Scroll down to hash after page load
    $(window).load(function() {
      if (window.location.hash) {
        _scrollBody($(window.location.hash));
      }
    });

  } // end init()

  function _scrollBody(element, duration, delay, offset) {
    if (offset === undefined) {
      offset = headerOffset - 1;
    }

    element.velocity('scroll', {
      duration: duration,
      delay: delay,
      offset: -offset,
    }, 'easeOutSine');
  }

  function _initAccordions() {
    // Add SVG arrow to accordion shortcode titles
    $('<svg class="icon icon-arrow-right" aria-hidden="hidden" role="image"><use xlink:href="#icon-arrow-right"/></svg>').appendTo('.accordion:not(.fb-accordion) .accordion-title');

    // Custom fb-accordions
    $('.fb-accordion').each(function() {
      $(this).find('.accordion-title').on('click', function(e) {
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
    $el.next().clearQueue().stop().slideDown(250, function() {
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
        $el.removeClass('open').next().slideUp(250);
      }, 200);
    } else {
      $el.removeClass('open').next().slideUp(250);
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
        var link = $(this).find('a:first');
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

  function _injectSvgSprite() {
    boomsvgloader.load('/app/themes/girlsgarage/assets/svgs/build/svgs-defs.svg');
  }

  function _initNav() {
    // Search Icon
    $('<svg class="icon icon-search" aria-hidden="hidden" role="image"><title>Search</title><use xlink:href="#icon-search"/></svg>').appendTo('#menu-main-nav .menu-search a');
    $('body').on('click', '.menu-search a,.nav-search a', function(e) {
      e.preventDefault();
      $('.site-header').toggleClass('search-active');
      if ($('.site-header').hasClass('search-active')) {
        $('.search-form .search-field').focus();
      }
    });
    // Mobile menu toggle
    $('<button class="menu-toggle">Menu</button>')
      .prependTo('.site-header')
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

  function _initSlickSliders() {
    $('.slider').slick({
      slide: '.slide-item',
      autoplay: true,
      arrows: true,
      prevArrow: '<div class="previous-item button-prev nav-button"><svg class="icon icon-circle-stroke" aria-hidden="hidden" role="image"><use xlink:href="#icon-circle-stroke"/></svg><svg class="icon icon-arrow-left button-next" aria-hidden="hidden" role="image"><use xlink:href="#icon-arrow-left"/></svg><span class="sr-only">Prev</span></div>',
      nextArrow: '<div class="next-item button-next nav-button"><svg class="icon icon-circle-stroke" aria-hidden="hidden" role="image"><use xlink:href="#icon-circle-stroke"/></svg><svg class="icon icon-arrow-right button-next" aria-hidden="hidden" role="image"><use xlink:href="#icon-arrow-right"/></svg><span class="sr-only">Next</span></div>',
      dots: false,
      autoplaySpeed: 6000,
      speed: 300,
      lazyLoad: 'ondemand'
    });

    $('a.lightbox').swipebox({
      autoplayVideos: false,
      loopAtEnd: false,
      afterOpen: function() {
        $('#swipebox-slider .slide:last-of-type').remove();
      }
    });

  }

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
    breakpoint_small = (screenWidth > breakpoint_array[0]);
    breakpoint_medium = (screenWidth > breakpoint_array[1]);
    breakpoint_large = (screenWidth > breakpoint_array[2]);

    _setHeaderOffset();
  }

  // Header offset w/wo wordpress admin bar
  function _setHeaderOffset() {
    if (breakpoint_medium) {
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
    resize: _resize,
    scrollBody: function(section, duration, delay) {
      _scrollBody(section, duration, delay);
    }
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(CFS.init);

// Zig-zag the mothership
jQuery(window).resize(CFS.resize);

/**
 * File custom.js.
 *
 * Theme Custom enhancements for a better user experience.
 *
 */
// the semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;
(function($, window, document, undefined) {
    'use strict';

    var BUDDYX = window.BUDDYX || {};

    /**
     * Utility: Create a focus trap within a container.
     * Keeps keyboard focus within the container when TAB is pressed.
     *
     * @param {jQuery} $container - The container element to trap focus within.
     * @param {Event} e - The keydown event.
     * @return {boolean} Whether focus was trapped.
     */
    BUDDYX.trapFocus = function($container, e) {
        if (e.key !== 'Tab') {
            return false;
        }

        var $focusable = $container.find('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])').filter(':visible');
        if (!$focusable.length) {
            return false;
        }

        var $first = $focusable.first();
        var $last = $focusable.last();

        if (e.shiftKey) {
            if (document.activeElement === $first[0]) {
                e.preventDefault();
                $last.focus();
                return true;
            }
        } else {
            if (document.activeElement === $last[0]) {
                e.preventDefault();
                $first.focus();
                return true;
            }
        }
        return false;
    };

    /**
     * Key code constants for keyboard navigation.
     * Using key names instead of deprecated keyCode.
     */
    BUDDYX.keys = {
        ESCAPE: 'Escape',
        TAB: 'Tab',
        ENTER: 'Enter'
    };

    // Site Loader
    BUDDYX.siteLoader = function() {
        $('.site-loader').addClass('loaded');
    };

    // Keep --bx-adminbar-offset equal to the part of the admin bar still on screen. On narrow
    // screens WordPress makes the admin bar scroll away with the page, so a fixed header must
    // follow it up instead of jumping to the top and leaving a gap below it.
    BUDDYX.syncAdminBarOffset = function() {
        var adminBar = document.getElementById('wpadminbar'),
            offset = 0;

        if (adminBar) {
            offset = Math.max(0, Math.round(adminBar.getBoundingClientRect().bottom));
        }

        if (BUDDYX.adminBarOffset !== offset) {
            BUDDYX.adminBarOffset = offset;
            document.documentElement.style.setProperty('--bx-adminbar-offset', offset + 'px');
        }
    };

    // Header Height
    BUDDYX.headerClassUpdate = function() {
        var $elementHeader = $('body.sticky-header, .sticky-header .site-header-wrapper'),
            className = 'has-sticky-header',
            isSticky = $(document).scrollTop() >= 1;

        BUDDYX.syncAdminBarOffset();

        /*
         * Measure the header while it is still in the flow, then toggle the class and set the
         * .site padding in the same pass. Toggling first and measuring afterwards (as headerScroll
         * does) forces a layout while the fixed header is out of the flow and the padding is not
         * applied yet. The page is briefly shorter, so the browser clamps the scroll position and
         * the first scroll away from the top is swallowed on short pages or with a tall header.
         */
        if ($elementHeader.length && $('body').hasClass(className) !== isSticky) {
            $('.site').css('paddingTop', isSticky ? $('.site-header-wrapper').height() + 10 + 'px' : '0px');
        }

        $elementHeader.toggleClass(className, isSticky);
    };

    /*
     * Binds a passive scroll listener. jQuery cannot pass { passive: true }, so the real
     * scroll event is bound natively. The jQuery binding only serves scripts that fire
     * $(target).trigger('scroll') themselves, which has no originalEvent.
     */
    BUDDYX.onScroll = function(target, handler) {
        target.addEventListener('scroll', handler, { passive: true });

        $(target).on('scroll', function(event) {
            if (!event.originalEvent) {
                handler();
            }
        });
    };

    // Binds the scroll listener once; later calls (resize / load) only re-sync the header state.
    BUDDYX.headerClass = function() {
        var ticking = false;

        if (BUDDYX.headerClassBound) {
            BUDDYX.headerClassUpdate();
            return;
        }

        BUDDYX.headerClassBound = true;

        BUDDYX.onScroll(document, function() {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(function() {
                ticking = false;
                BUDDYX.headerClassUpdate();
            });
        });

        BUDDYX.headerClassUpdate();
    };

    // Header Scroll
    BUDDYX.headerScroll = function() {
        var header_height = $('.site-header-wrapper').height();

        if ($('body').hasClass('has-sticky-header')) {
            $('.site').css("paddingTop", header_height + 10 + "px");
        } else {
            $('.site').css("paddingTop", 0 + "px");
        }
    };

    // Header Search
    BUDDYX.headerSearch = function() {

        $('.search-icon').on('click', function(e) {
            e.preventDefault();
            $('.site-header .top-menu-search-container').toggle();
        });

        $(document).on('mouseup', function(e) {
            var container = $(".top-menu-search-container");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut();
            }
        });

        $("#primary-menu a, .cart a.menu-icons-wrapper, .bp-icon-wrap, a.user-link, .site-sub-header a, .site-wrapper a").on('focusin', function() {
            $('.site-header .top-menu-search-container').hide();
        });

    };

    // Desktop Menu Toggle
    BUDDYX.desktopMenuToggle = function() {
        // Submenu open/close (hover, click, keyboard) is handled by the
        // theme's own nav--toggle-sub system (see navigation.js and
        // _navigation.css) which already targets #primary-menu.

        $("#primary-menu a, .desktop-icons a, .bp-icon-wrap").on('focusin', function() {
            $('.user-link-wrap').removeClass("active");
        });

        $(".site-title a, .bp-msg .bp-icon-wrap, .user-link-wrap .user-link, button.menu-toggle").on('focusin', function() {
            $('.user-notifications').removeClass("active");
        });

        $(".user-link-wrap .user-link").on('focusin', function() {
            $(this).parent().removeClass("active");
            $(this).parent().addClass("active");
        });

        $(document).on('click', '.user-link-wrap .user-link', function(e) {
            var container = $(".user-link-wrap");
            container.removeClass('active');
        });

        $('.user-notifications .bp-icon-wrap').on('click', function(e) {
            e.preventDefault();
            $(this).parent().toggleClass('active');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.user-notifications').length) {
                $('.user-notifications').removeClass('active');
            }
        });

        $(".user-link-wrap ul#user-profile-menu > li:last-child a").on('focusout', function() {
            $('.user-link-wrap').removeClass("active");
        });
    };

    // Mobile Menu Toggle
    BUDDYX.mobileNav = function() {
        var widget = $('.menu-toggle'),
            body = $('body'),
            menuTriggerElement = null;

        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            } else {
                menuTriggerElement = this;
                setTimeout(function() {
                    openWidget();
                }, 10);
            }
        });

        body.on("click touchstart", ".mobile-menu-close", function() {
            if (isOpened()) {
                closeWidget();
            }
        });

        body.on("click", ".menu-close", function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            }
        });

        $(document).on('keyup', function(e) {
            if (e.key === BUDDYX.keys.ESCAPE && isOpened()) {
                closeWidget();
            }
        });

        // Focus trap for mobile menu - uses shared utility
        $(document).on('keydown', function(e) {
            if (!isOpened()) {
                return;
            }
            BUDDYX.trapFocus($('.buddyx-mobile-menu'), e);
        });

        var closeWidget = function() {
            $('body').removeClass('mobile-menu-opened');
            widget.attr('aria-expanded', 'false');
            // Return focus to trigger element
            if (menuTriggerElement) {
                $(menuTriggerElement).focus();
                menuTriggerElement = null;
            }
        };

        var openWidget = function() {
            $('body').addClass('mobile-menu-opened');
            widget.attr('aria-expanded', 'true');
            // Focus first focusable element in mobile menu
            setTimeout(function() {
                var $mobileMenu = $('.buddyx-mobile-menu');
                var $focusable = $mobileMenu.find('a, button, input, [tabindex]:not([tabindex="-1"])').filter(':visible');
                if ($focusable.length) {
                    $focusable.first().focus();
                }
            }, 100);
        };

        var isOpened = function() {
            return $('body').hasClass('mobile-menu-opened');
        };
    };

    // Blog Layout
    BUDDYX.blogLayout = function() {

        var $masonry = $('.buddyx-article--masonry:not(.without-masonry)');

        // Isotope is only loaded on pages that render the masonry layout.
        if (!$masonry.length || !$.fn.isotope) {
            return;
        }

        $masonry.isotope({
            itemSelector: '.buddyx-article-col',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: '.buddyx-grid-sizer',
            }
        });

    };

    // stickySidebar
    BUDDYX.stickySidebar = function() {
        var headerHeight = $('.site-header-wrapper').outerHeight();
        var offsetTop = 32;
    
        // Calculate the offset based on the presence of sticky-header and admin-bar classes
        if ($('body').hasClass('sticky-header') && $('body').hasClass('admin-bar')) {
            offsetTop = headerHeight + 62;
        } else if ($('body').hasClass('sticky-header')) {
            offsetTop = headerHeight + 32;
        } else {
            offsetTop = headerHeight;
        }
    
        // Check the window width and apply sticky sidebar accordingly
        if (window.innerWidth > 959) {
            $('.sticky-sidebar-enable .sticky-sidebar').stick_in_parent({
                offset_top: offsetTop,
                spacer: false // Remove the trailing comma here
            });
        } else {
            $('.sticky-sidebar-enable .sticky-sidebar').trigger('sticky_kit:detach');
        }
    
        // Recalculate sticky sidebar position after an Ajax call is completed
        if ($('.sticky-sidebar-enable .sticky-sidebar').length > 0) {
            $(document).on('ajaxComplete', function(event, request, settings) {
                setTimeout(function() {
                    $(document.body).trigger('sticky_kit:recalc');
                }, 150);
            });
        }
    };

    // tableDataAtt
    BUDDYX.tableDataAtt = function() {

        // Label each table's cells from that table's own header row.
        $('table').each(function() {
            var $table = $(this),
                $th = $table.children('thead').find('th');

            if (!$th.length) {
                return;
            }

            // children() keeps a nested table's cells out of this table's pass.
            $table.children('tbody').children('tr').children('td').attr('data-attr', function() {
                return $th.eq($(this).index()).text();
            });
        });
    };

    // Gallery Slider
    BUDDYX.galleryPostSlider = function() {

        // Slick is only loaded on pages that render a gallery block.
        if (!$.fn.slick) {
            return;
        }

        var obj_rtl = $('body').hasClass('rtl');
        var arrows = {
            nextArrow: '<button class="slick-next slick-arrow"><i class="fa fa-angle-right"></i></button>',
            prevArrow: '<button class="slick-prev slick-arrow"><i class="fa fa-angle-left"></i></button>'
        };

        $('.buddyx-gallery-block .gallery').each(function() {
            var $gallery = $(this),
                settings;

            // Already initialised (repeated call): slick throws if it is initialised twice.
            if ($gallery.hasClass('slick-initialized')) {
                return;
            }

            // One layout per gallery. When several apply, the first match wins: before 5.1.9 each
            // layout called .slick() in this order and slick ignores a second init on the same gallery.
            // A single post's .buddyx-post-section also carries .buddyx-section-half, so it gets 2 slides.
            if ($gallery.closest('.buddyx-article--default').length) {
                settings = {
                    slidesToShow: 4,
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                };
            } else if ($gallery.closest('.buddyx-post-section').length) {
                settings = {
                    slidesToShow: 2,
                    responsive: [{
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1
                        }
                    }]
                };
            } else if ($gallery.closest('.buddyx-article--list, .buddyx-article--masonry, .buddyx-section-half').length) {
                settings = {
                    slidesToShow: 1
                };
            } else {
                return;
            }

            $gallery.slick($.extend({
                infinite: false,
                slidesToScroll: 1,
                rtl: obj_rtl
            }, arrows, settings));
        });

    };

    // Dark mode: the TinyMCE iframe body is unreachable from CSS, so paint it from the theme tokens.
    // Bound at load, not on ready: editors initialise from an inline footer script and can finish first.
    BUDDYX.editorDarkMode = function() {
        var root = document.documentElement;

        function paint(editor) {
            var body = editor && editor.getBody && editor.getBody();
            if (!body) {
                return;
            }
            if (root.getAttribute('data-bx-mode') === 'dark') {
                var styles = window.getComputedStyle(root);
                body.style.backgroundColor = styles.getPropertyValue('--bx-color-bg-elevated').trim();
                body.style.color = styles.getPropertyValue('--bx-color-fg').trim();
            } else {
                body.style.backgroundColor = '';
                body.style.color = '';
            }
        }

        function paintAll() {
            if (window.tinymce && window.tinymce.editors) {
                window.tinymce.editors.forEach(paint);
            }
        }

        $(document).on('tinymce-editor-init', function(event, editor) {
            paint(editor);
        });
        document.addEventListener('bx:color-mode-change', paintAll);
        paintAll();
    };

    BUDDYX.editorDarkMode();

    $(function() {
        BUDDYX.headerClass();
        BUDDYX.headerSearch();
        BUDDYX.desktopMenuToggle();
        BUDDYX.mobileNav();
        BUDDYX.stickySidebar();
        BUDDYX.tableDataAtt();
        BUDDYX.galleryPostSlider();
    });

    // Scrolling only changes the .site padding when the sticky state flips, which
    // headerClass() handles. A resize can change the header height without a flip,
    // so re-measure it here.
    $(window).on('resize', function() {
        BUDDYX.headerClass();
        BUDDYX.headerScroll();
    });

    $(window).on('load', function() {
        BUDDYX.headerClass();
        BUDDYX.siteLoader();
        BUDDYX.blogLayout();
    });

})(jQuery, window, document);
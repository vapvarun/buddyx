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

    // Header Height
    BUDDYX.headerClass = function() {
        var $document = $(document),
            $elementHeader = $('body.sticky-header, .sticky-header .site-header-wrapper'),
            className = 'has-sticky-header';

        $document.on('scroll', function() {
            $elementHeader.toggleClass(className, $document.scrollTop() >= 1);
        });
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
        $('.buddyx-desktop-menu #primary-menu').superfish({
            delay: 600,
            animation: {
                opacity: 'show'
            },
            animationOut: {
                opacity: 'hide'
            },
            speed: 'fast',
            speedOut: 'fast',
            cssArrows: false,
            disableHI: false,
        });

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

        $(".buddyx-mobile-menu").on('focusout', function() {
            $('.mobile-menu-heading .close-menu').trigger('focusin');
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
            // Return focus to trigger element
            if (menuTriggerElement) {
                $(menuTriggerElement).focus();
                menuTriggerElement = null;
            }
        };

        var openWidget = function() {
            $('body').addClass('mobile-menu-opened');
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

        $('.buddyx-article--masonry:not(.without-masonry)').isotope({
            itemSelector: '.buddyx-article-col',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: '.buddyx-grid-sizer',
            }
        });

    };

    // fitVids
    BUDDYX.fitVids = function() {

        // LearnDash Player fix
        if ( $( '.ld-video iframe' ).length > 0 ) {
            $( '.ld-video iframe' ).addClass( 'fitvidsignore' );
        }

        // Tutor Player fix
        if ( $( '.tutor-video-player iframe' ).length > 0 ) {
            $( '.tutor-video-player iframe' ).addClass( 'fitvidsignore' );
        }

        var doFitVids = function() {
            setTimeout(
                function() {
                    var youtubeSelector = 'iframe[src*="youtube"]';
                    var vimeoSelector = '';
                    if (
                        ! $( '.tutor-course-details-page' ).length > 0 &&
                        ! $( '.tutor-course-single-content-wrapper' ).length > 0
                    ) {
                        vimeoSelector = 'iframe[src*="vimeo"]';
                    }
                    var dynamicSelector = youtubeSelector + ( vimeoSelector ? ',' + vimeoSelector : '' );
                    $( dynamicSelector ).parent().fitVids();
                },
                300
            );
        };
        doFitVids();
        // Unbind previous before binding new to prevent duplicate handlers
        $( document ).off('ajaxComplete.buddyxFitVids').on('ajaxComplete.buddyxFitVids', function() {
            if ( !$( '.elementor-popup-modal .elementor-widget-video' ).length ) {
                doFitVids();
            }
            $( '.elementor-video-container' ).addClass( 'fitvidsignore' );
        } );

        var doFitVidsOnLazyLoad = function( event, data ) {
            if ( typeof data !== 'undefined' && typeof data.element !== 'undefined' ) {
                // load iframe in correct dimension
                if ( data.element.getAttribute( 'data-lazy-type' ) == 'iframe' ) {
                    doFitVids();
                }
            }
        };
        $( document ).on( 'bp_nouveau_lazy_load', doFitVidsOnLazyLoad );

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

        if ($('table').length) {
            var $th = $("thead th");
            $('tbody tr td').attr('data-attr', function() {
                return $th.eq($(this).index()).text();
            });
        }
    };

    // Gallery Slider
    BUDDYX.galleryPostSlider = function() {

        $('.buddyx-gallery-block .gallery').each(function() {
            var obj_rtl;
            if ($('body').hasClass("rtl")) {
                obj_rtl = true;
            } else {
                obj_rtl = false;
            }

            $('.buddyx-article--default .buddyx-gallery-block .gallery').slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                nextArrow: '<button class="slick-next slick-arrow"><i class="fa fa-angle-right"></i></button>',
                prevArrow: '<button class="slick-prev slick-arrow"><i class="fa fa-angle-left"></i></button>',
                rtl: obj_rtl,
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
            });

            $('.buddyx-post-section .buddyx-gallery-block .gallery').slick({
                infinite: false,
                slidesToShow: 2,
                slidesToScroll: 1,
                nextArrow: '<button class="slick-next slick-arrow"><i class="fa fa-angle-right"></i></button>',
                prevArrow: '<button class="slick-prev slick-arrow"><i class="fa fa-angle-left"></i></button>',
                rtl: obj_rtl,
                responsive: [{
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });

            $('.buddyx-article--list .buddyx-gallery-block .gallery, .buddyx-article--masonry .buddyx-gallery-block .gallery, .buddyx-section-half .buddyx-gallery-block .gallery').slick({
                infinite: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                nextArrow: '<button class="slick-next slick-arrow"><i class="fa fa-angle-right"></i></button>',
                prevArrow: '<button class="slick-prev slick-arrow"><i class="fa fa-angle-left"></i></button>',
                rtl: obj_rtl
            });
        });

    };

    $(function() {
        BUDDYX.headerClass();
        BUDDYX.headerSearch();
        BUDDYX.desktopMenuToggle();
        BUDDYX.mobileNav();
        BUDDYX.stickySidebar();
        BUDDYX.fitVids();
        BUDDYX.tableDataAtt();
        BUDDYX.galleryPostSlider();
    });

    $(window).on('resize', function() {
        // do stuff
        BUDDYX.headerClass();
    });

    $(window).on('scroll', function() {
        // do stuff
        BUDDYX.headerScroll();
    });

    $(window).on('load', function() {
        BUDDYX.headerClass();
        BUDDYX.siteLoader();
        BUDDYX.blogLayout();
    });

})(jQuery, window, document);
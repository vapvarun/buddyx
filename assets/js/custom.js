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
            $('.user-link-wrap, .user-notifications').removeClass("active");
        });

        $(".site-title a, .user-link-wrap .user-link, button.menu-toggle").on('focusin', function() {
            $('.user-notifications').removeClass("active");
        });

        $(".user-link-wrap .user-link, .user-notifications .bp-icon-wrap").on('focusin', function() {
            $(this).parent().removeClass("active");
            $(this).parent().addClass("active");
        });

        $(document).on('click', '.user-link-wrap .user-link, .user-notifications .bp-icon-wrap', function(e) {
            var container = $(".user-link-wrap");
            container.removeClass('active');
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

            body = $('body');
        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            } else {
                setTimeout(function() {
                    openWidget();
                }, 10);
            }
        });

        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();

            } else {
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
            if (e.keyCode === 27 && isOpened())
                closeWidget();
        });

        var closeWidget = function() {
            $('body').removeClass('mobile-menu-opened');
            $(widget).removeClass('menu-toggle-open');
        };

        var openWidget = function() {
            $('body').addClass('mobile-menu-opened');
            $(widget).addClass('menu-toggle-open');
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

        var doFitVids = function () {
            setTimeout(
                function () {
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
        $( document ).ajaxComplete( function () {
            if ( !$( '.elementor-popup-modal .elementor-widget-video' ).length ) {
                doFitVids();
            }
            $( '.elementor-video-container' ).addClass( 'fitvidsignore' );
        } );

        var doFitVidsOnLazyLoad = function ( event, data ) {
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

    // roundAvatarsBodyclass
    BUDDYX.roundAvatarsBodyclass = function() {

        if ($('.buddypress-wrap').hasClass('round-avatars')) {
            $('body').addClass('round-avatars');
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

    // Toggle Theme
    BUDDYX.toggleTheme = function() {

        $(document).on('click', '#buddyx-toggle-track', function(e) {
            e.preventDefault();
            var color = '';
            if (!$('body').hasClass('buddyx-dark-theme')) {
                $.cookie('bxtheme', 'dark', { path: '/' });
                $('body').addClass('buddyx-dark-theme');
                color = 'dark';
            } else {
                $.removeCookie('bxtheme', { path: '/' });
                $('body').removeClass('buddyx-dark-theme');
            }

            if (typeof(toggle_theme_ajax) != 'undefined' && toggle_theme_ajax != null) {
                toggle_theme_ajax.abort();
            }

            var data = {
                'action': 'buddyboss_lms_toggle_theme_color',
                'color': color
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            if (typeof(toggle_theme_ajax) != 'undefined' && toggle_theme_ajax != null) {
                toggle_theme_ajax = $.post(ajaxurl, data, function(response) {});
            }
        });

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

    // MediaPress
    BUDDYX.mediaPress = function() {

        /**
         * Activity upload Form handling
         * Prepend the upload buttons to Activity form
         */
        $('.activity-update-form #whats-new-form').append($('#mpp-activity-upload-buttons'));

    };

    $(function() {

        BUDDYX.headerClass();
        BUDDYX.headerSearch();
        BUDDYX.desktopMenuToggle();
        BUDDYX.mobileNav();
        BUDDYX.stickySidebar();
        BUDDYX.fitVids();
        BUDDYX.roundAvatarsBodyclass();
        BUDDYX.tableDataAtt();
        BUDDYX.toggleTheme();
        BUDDYX.galleryPostSlider();
        BUDDYX.mediaPress();

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
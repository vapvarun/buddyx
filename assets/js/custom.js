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
    BUDDYX.headerHeight = function() {
        var header_height = $('.site-header-wrapper').height();
        $('.site').css("paddingTop", header_height + 12 + "px");
        $('.page-template-full-width:not(.elementor-editor-active) .site').css("paddingTop", header_height - 10 + "px");
        $('#cover-image-container').css("marginTop", -header_height + 46 + "px");

        var $document = $(document),
            $elementHeader = $('.site-header-wrapper'),
            className = 'has-sticky-header';

        $document.scroll(function() {
            $elementHeader.toggleClass(className, $document.scrollTop() >= 1);
        });
    };

    // Header Search
    BUDDYX.headerSearch = function() {
        $('.search-icon').on('click', function(e) {
            e.stopPropagation();
            $('.site-header .top-menu-search-container').toggle();
        });

        $(document).mouseup(function(e) {
            var container = $(".top-menu-search-container");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut();
            }
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

        $("#primary-menu a, .desktop-icons a, .bp-icon-wrap").focusin(function() {
            $('.user-link-wrap').removeClass("active");
        });

        $(".user-link-wrap .user-link").focusin(function() {
            $(this).parent().removeClass("active");
            $(this).parent().addClass("active");
        });

        $(document).on('click', '.user-link-wrap .user-link', function(e) {
            var container = $(".user-link-wrap");
            container.removeClass('active');
        });

        $(".user-link-wrap ul#user-profile-menu > li:last-child a").focusout(function() {
            $('.user-link-wrap').removeClass("active");
        });

        $(".buddyx-mobile-menu").focusout(function() {
            $('.mobile-menu-heading .close-menu').focusin();
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

        $(document).keyup(function(e) {
            if (e.keyCode === 27 && isOpened())
                closeWidget();
        });

        var closeWidget = function() {
            $('body').removeClass('mobile-menu-opened');
        };

        var openWidget = function() {
            $('body').addClass('mobile-menu-opened');
        };

        var isOpened = function() {
            return $('body').hasClass('mobile-menu-opened');
        };
    };

    // Blog Layout
    BUDDYX.blogLayout = function() {

        $('.grid-layout').isotope({
            // options
            itemSelector: '.entry-layout',
            layoutMode: 'fitRows'
        });

        $('.masonry-layout').isotope({
            itemSelector: '.entry-layout',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: '.grid-sizer',
            }
        })
    };

    // fitVids
    BUDDYX.fitVids = function() {

        var doFitVids = function() {
            setTimeout(function() {
                $('iframe[src*="youtube"], iframe[src*="vimeo"]').parent().fitVids();
            }, 300);
        };
        doFitVids();
        $(document).ajaxComplete(doFitVids);

        var doFitVidsOnLazyLoad = function(event, data) {
            if (typeof data !== 'undefined' && typeof data.element !== 'undefined') {
                //load iframe in correct dimension
                if (data.element.getAttribute('data-lazy-type') == 'iframe') {
                    doFitVids();
                }
            }
        };
        $(document).on('bp_nouveau_lazy_load', doFitVidsOnLazyLoad);

    };

    // stickySidebar
    BUDDYX.stickySidebar = function() {

        var headerHeight = $('.site-header-wrapper').height();
        var headerHeightExt = headerHeight + 54;
        $('.sticky-sidebar-enable .sticky-sidebar').stick_in_parent({
            offset_top: headerHeightExt,
            recalc_every: 1,
        });

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

    $(document).ready(function() {

        BUDDYX.headerHeight();
        BUDDYX.headerSearch();
        BUDDYX.desktopMenuToggle();
        BUDDYX.mobileNav();
        BUDDYX.fitVids();
        BUDDYX.roundAvatarsBodyclass();
        BUDDYX.tableDataAtt();

    });

    $(window).resize(function() {
        // do stuff
        BUDDYX.headerHeight();
    });

    $(window).scroll(function() {
        // do stuff
    });

    $(window).load(function() {
        BUDDYX.siteLoader();
        BUDDYX.stickySidebar();
        BUDDYX.blogLayout();
    });

})(jQuery, window, document);
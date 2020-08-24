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

    // Mobile Sub Menu Toggle
    BUDDYX.mobileMenuToggle = function() {

        $('.buddyx-mobile-menu #primary-menu .sub-menu').hide();

        $('.buddyx-mobile-menu #primary-menu .menu-item-has-children').each(function() {
            $(this).prepend('<i class="fa submenu-btn fa-angle-down"></i>');
        });

        $('body').on('click', '.buddyx-mobile-menu #primary-menu .submenu-btn', function(e) {
            e.preventDefault();
            $("body").addClass('menu-active');
            $(this).toggleClass('fa-angle-up').parent().children('.sub-menu').slideToggle();
        });

        $('.buddyx-mobile-menu #primary-menu li:has(ul)').doubleTapToGo();

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
        BUDDYX.mobileNav();
        BUDDYX.mobileMenuToggle();
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
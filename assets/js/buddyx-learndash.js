/**
 * File buddypress.js.
 *
 * Theme Custom enhancements for a better user experience.
 *
 */

// The semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;(function ($, window, document) {
    'use strict';

    var BUDDYX = window.BUDDYX || {};

    // Toggle Theme
    BUDDYX.toggleTheme = function () {
        $(document).on('click', '#buddyx-toggle-track', function (e) {
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

            if (typeof toggle_theme_ajax !== 'undefined' && toggle_theme_ajax !== null) {
                toggle_theme_ajax.abort();
            }

            var data = {
                action: 'buddyx_lms_toggle_theme_color',
                color: color,
                nonce: buddyx_ajax.nonce
            };

            // Since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            if (typeof toggle_theme_ajax !== 'undefined' && toggle_theme_ajax !== null) {
                toggle_theme_ajax = $.post(ajaxurl, data, function (response) {
                    // Handle response if needed
                });
            }
        });
    };

    // Initialize all functions
    $(document).ready(function () {
        BUDDYX.toggleTheme();
    });

    $(window).on('resize', function () {
        // Do stuff on resize
    });

    $(window).on('scroll', function () {
        // Do stuff on scroll
    });

    $(window).on('load', function () {
        // Do stuff on load
    });

})(jQuery, window, document);
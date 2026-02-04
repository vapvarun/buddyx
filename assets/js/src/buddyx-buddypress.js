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

    // Round avatars body class
    BUDDYX.roundAvatarsBodyclass = function () {
        if ($('.buddypress-wrap').hasClass('round-avatars')) {
            $('body').addClass('round-avatars');
        }
    };

    // MediaPress
    BUDDYX.mediaPress = function () {
        /**
         * Activity upload Form handling
         * Prepend the upload buttons to Activity form
         */
        $('.activity-update-form #whats-new-form').append($('#mpp-activity-upload-buttons'));
    };

    // Initialize all functions
    $(document).ready(function () {
        BUDDYX.roundAvatarsBodyclass();
        BUDDYX.mediaPress();
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
<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

//
// custom style
// -------------------------------------------------------------
function buddyx_get_customizer_style() {

	// Site Loader Background
    $site_loader_bg = get_theme_mod( 'site_loader_bg' );
    if ( $site_loader_bg ) {

		echo '.site-loader {';
		echo sprintf( 'background-color: %s;', esc_attr( $site_loader_bg ) );
		echo '}';

	}

    // Site Title Hover Color
    $site_title_hover_color = get_theme_mod( 'site_title_hover_color' );
    if ( $site_title_hover_color ) {

		echo '.site-title a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_title_hover_color ) );
		echo '}';

	}

    // Menu Hover Color
    $menu_hover_color = get_theme_mod( 'menu_hover_color' );
    if ( $menu_hover_color ) {

        // color
		echo '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover {';
		echo sprintf( 'color: %s;', esc_attr( $menu_hover_color ) );
		echo '}';

        // border color
        echo '.nav--toggle-small .menu-toggle:hover {';
        echo sprintf( 'border-color: %s;', esc_attr( $menu_hover_color ) );
        echo '}';

	}

    // Menu Active Color
    $menu_active_color = get_theme_mod( 'menu_active_color' );
    if ( $menu_active_color ) {

		echo '.main-navigation ul li.current-menu-item>a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_color ) );
		echo '}';

	}

    // Header Background Color
    $site_header_bg_color = get_theme_mod( 'site_header_bg_color' );
    if ( $site_header_bg_color ) {

        // color
		echo '.site-header-wrapper, .layout-boxed .site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container, .main-navigation #user-profile-menu, .main-navigation .bp-header-submenu {';
		echo sprintf( 'background-color: %s;', esc_attr( $site_header_bg_color ) );
		echo '}';

        // border color
        echo '.site-header-wrapper {';
        echo sprintf( 'border-color: %s;', esc_attr( $site_header_bg_color ) );
        echo '}';

        // border top color
        echo '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before {';
        echo sprintf( 'border-top-color: %s;', esc_attr( $site_header_bg_color ) );
        echo '}';

        // border right color
        echo '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before {';
        echo sprintf( 'border-right-color: %s;', esc_attr( $site_header_bg_color ) );
        echo '}';

	}

    // Body Background Color
    $body_background_color = get_theme_mod( 'body_background_color' );
    if ( $body_background_color ) {

		echo 'body, body.layout-boxed {';
		echo sprintf( 'background-color: %s;', esc_attr( $body_background_color ) );
		echo '}';

	}
    
    // Content Background Color
    $content_background_color = get_theme_mod( 'content_background_color' );
    if ( $content_background_color ) {

		echo 'body.layout-boxed .site {';
		echo sprintf( 'background-color: %s;', esc_attr( $content_background_color ) );
		echo '}';

	}

    // Theme Color
    $site_primary_color = get_theme_mod( 'site_primary_color' );
    if ( $site_primary_color ) {

        // color
		echo '.post-meta-category.post-meta-category a, .buddyx-breadcrumbs a, #breadcrumbs a, .pagination .current, .buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:focus, nav#object-nav.vertical .selected>a, .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.selected a, .buddypress-wrap .bp-navs li.selected a:focus, .buddypress-wrap .bp-navs li.current a:focus,
        .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a, .media .rtm-tabs li.active a, .buddypress.widget .item-options a.selected,
        
        .learndash-wrapper .ld-expand-button.ld-button-alternate,
        .learndash-wrapper .ld-item-list .ld-item-list-item a.ld-item-name:hover,
        .learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:hover,
        .learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:active,
        .learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:focus,
        .learndash-wrapper .ld-table-list a.ld-table-list-item-preview:hover,
        .learndash-wrapper .ld-table-list a.ld-table-list-item-preview:active,
        .learndash-wrapper .ld-table-list a.ld-table-list-item-preview:focus,
        .learndash-wrapper .ld-expand-button.ld-button-alternate:hover,
        .learndash-wrapper .ld-expand-button.ld-button-alternate:active,
        .learndash-wrapper .ld-expand-button.ld-button-alternate:focus,
        .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:hover, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:active, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:focus, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:hover, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:active, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:focus,
        
        .learndash-wrapper .ld-status-unlocked,
        #learndash_lesson_topics_list span a, #learndash_lessons a, #learndash_profile a, #learndash_profile a span, #learndash_quizzes a, .expand_collapse a, .learndash_topic_dots a, .learndash_topic_dots a>span,
        #learndash_lessons h4>a, #learndash_quizzes h4>a, #learndash_lesson_topics_list ul>li>span a, #learndash_course_content .learndash_topic_dots ul>li a, #learndash_profile .learndash-course-link a, #learndash_profile .quiz_title a, #learndash_profile .profile_edit_profile a,
        .learndash-wrapper .ld-course-navigation .ld-lesson-item.ld-is-current-lesson .ld-lesson-item-preview-heading, .learndash-wrapper .ld-course-navigation .ld-lesson-item.ld-is-current-lesson .ld-lesson-title,
        .learndash-wrapper .ld-course-navigation .ld-lesson-item-preview a.ld-lesson-item-preview-heading:hover,
        .learndash-wrapper .ld-button.ld-button-transparent,
        .learndash-wrapper .ld-focus .ld-focus-header .ld-button:hover,
        .learndash-wrapper .ld-breadcrumbs a:hover, .learndash-wrapper .ld-breadcrumbs a:active, .learndash-wrapper .ld-breadcrumbs a:focus,
        .learndash-wrapper .ld-content-actions>a:hover, .learndash-wrapper .ld-content-actions>a:active, .learndash-wrapper .ld-content-actions>a:focus,
        .learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active,
        .learndash-wrapper .ld-profile-summary .ld-profile-card .ld-profile-edit-link:hover,
        .learndash-wrapper .ld-item-list .ld-section-heading .ld-search-prompt:hover,
        #ld-profile .ld-item-list .ld-item-list-item a.ld-item-name:hover,
        .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:hover, .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:active, .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:focus, .learndash-wrapper .ld-home-link:hover, .learndash-wrapper .ld-home-link:active, .learndash-wrapper .ld-home-link:focus,
        
        #learndash_lessons h4>a:hover, #learndash_lessons h4>a:active, #learndash_lessons h4>a:focus, #learndash_quizzes h4>a:hover, #learndash_quizzes h4>a:active, #learndash_quizzes h4>a:focus, #learndash_lesson_topics_list ul>li>span a:hover, #learndash_lesson_topics_list ul>li>span a:active, #learndash_lesson_topics_list ul>li>span a:focus, #learndash_course_content .learndash_topic_dots ul>li a:hover, #learndash_course_content .learndash_topic_dots ul>li a:active, #learndash_course_content .learndash_topic_dots ul>li a:focus, #learndash_profile .learndash-course-link a:hover, #learndash_profile .learndash-course-link a:active, #learndash_profile .learndash-course-link a:focus, #learndash_profile .quiz_title a:hover, #learndash_profile .quiz_title a:active, #learndash_profile .quiz_title a:focus, #learndash_profile .profile_edit_profile a:hover, #learndash_profile .profile_edit_profile a:active, #learndash_profile .profile_edit_profile a:focus,
        ul.learn-press-courses .course .course-info .course-price .price, .widget .course-meta-field, .lp-single-course .course-price .price,
        
        .llms-student-dashboard .llms-sd-item.current>a, .llms-loop-item-content .llms-loop-title:hover, .llms-pagination ul li .page-numbers.current,
        
        .tribe-common--breakpoint-medium.tribe-events-pro .tribe-events-pro-map__event-datetime-featured-text, .tribe-common--breakpoint-medium.tribe-events .tribe-events-calendar-list__event-datetime-featured-text, .tribe-common .tribe-common-c-svgicon, .tribe-common .tribe-common-cta--thin-alt:active, .tribe-common .tribe-common-cta--thin-alt:focus, .tribe-common .tribe-common-cta--thin-alt:hover, .tribe-common a:active, .tribe-common a:focus, .tribe-common a:hover, .tribe-events-cal-links .tribe-events-gcal, .tribe-events-cal-links .tribe-events-ical, .tribe-events-event-meta a, .tribe-events-event-meta a:visited, .tribe-events-pro .tribe-events-pro-organizer__meta-email-link, .tribe-events-pro .tribe-events-pro-organizer__meta-website-link, .tribe-events-pro .tribe-events-pro-photo__event-datetime-featured-text, .tribe-events-schedule .recurringinfo a, .tribe-events-single ul.tribe-related-events li .tribe-related-events-title a, .tribe-events-widget.tribe-events-widget .tribe-events-widget-events-list__view-more-link, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:active, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:focus, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:hover, .tribe-events .tribe-events-c-ical__link, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link, .tribe-related-event-info .recurringinfo a, .tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum, .tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link {';
		echo sprintf( 'color: %s;', esc_attr( $site_primary_color ) );
		echo '}';

        // background color
        echo '.buddypress-icons-wrapper .bp-msg sup, .buddypress-icons-wrapper .user-notifications sup, .menu-icons-wrapper .cart sup, .buddypress-wrap .bp-navs li.current a .count, .buddypress-wrap .bp-navs li.dynamic.current a .count, .buddypress-wrap .bp-navs li.selected a .count, .buddypress_object_nav .bp-navs li.current a .count, .buddypress_object_nav .bp-navs li.selected a .count, .buddypress-wrap .bp-navs li.dynamic.selected a .count, .buddypress_object_nav .bp-navs li.dynamic a .count, .buddypress_object_nav .bp-navs li.dynamic.current a .count, .buddypress_object_nav .bp-navs li.dynamic.selected a .count, .bp-navs ul li .count, .buddypress-wrap .bp-navs li.dynamic a .count, .bp-single-vert-nav .bp-navs.vertical li span, .buddypress-wrap .bp-navs li.dynamic a:hover .count, .buddypress_object_nav .bp-navs li.dynamic a:hover .count, .buddypress-wrap .rtm-bp-navs ul li.selected a:hover>span, .buddypress-wrap .rtm-bp-navs ul li.selected a>span, .users-header .bp-member-type,
        .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a:after, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link a:hover:after, .entry .post-categories a, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
        .llms-progress .progress-bar-complete, body .llms-syllabus-wrapper .llms-section-title,
        
        .tribe-events .tribe-events-calendar-list__event-row--featured .tribe-events-calendar-list__event-date-tag-datetime:after {';
        echo sprintf( 'background-color: %s;', esc_attr( $site_primary_color ) );
        echo '}';

        // background
        echo '.tribe-events .datepicker .day.active, .tribe-events .datepicker .day.active.focused, .tribe-events .datepicker .day.active:focus, .tribe-events .datepicker .day.active:hover, .tribe-events .datepicker .month.active, .tribe-events .datepicker .month.active.focused, .tribe-events .datepicker .month.active:focus, .tribe-events .datepicker .month.active:hover, .tribe-events .datepicker .year.active, .tribe-events .datepicker .year.active.focused, .tribe-events .datepicker .year.active:focus, .tribe-events .datepicker .year.active:hover, .widget .tribe-events .tribe-events-calendar-month__day-cell--selected, .widget .tribe-events .tribe-events-calendar-month__day-cell--selected:focus, .widget .tribe-events .tribe-events-calendar-month__day-cell--selected:hover, .tribe-events .tribe-events-c-ical__link:active, .tribe-events .tribe-events-c-ical__link:focus, .tribe-events .tribe-events-c-ical__link:hover, .widget .tribe-events-widget .tribe-events-widget-events-list__event-row--featured .tribe-events-widget-events-list__event-date-tag-datetime:after, .tribe-events-pro.tribe-events-view--week .datepicker .day.current:before {';
        echo sprintf( 'background: %s;', esc_attr( $site_primary_color ) );
        echo '}';

        // border color
        echo '.buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a,
            
        .llms-student-dashboard .llms-sd-item.current>a, .llms-student-dashboard .llms-sd-item>a:hover,
        
        .tribe-common .tribe-common-cta--thin-alt, .tribe-common .tribe-common-cta--thin-alt:active, .tribe-common .tribe-common-cta--thin-alt:focus, .tribe-common .tribe-common-cta--thin-alt:hover, .tribe-events-pro .tribe-events-pro-map__event-card-wrapper--active .tribe-events-pro-map__event-card-button, .tribe-events-pro .tribe-events-pro-week-day-selector__day--active, .tribe-events .tribe-events-c-ical__link {';
        echo sprintf( 'border-color: %s;', esc_attr( $site_primary_color ) );
        echo '}';
        
        // border bottom color
        echo '.tribe-common .tribe-common-anchor-thin:active, .tribe-common .tribe-common-anchor-thin:focus, .tribe-common .tribe-common-anchor-thin:hover, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:active, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:focus, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:hover {';
        echo sprintf( 'border-bottom-color: %s;', esc_attr( $site_primary_color ) );
        echo '}';

	}
    
    // Link Color
    $site_links_color = get_theme_mod( 'site_links_color' );
    if ( $site_links_color ) {

		echo 'a {';
		echo sprintf( 'color: %s;', esc_attr( $site_links_color ) );
		echo '}';

	}

    // Link Hover
    $site_links_focus_hover_color = get_theme_mod( 'site_links_focus_hover_color' );
    if ( $site_links_focus_hover_color ) {

		echo 'a:hover, a:active, a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:hover, .rtmedia-actions-before-comments .rtmedia-comment-link:hover, .rtmedia-actions-before-comments .rtmedia-view-conversation:hover, #buddypress .rtmedia-actions-before-comments .rtmedia-like:hover, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:hover, nav#object-nav.vertical a:hover,
        .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_links_focus_hover_color ) );
		echo '}';

	}

    // Button Background Color
    $site_buttons_background_color = get_theme_mod( 'site_buttons_background_color' );
    if ( $site_buttons_background_color ) {

		echo '.buddyx-mobile-menu .dropdown-toggle, a.read-more.button, input[type="button"], input[type="reset"], input[type="submit"],
        #buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .buddypress .buddypress-wrap button.ges-change, .group-email-tooltip__close, .buddypress .buddypress-wrap button.group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review, button.friendship-button, button.group-button,
        .woocommerce-product-search button[type=submit], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
                            
        .ld-course-list-items .ld_course_grid .btn-primary,
        .learndash-wrapper .ld-expand-button,
        .learndash-wrapper .ld-expand-button.ld-button-alternate .ld-icon,
        .learndash-wrapper .ld-table-list .ld-table-list-header,
        .learndash-wrapper .ld-focus .ld-focus-sidebar .ld-course-navigation-heading,
        .learndash-wrapper .ld-focus .ld-focus-sidebar .ld-focus-sidebar-trigger,
        .learndash-wrapper .ld-button,
        .learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu .ld-user-menu-items a,
        .learndash-wrapper .ld-button, .learndash-wrapper .ld-content-actions .ld-button, .learndash-wrapper .ld-expand-button, .learndash-wrapper .ld-alert .ld-button,
        .learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active:after,
        .learndash-wrapper .btn-join, .learndash-wrapper #btn-join, .learndash-wrapper .learndash_mark_complete_button, .learndash-wrapper #learndash_mark_complete_button, .ld-course-status-action .ld-button, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary, .ldx-plugin .uo-toolkit-grid__course-action input, .learndash-resume-button input[type=submit], .learndash-reset-form .learndash-reset-button[type=submit], .learndash-wrapper .ld-login-modal input[type=submit], .learndash-wrapper .ld-login-button, .learndash-course-widget-wrap .ld-course-status-action a,
        
        .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
        #wp-idea-stream a.button, #wp-idea-stream button:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:not(.ed_button), #wp-idea-stream input[type=reset], #wp-idea-stream input[type=submit]:not(.search-submit), a.wpis-title-button, body.single-ideas #comments .comment-reply-link,
        .tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn {';
		echo sprintf( 'background: %s;', esc_attr( $site_buttons_background_color ) );
		echo '}';

	}

    // Button Background Hover Color
    $site_buttons_background_hover_color = get_theme_mod( 'site_buttons_background_hover_color' );
    if ( $site_buttons_background_hover_color ) {

		echo '.buddyx-mobile-menu .dropdown-toggle:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
        #buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .buddypress .buddypress-wrap button.ges-change:hover, .group-email-tooltip__close:hover, .buddypress .buddypress-wrap button.group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover, button.friendship-button:hover, button.group-button:hover,
        .woocommerce-product-search button[type=submit]:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
                            
        .ld-course-list-items .ld_course_grid .btn-primary:hover,
        .learndash-wrapper .ld-expand-button:hover,
        .learndash-wrapper .ld-button:hover,
        .learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu .ld-user-menu-items a:hover,
        .learndash-wrapper .ld-button:hover, .learndash-wrapper .ld-button:active, .learndash-wrapper .ld-button:focus, .learndash-wrapper .ld-content-actions .ld-button:hover, .learndash-wrapper .ld-content-actions .ld-button:active, .learndash-wrapper .ld-content-actions .ld-button:focus, .learndash-wrapper .ld-expand-button:hover, .learndash-wrapper .ld-expand-button:active, .learndash-wrapper .ld-expand-button:focus, .learndash-wrapper .ld-alert .ld-button:hover, .learndash-wrapper .ld-alert .ld-button:active, .learndash-wrapper .ld-alert .ld-button:focus,
        .learndash-wrapper .btn-join:hover, .learndash-wrapper .btn-join:active, .learndash-wrapper .btn-join:focus, .learndash-wrapper #btn-join:hover, .learndash-wrapper #btn-join:active, .learndash-wrapper #btn-join:focus, .learndash-wrapper .learndash_mark_complete_button:hover, .learndash-wrapper .learndash_mark_complete_button:active, .learndash-wrapper .learndash_mark_complete_button:focus, .learndash-wrapper #learndash_mark_complete_button:hover, .learndash-wrapper #learndash_mark_complete_button:active, .learndash-wrapper #learndash_mark_complete_button:focus, .ld-course-status-action .ld-button:hover, .ld-course-status-action .ld-button:active, .ld-course-status-action .ld-button:focus, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:hover, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:active, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:focus, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:hover, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:active, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:focus, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:hover, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:active, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:focus, .ldx-plugin .uo-toolkit-grid__course-action input:hover, .ldx-plugin .uo-toolkit-grid__course-action input:active, .ldx-plugin .uo-toolkit-grid__course-action input:focus, .learndash-resume-button input[type=submit]:hover, .learndash-resume-button input[type=submit]:active, .learndash-resume-button input[type=submit]:focus, .learndash-reset-form .learndash-reset-button[type=submit]:hover, .learndash-reset-form .learndash-reset-button[type=submit]:active, .learndash-reset-form .learndash-reset-button[type=submit]:focus, .learndash-wrapper .ld-login-modal input[type=submit]:hover, .learndash-wrapper .ld-login-modal input[type=submit]:active, .learndash-wrapper .ld-login-modal input[type=submit]:focus, .learndash-wrapper .ld-login-button:hover, .learndash-wrapper .ld-login-button:active, .learndash-wrapper .ld-login-button:focus, .learndash-course-widget-wrap .ld-course-status-action a:hover,
        
        .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
        #wp-idea-stream a.button:focus, #wp-idea-stream a.button:hover, #wp-idea-stream button:hover:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:hover:not(.ed_button), #wp-idea-stream input[type=reset]:hover, #wp-idea-stream input[type=submit]:hover:not(.search-submit), a.wpis-title-button:focus, a.wpis-title-button:hover, body.single-ideas #comments .comment-reply-link:hover,
        .tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover {';
		echo sprintf( 'background: %s;', esc_attr( $site_buttons_background_hover_color ) );
		echo '}';

	}

    // Button Text Color
    $site_buttons_text_color = get_theme_mod( 'site_buttons_text_color' );
    if ( $site_buttons_text_color ) {

		echo '.buddyx-mobile-menu .dropdown-toggle, a.read-more.button, input[type="button"], input[type="reset"], input[type="submit"],
        #buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .buddypress .buddypress-wrap button.ges-change, .group-email-tooltip__close, .buddypress .buddypress-wrap button.group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review, button.friendship-button, button.group-button,
        .woocommerce-product-search button[type=submit], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
        .learndash-course-widget-wrap .ld-course-status-action a,
        
        .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
        #wp-idea-stream a.button, #wp-idea-stream button:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:not(.ed_button), #wp-idea-stream input[type=reset], #wp-idea-stream input[type=submit]:not(.search-submit), a.wpis-title-button, body.single-ideas #comments .comment-reply-link {';
		echo sprintf( 'color: %s;', esc_attr( $site_buttons_text_color ) );
		echo '}';

	}

    // Button Text Hover Color
    $site_buttons_text_hover_color = get_theme_mod( 'site_buttons_text_hover_color' );
    if ( $site_buttons_text_hover_color ) {

		echo '.buddyx-mobile-menu .dropdown-toggle:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
        #buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .buddypress .buddypress-wrap button.ges-change:hover, .group-email-tooltip__close:hover, .buddypress .buddypress-wrap button.group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover, button.friendship-button:hover, button.group-button:hover,
        .woocommerce-product-search button[type=submit]:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
        .learndash-course-widget-wrap .ld-course-status-action a:hover,
        
        .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
        #wp-idea-stream a.button:focus, #wp-idea-stream a.button:hover, #wp-idea-stream button:hover:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:hover:not(.ed_button), #wp-idea-stream input[type=reset]:hover, #wp-idea-stream input[type=submit]:hover:not(.search-submit), a.wpis-title-button:focus, a.wpis-title-button:hover, body.single-ideas #comments .comment-reply-link:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_buttons_text_hover_color ) );
		echo '}';

	}

    // Button Border Color
    $site_buttons_border_color = get_theme_mod( 'site_buttons_border_color' );
    if ( $site_buttons_border_color ) {

		echo '.buddyx-mobile-menu .dropdown-toggle, a.read-more.button, input[type="button"], input[type="reset"], input[type="submit"],
        #buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .buddypress .buddypress-wrap button.ges-change, .group-email-tooltip__close, .buddypress .buddypress-wrap button.group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review, button.friendship-button, button.group-button,
        .woocommerce-product-search button[type=submit], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
        
        .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
        #wp-idea-stream a.button, #wp-idea-stream button:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:not(.ed_button), #wp-idea-stream input[type=reset], #wp-idea-stream input[type=submit]:not(.search-submit), a.wpis-title-button, body.single-ideas #comments .comment-reply-link,
        .tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn {';
		echo sprintf( 'border-color: %s;', esc_attr( $site_buttons_border_color ) );
		echo '}';

	}

    // Button Border Hover Color
    $site_buttons_border_hover_color = get_theme_mod( 'site_buttons_border_hover_color' );
    if ( $site_buttons_border_hover_color ) {

		echo '.buddyx-mobile-menu .dropdown-toggle:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
        #buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .buddypress .buddypress-wrap button.ges-change:hover, .group-email-tooltip__close:hover, .buddypress .buddypress-wrap button.group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover, button.friendship-button:hover, button.group-button:hover,
        .woocommerce-product-search button[type=submit]:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
        
        .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
        #wp-idea-stream a.button:focus, #wp-idea-stream a.button:hover, #wp-idea-stream button:hover:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:hover:not(.ed_button), #wp-idea-stream input[type=reset]:hover, #wp-idea-stream input[type=submit]:hover:not(.search-submit), a.wpis-title-button:focus, a.wpis-title-button:hover, body.single-ideas #comments .comment-reply-link:hover,
        .tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover {';
		echo sprintf( 'border-color: %s;', esc_attr( $site_buttons_border_hover_color ) );
		echo '}';

	}

    // Image Overlay Color
    $buddyx_section_title_over_overlay = get_theme_mod( 'buddyx_section_title_over_overlay' );
    if ( $buddyx_section_title_over_overlay ) {

		echo '.buddyx-section-title-over.has-featured-image.has-featured-image .post-thumbnail:after {';
		echo sprintf( 'background: %s;', esc_attr( $buddyx_section_title_over_overlay ) );
		echo '}';

	}

    // Footer Title Color
    $site_footer_title_color = get_theme_mod( 'site_footer_title_color' );
    if ( $site_footer_title_color ) {

		echo '.site-footer .widget-title {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_title_color ) );
		echo '}';

	}

    // Footer Content Color
    $site_footer_content_color = get_theme_mod( 'site_footer_content_color' );
    if ( $site_footer_content_color ) {

		echo '.site-footer {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_content_color ) );
		echo '}';

	}

    // Footer Link Color
    $site_footer_links_color = get_theme_mod( 'site_footer_links_color' );
    if ( $site_footer_links_color ) {

		echo '.site-footer a {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_links_color ) );
		echo '}';

	}

    // Footer Link Hover
    $site_footer_links_hover_color = get_theme_mod( 'site_footer_links_hover_color' );
    if ( $site_footer_links_hover_color ) {

		echo '.site-footer a:hover, .site-footer a:active {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_links_hover_color ) );
		echo '}';

	}

    // Copyright Background Color
    $site_copyright_background_color = get_theme_mod( 'site_copyright_background_color' );
    if ( $site_copyright_background_color ) {

		echo '.site-info {';
		echo sprintf( 'background-color: %s;', esc_attr( $site_copyright_background_color ) );
		echo '}';

	}

    // Copyright Border Color
    $site_copyright_border_color = get_theme_mod( 'site_copyright_border_color' );
    if ( $site_copyright_border_color ) {

		echo '.site-info {';
		echo sprintf( 'border-color: %s;', esc_attr( $site_copyright_border_color ) );
		echo '}';

	}

    // Copyright Content Color
    $site_copyright_content_color = get_theme_mod( 'site_copyright_content_color' );
    if ( $site_copyright_content_color ) {

		echo '.site-info {';
		echo sprintf( 'color: %s;', esc_attr( $site_copyright_content_color ) );
		echo '}';

	}

    // Copyright Link Color
    $site_copyright_links_color = get_theme_mod( 'site_copyright_links_color' );
    if ( $site_copyright_links_color ) {

		echo '.site-info a {';
		echo sprintf( 'color: %s;', esc_attr( $site_copyright_links_color ) );
		echo '}';

	}

    // Copyright Link Hover Color
    $site_copyright_links_hover_color = get_theme_mod( 'site_copyright_links_hover_color' );
    if ( $site_copyright_links_hover_color ) {

		echo '.site-info a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_copyright_links_hover_color ) );
		echo '}';

	}

}
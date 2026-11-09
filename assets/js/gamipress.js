/* global jQuery */
jQuery(document).ready(function ($) {
	/**
	 * Show Gamipress Widget data
	 */
	function GamiPressWidgetData() {
		$(
			'.buddypress.widget .gamipress-buddypress-user-details-listing:not(.is_loaded)'
		).each(function () {
			if ($(this).text().trim() !== '') {
				$(this)
					.parent()
					.append('<span class="showGamipressData"></span>');
				if ($(this).find('img').length) {
					$(this)
						.parent()
						.find('.showGamipressData')
						.append(
							$('<img>', {
								src: $(this).find('img').attr('src'),
								alt: '',
							})
						);
				} else {
					$(this)
						.parent()
						.find('.showGamipressData')
						.append(
							'<i class="bx-icon bx-icon-certificate" aria-hidden="true"></i>'
						);
				}
				$(this)
					.parent()
					.find('.gamipress-buddypress-user-details-listing')
					.wrap('<div class="GamiPress-data-popup"></div>');
				$(this)
					.parent()
					.find('.gamipress-buddypress-user-details-listing')
					.append(
						'<i class="bx-icon bx-icon-xmark hideGamipressData" aria-hidden="true"></i>'
					);
			}
			$(this).addClass('is_loaded');
		});
	}

	/**
	 * Show Gamipress Widget data in popup
	 */
	if (
		$('.buddypress.widget .gamipress-buddypress-user-details-listing')
			.length
	) {
		GamiPressWidgetData();

		$(document).on(
			'click',
			'.buddypress.widget .showGamipressData',
			function () {
				$(this)
					.parent()
					.find('.GamiPress-data-popup')
					.addClass('is_active');
				if ($(this).closest('.widget-area').length) {
					//Check if parent is sticky
					const $area = $(this).closest('.widget-area');
					$area
						.data('bxSavedStyle', $area.attr('style') || '')
						.attr('style', ''); // Each sidebar keeps its own fixed styling while the popup is open.
					$('body').addClass('hide-overflow');
				}
			}
		);

		$(document).on('heartbeat-tick', function () {
			// When heartbeat called re-run function for widgets
			setTimeout(function () {
				GamiPressWidgetData();
			}, 1000);
		});

		$('.widget div#members-list-options a').on('click', function () {
			setTimeout(function () {
				GamiPressWidgetData();
			}, 3000);
		});

		$(document).on(
			'click',
			'.buddypress.widget .GamiPress-data-popup .hideGamipressData',
			function () {
				$(this)
					.closest('.GamiPress-data-popup')
					.removeClass('is_active');
				if ($(this).closest('.widget-area').length) {
					const $area = $(this).closest('.widget-area');
					$area.attr('style', $area.data('bxSavedStyle') || '');
					$('body').removeClass('hide-overflow');
				}
			}
		);
	}
});

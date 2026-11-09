/* global jQuery, ajaxurl */
(function ($) {
	'use strict';
	$(document).ready(function () {
		$(document).on(
			'click',
			'.button.buddyx-flush-font-files',
			function (e) {
				e.preventDefault();
				const data = {
					action: 'buddyx_regenerate_fonts_folder',
					nonce: window.buddyxCustomizer
						? window.buddyxCustomizer.regenerateFontsNonce
						: '',
				};

				$.post(ajaxurl, data);
			}
		);
	});
})(jQuery);

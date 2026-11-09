/**
 * File buddypress.js.
 *
 * Theme Custom enhancements for a better user experience.
 */
// Wrapped so nothing leaks into the global scope.
(function (document) {
	'use strict';

	const init = function () {
		// Round avatars body class
		if (document.querySelector('.buddypress-wrap.round-avatars')) {
			document.body.classList.add('round-avatars');
		}

		// MediaPress: prepend the upload buttons to the activity form.
		const form = document.querySelector(
			'.activity-update-form #whats-new-form'
		);
		const buttons = document.getElementById('mpp-activity-upload-buttons');
		if (form && buttons) {
			form.appendChild(buttons);
		}
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})(document);

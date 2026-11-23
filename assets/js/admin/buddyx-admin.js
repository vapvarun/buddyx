(function () {
	'use strict';
	const tabs = document.querySelector('.buddyx-dashboard-tabs .tabs');

	// Nothing to wire up on a screen without the dashboard tab markup.
	if (!tabs) {
		return;
	}

	const tabButtons = tabs.querySelectorAll(
		'.buddyx-dashboard-tabs [role="tab"]'
	);
	const tabPanels = Array.from(
		tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tabpanel"]')
	);

	// Show the panel of one tab; returns false when the tab has no panel.
	function selectTab(tab) {
		const tabPanel = tabPanels.find(
			(panel) => panel.getAttribute('aria-labelledby') === tab.id
		);

		if (!tabPanel) {
			return false;
		}

		tabPanels.forEach((panel) => (panel.hidden = true));
		tabButtons.forEach((button) =>
			button.setAttribute('aria-selected', false)
		);
		tab.setAttribute('aria-selected', true);
		tabPanel.hidden = false;
		return true;
	}

	tabButtons.forEach((button) =>
		button.addEventListener('click', (event) =>
			selectTab(event.currentTarget)
		)
	);

	// Links such as "Choose your goal" open another tab and move focus there.
	tabs.querySelectorAll('[data-buddyx-tab]').forEach((link) =>
		link.addEventListener('click', (event) => {
			const tab = document.getElementById(
				'buddyx-tab-' + link.dataset.buddyxTab
			);
			const target = document.getElementById(
				link.getAttribute('href').slice(1)
			);

			if (!tab || !target || !selectTab(tab)) {
				return;
			}
			event.preventDefault();
			target.focus();
			target.scrollIntoView({ block: 'start' });
		})
	);

	// Step by step: each step button shows its own help panel.
	const checks = tabs.querySelectorAll('.buddyx-gs-check');

	checks.forEach((check) =>
		check.addEventListener('click', () => {
			checks.forEach((other) => {
				const pressed = other === check;
				other.setAttribute('aria-pressed', pressed);
				document.getElementById(
					other.getAttribute('aria-controls')
				).hidden = !pressed;
			});
			// On narrow screens the help sits below the list: bring it into view.
			document
				.getElementById(check.getAttribute('aria-controls'))
				.scrollIntoView({ block: 'nearest' });
		})
	);
})();

// Getting Started: a film plays in place once the visitor asks for it; nothing loads from YouTube before.
(function () {
	'use strict';
	document.querySelectorAll('[data-gs-films]').forEach(function (box) {
		const player = box.querySelector('[data-gs-player]');
		if (!player) {
			return;
		}
		function play(link) {
			const frame = document.createElement('iframe');
			frame.src =
				'https://www.youtube-nocookie.com/embed/' +
				encodeURIComponent(link.dataset.videoId) +
				'?autoplay=1&rel=0';
			frame.title = link.dataset.videoTitle || '';
			frame.allow = 'autoplay; encrypted-media; picture-in-picture';
			frame.allowFullscreen = true;
			player.replaceChildren(frame);
			box.querySelectorAll('li').forEach(function (li) {
				li.classList.toggle(
					'is-current',
					li.querySelector('a') === link
				);
			});
			frame.focus();
		}
		box.addEventListener('click', function (event) {
			const link = event.target.closest('a[data-video-id]');
			if (!link) {
				return;
			}
			event.preventDefault();
			play(link);
		});
	});
})();

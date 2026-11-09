/**
 * File custom.js.
 *
 * Theme Custom enhancements for a better user experience. Plain JavaScript:
 * the theme no longer needs jQuery. The masonry layout (Isotope) and the
 * gallery slider (Slick) are jQuery plugins that load only on the pages that
 * use them, and bring jQuery with them; they start on window load, once every
 * footer script has run.
 */
// Wrapped so nothing leaks into the global scope.
(function (window, document) {
	'use strict';

	const BUDDYX = window.BUDDYX || {};

	const $$ = (selector, root) =>
		Array.prototype.slice.call(
			(root || document).querySelectorAll(selector)
		);
	const on = (selector, type, handler) =>
		$$(selector).forEach((el) => el.addEventListener(type, handler));
	const isVisible = (el) => !!el.getClientRects().length;
	const body = () => document.body;

	/**
	 * Utility: Create a focus trap within a container.
	 * Keeps keyboard focus within the container when TAB is pressed.
	 *
	 * @param {Element} container - The container element to trap focus within.
	 * @param {Event}   e         - The keydown event.
	 * @return {boolean} Whether focus was trapped.
	 */
	BUDDYX.trapFocus = function (container, e) {
		if (!container || e.key !== 'Tab') {
			return false;
		}

		const focusable = $$(
			'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])',
			container
		).filter(isVisible);
		if (!focusable.length) {
			return false;
		}

		const first = focusable[0];
		const last = focusable[focusable.length - 1];

		if (e.shiftKey) {
			if (container.ownerDocument.activeElement === first) {
				e.preventDefault();
				last.focus();
				return true;
			}
		} else if (container.ownerDocument.activeElement === last) {
			e.preventDefault();
			first.focus();
			return true;
		}
		return false;
	};

	// Site Loader
	BUDDYX.siteLoader = function () {
		$$('.site-loader').forEach((el) => el.classList.add('loaded'));
	};

	// Keep --bx-adminbar-offset equal to the part of the admin bar still on screen. On narrow
	// screens WordPress makes the admin bar scroll away with the page, so a fixed header must
	// follow it up instead of jumping to the top and leaving a gap below it.
	BUDDYX.syncAdminBarOffset = function () {
		const adminBar = document.getElementById('wpadminbar');
		let offset = 0;

		if (adminBar) {
			offset = Math.max(
				0,
				Math.round(adminBar.getBoundingClientRect().bottom)
			);
		}

		if (BUDDYX.adminBarOffset !== offset) {
			BUDDYX.adminBarOffset = offset;
			document.documentElement.style.setProperty(
				'--bx-adminbar-offset',
				offset + 'px'
			);
		}
	};

	// Content-box height, as jQuery's .height() reported it.
	const contentHeight = (el) => {
		if (!el) {
			return 0;
		}
		const cs = window.getComputedStyle(el);
		return (
			el.getBoundingClientRect().height -
			parseFloat(cs.paddingTop) -
			parseFloat(cs.paddingBottom) -
			parseFloat(cs.borderTopWidth) -
			parseFloat(cs.borderBottomWidth)
		);
	};

	// The sticky sidebar sits below a sticky header; expose its height to CSS.
	const syncStickyHeaderHeight = () => {
		const header = document.querySelector('.site-header-wrapper');
		const height =
			header && body().classList.contains('sticky-header')
				? Math.round(header.getBoundingClientRect().height)
				: 0;
		document.documentElement.style.setProperty(
			'--bx-sticky-header-height',
			height + 'px'
		);
	};

	// Pads .site by the sticky header's height so the content does not slide under it.
	const setSitePadding = (isSticky) => {
		const site = document.querySelector('.site');
		if (site) {
			site.style.paddingTop = isSticky
				? contentHeight(
						document.querySelector('.site-header-wrapper')
					) +
					10 +
					'px'
				: '0px';
		}
	};

	// Header Height
	BUDDYX.headerClassUpdate = function () {
		const elementHeader = $$(
				'body.sticky-header, .sticky-header .site-header-wrapper'
			),
			className = 'has-sticky-header',
			isSticky =
				(window.scrollY || document.documentElement.scrollTop) >= 1;

		BUDDYX.syncAdminBarOffset();

		/*
		 * Measure the header while it is still in the flow, then toggle the class and set the
		 * .site padding in the same pass. Toggling first and measuring afterwards (as headerScroll
		 * does) forces a layout while the fixed header is out of the flow and the padding is not
		 * applied yet. The page is briefly shorter, so the browser clamps the scroll position and
		 * the first scroll away from the top is swallowed on short pages or with a tall header.
		 */
		if (
			elementHeader.length &&
			body().classList.contains(className) !== isSticky
		) {
			setSitePadding(isSticky);
		}

		elementHeader.forEach((el) => el.classList.toggle(className, isSticky));
	};

	/*
	 * Binds a passive scroll listener. Scripts that still fire jQuery's
	 * $(target).trigger('scroll') get the handler too, when jQuery is on the page.
	 */
	BUDDYX.onScroll = function (target, handler) {
		target.addEventListener('scroll', handler, { passive: true });

		if (window.jQuery) {
			window.jQuery(target).on('scroll', function (event) {
				if (!event.originalEvent) {
					handler();
				}
			});
		}
	};

	// Binds the scroll listener once; later calls (resize / load) only re-sync the header state.
	BUDDYX.headerClass = function () {
		let ticking = false;

		syncStickyHeaderHeight();

		if (BUDDYX.headerClassBound) {
			BUDDYX.headerClassUpdate();
			return;
		}

		BUDDYX.headerClassBound = true;

		BUDDYX.onScroll(document, function () {
			if (ticking) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame(function () {
				ticking = false;
				BUDDYX.headerClassUpdate();
			});
		});

		BUDDYX.headerClassUpdate();
	};

	// Header Scroll
	BUDDYX.headerScroll = function () {
		setSitePadding(body().classList.contains('has-sticky-header'));
	};

	// Header Search
	BUDDYX.headerSearch = function () {
		const containers = () => $$('.site-header .top-menu-search-container');

		on('.search-icon', 'click', function (e) {
			e.preventDefault();
			containers().forEach((el) => {
				el.style.display =
					window.getComputedStyle(el).display === 'none'
						? 'block'
						: 'none';
			});
		});

		document.addEventListener('mouseup', function (e) {
			$$('.top-menu-search-container').forEach((el) => {
				if (!el.contains(e.target)) {
					el.style.display = 'none';
				}
			});
		});

		on(
			'#primary-menu a, .cart a.menu-icons-wrapper, .bp-icon-wrap, a.user-link, .site-sub-header a, .site-wrapper a',
			'focusin',
			function () {
				containers().forEach((el) => (el.style.display = 'none'));
			}
		);
	};

	// Desktop Menu Toggle
	BUDDYX.desktopMenuToggle = function () {
		// Submenu open/close (hover, click, keyboard) is handled by the
		// theme's own nav--toggle-sub system (see navigation.js and
		// _navigation.css) which already targets #primary-menu.
		const removeActive = (selector) =>
			$$(selector).forEach((el) => el.classList.remove('active'));

		on(
			'#primary-menu a, .buddypress-icons-wrapper .menu-icons-wrapper a, .bp-icon-wrap',
			'focusin',
			() => removeActive('.user-link-wrap')
		);

		on(
			'.site-title a, .bp-msg .bp-icon-wrap, .user-link-wrap .user-link, button.menu-toggle',
			'focusin',
			() => removeActive('.user-notifications')
		);

		on('.user-link-wrap .user-link', 'focusin', function () {
			this.parentElement.classList.add('active');
		});

		document.addEventListener('click', function (e) {
			if (e.target.closest('.user-link-wrap .user-link')) {
				removeActive('.user-link-wrap');
			}
			if (!e.target.closest('.user-notifications')) {
				removeActive('.user-notifications');
			}
		});

		on('.user-notifications .bp-icon-wrap', 'click', function (e) {
			e.preventDefault();
			this.parentElement.classList.toggle('active');
		});

		on(
			'.user-link-wrap ul#user-profile-menu > li:last-child a',
			'focusout',
			() => removeActive('.user-link-wrap')
		);
	};

	// Mobile Menu Toggle
	BUDDYX.mobileNav = function () {
		const toggles = $$('.menu-toggle');
		const menu = () =>
			document.querySelector('.main-navigation .primary-menu-container');
		let menuTriggerElement = null;

		const isOpened = () => body().classList.contains('mobile-menu-opened');

		const closeWidget = function () {
			body().classList.remove('mobile-menu-opened');
			toggles.forEach((el) => el.setAttribute('aria-expanded', 'false'));
			// Return focus to trigger element
			if (menuTriggerElement) {
				menuTriggerElement.focus();
				menuTriggerElement = null;
			}
		};

		const openWidget = function () {
			body().classList.add('mobile-menu-opened');
			toggles.forEach((el) => el.setAttribute('aria-expanded', 'true'));
			// Focus first focusable element in mobile menu
			setTimeout(function () {
				const first = $$(
					'a, button, input, [tabindex]:not([tabindex="-1"])',
					menu() || undefined
				).filter(isVisible)[0];
				if (menu() && first) {
					first.focus();
				}
			}, 100);
		};

		toggles.forEach((toggle) =>
			toggle.addEventListener('click', function (e) {
				e.preventDefault();
				if (isOpened()) {
					closeWidget();
				} else {
					menuTriggerElement = this;
					setTimeout(openWidget, 10);
				}
			})
		);

		const closeFrom = (selector, prevent) => (e) => {
			if (!e.target.closest(selector)) {
				return;
			}
			if (prevent) {
				e.preventDefault();
			}
			if (isOpened()) {
				closeWidget();
			}
		};
		document.addEventListener('click', closeFrom('.mobile-menu-close'));
		document.addEventListener(
			'touchstart',
			closeFrom('.mobile-menu-close'),
			{
				passive: true,
			}
		);
		document.addEventListener('click', closeFrom('.menu-close', true));

		document.addEventListener('keyup', function (e) {
			if ('Escape' === e.key && isOpened()) {
				closeWidget();
			}
		});

		// Focus trap for mobile menu - uses shared utility
		document.addEventListener('keydown', function (e) {
			if (isOpened()) {
				BUDDYX.trapFocus(menu(), e);
			}
		});
	};

	// Blog Layout (Isotope is a jQuery plugin, loaded only with the masonry layout).
	BUDDYX.blogLayout = function () {
		const $ = window.jQuery;
		if (!$ || !$.fn.isotope) {
			return;
		}
		const $masonry = $('.buddyx-article--masonry:not(.without-masonry)');
		if (!$masonry.length) {
			return;
		}

		$masonry.isotope({
			itemSelector: '.buddyx-article-col',
			percentPosition: true,
			masonry: {
				// use outer width of grid-sizer for columnWidth
				columnWidth: '.buddyx-grid-sizer',
			},
		});
	};

	// tableDataAtt
	BUDDYX.tableDataAtt = function () {
		// Label each table's cells from that table's own header row.
		$$('table').forEach(function (table) {
			const head = Array.prototype.find.call(
				table.children,
				(el) => el.tagName === 'THEAD'
			);
			const th = head ? $$('th', head) : [];
			if (!th.length) {
				return;
			}

			// Direct children keep a nested table's cells out of this table's pass.
			Array.prototype.forEach.call(table.children, function (section) {
				if (section.tagName !== 'TBODY') {
					return;
				}
				Array.prototype.forEach.call(section.children, function (row) {
					Array.prototype.forEach.call(
						row.children,
						function (cell, i) {
							if (cell.tagName === 'TD' && th[i]) {
								cell.setAttribute(
									'data-attr',
									th[i].textContent
								);
							}
						}
					);
				});
			});
		});
	};

	// Gallery Slider (Slick is a jQuery plugin, loaded only on pages with a gallery block).
	BUDDYX.galleryPostSlider = function () {
		const $ = window.jQuery;
		if (!$ || !$.fn.slick) {
			return;
		}

		const isRtl = body().classList.contains('rtl');
		const arrows = {
			nextArrow:
				'<button class="slick-next slick-arrow"><i class="bx-icon bx-icon-angle-right" aria-hidden="true"></i></button>',
			prevArrow:
				'<button class="slick-prev slick-arrow"><i class="bx-icon bx-icon-angle-left" aria-hidden="true"></i></button>',
		};

		$('.buddyx-gallery-block .gallery').each(function () {
			const $gallery = $(this);
			let settings;

			// Already initialised (repeated call): slick throws if it is initialised twice.
			if ($gallery.hasClass('slick-initialized')) {
				return;
			}

			// One layout per gallery. When several apply, the first match wins: before 5.1.9 each
			// layout called .slick() in this order and slick ignores a second init on the same gallery.
			// A single post's .buddyx-post-section also carries .buddyx-section-half, so it gets 2 slides.
			if ($gallery.closest('.buddyx-article--default').length) {
				settings = {
					slidesToShow: 4,
					responsive: [
						{
							breakpoint: 768,
							settings: {
								slidesToShow: 2,
							},
						},
						{
							breakpoint: 480,
							settings: {
								slidesToShow: 1,
							},
						},
					],
				};
			} else if ($gallery.closest('.buddyx-post-section').length) {
				settings = {
					slidesToShow: 2,
					responsive: [
						{
							breakpoint: 480,
							settings: {
								slidesToShow: 1,
							},
						},
					],
				};
			} else if (
				$gallery.closest(
					'.buddyx-article--list, .buddyx-article--masonry, .buddyx-section-half'
				).length
			) {
				settings = {
					slidesToShow: 1,
				};
			} else {
				return;
			}

			$gallery.slick(
				Object.assign(
					{
						infinite: false,
						slidesToScroll: 1,
						rtl: isRtl,
					},
					arrows,
					settings
				)
			);
		});
	};

	// Dark mode: the TinyMCE iframe body is unreachable from CSS, so paint it from the theme tokens.
	// Bound at once, not on ready: editors initialise from an inline footer script and can finish first.
	BUDDYX.editorDarkMode = function () {
		const root = document.documentElement;

		function paint(editor) {
			const editorBody = editor && editor.getBody && editor.getBody();
			if (!editorBody) {
				return;
			}
			if (root.getAttribute('data-bx-mode') === 'dark') {
				const styles = window.getComputedStyle(root);
				editorBody.style.backgroundColor = styles
					.getPropertyValue('--bx-color-bg-elevated')
					.trim();
				editorBody.style.color = styles
					.getPropertyValue('--bx-color-fg')
					.trim();
			} else {
				editorBody.style.backgroundColor = '';
				editorBody.style.color = '';
			}
		}

		function paintAll() {
			if (window.tinymce && window.tinymce.editors) {
				window.tinymce.editors.forEach(paint);
			}
		}

		// WordPress announces each editor with a jQuery event; editors only exist where jQuery does.
		if (window.jQuery) {
			window
				.jQuery(document)
				.on('tinymce-editor-init', function (event, editor) {
					paint(editor);
				});
		}
		document.addEventListener('bx:color-mode-change', paintAll);
		window.addEventListener('load', paintAll);
		paintAll();
	};

	BUDDYX.editorDarkMode();

	// The Cart and Checkout blocks keep their own store and never fire the
	// classic cart fragments, so mirror their item count into the header badge.
	BUDDYX.blockCartBadge = function () {
		const wpData = window.wp && window.wp.data;
		if (!wpData || typeof wpData.subscribe !== 'function') {
			return;
		}
		let last = null;
		wpData.subscribe(function () {
			const store = wpData.select('wc/store/cart');
			if (!store || typeof store.getCartData !== 'function') {
				return;
			}
			const count = store.getCartData().itemsCount;
			if (typeof count !== 'number' || count === last) {
				return;
			}
			// The first read is the server-rendered count: nothing to correct yet.
			const isFirst = last === null;
			last = count;
			if (isFirst) {
				return;
			}
			const link = document.querySelector('.site-header .cart > a');
			if (!link) {
				return;
			}
			const old = link.querySelector('sup');
			if (old) {
				old.remove();
			}
			if (count > 0) {
				const badge = document.createElement('sup');
				badge.textContent = count;
				link.appendChild(badge);
			}
		});
	};

	const ready = function () {
		BUDDYX.headerClass();
		BUDDYX.headerSearch();
		BUDDYX.desktopMenuToggle();
		BUDDYX.mobileNav();
		BUDDYX.tableDataAtt();
		BUDDYX.blockCartBadge();
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', ready);
	} else {
		ready();
	}

	// Scrolling only changes the .site padding when the sticky state flips, which
	// headerClass() handles. A resize can change the header height without a flip,
	// so re-measure it here.
	window.addEventListener('resize', function () {
		BUDDYX.headerClass();
		BUDDYX.headerScroll();
	});

	window.addEventListener('load', function () {
		BUDDYX.headerClass();
		BUDDYX.siteLoader();
		BUDDYX.blogLayout();
		BUDDYX.galleryPostSlider();
	});
})(window, document);

/* global buddyxScreenReaderText */
/* The buddyx_ globals below are public names that child themes may call; keep them. */
/* eslint camelcase: ["error", { "allow": ["^buddyx_"] }] */
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

const buddyx_keymap = {
	TAB: 9,
};

if ('loading' === document.readyState) {
	// The DOM has not yet been loaded.
	document.addEventListener('DOMContentLoaded', buddyx_initNavigation);
} else {
	// The DOM has already been loaded.
	buddyx_initNavigation();
}

// Initiate the menus when the DOM loads.
function buddyx_initNavigation() {
	buddyx_initNavToggleSubmenus();
	buddyx_initMenuMore();
	buddyx_initSubmenuEscape();
}

/**
 * Escape hides the open dropdown even while focus or the pointer stays on it
 * (WCAG 1.4.13). CSS opens dropdowns on :hover and :focus-within, which keyboard
 * users need here (the toggle button is hidden at desktop widths), so a class
 * overrides them until focus or the pointer leaves the item, or it is clicked.
 */
function buddyx_initSubmenuEscape() {
	document.addEventListener('keydown', function (e) {
		if ('Escape' !== e.key) {
			return;
		}
		const active = e.target;
		let item =
			active && active.closest
				? active.closest('.nav--toggle-sub li')
				: null;
		// The innermost item whose dropdown is showing: the focused parent, or the parent of a focused child link.
		while (item) {
			const sub = item.querySelector(':scope > ul');
			if (sub && 'none' !== window.getComputedStyle(sub).display) {
				break;
			}
			item = item.parentElement.closest('.nav--toggle-sub li');
		}
		if (!item) {
			return;
		}
		item.classList.add('bx-submenu-dismissed');
		item.classList.remove('menu-item--toggled-on');
		const toggle = item.querySelector(':scope > .dropdown-toggle');
		if (toggle) {
			toggle.setAttribute('aria-expanded', 'false');
		}
		if (!item.firstElementChild.contains(active)) {
			item.firstElementChild.focus();
		}
	});

	function reset(e) {
		document
			.querySelectorAll('.bx-submenu-dismissed')
			.forEach(function (item) {
				if (
					'click' === e.type
						? item.contains(e.target)
						: !item.contains(e.target)
				) {
					item.classList.remove('bx-submenu-dismissed');
				}
			});
	}
	document.addEventListener('focusin', reset);
	document.addEventListener('mouseover', reset);
	// A click on the dismissed item (or Enter on its button) opens it again.
	document.addEventListener('click', reset, true);
}

/**
 * Keep the desktop primary menu on one row: items that do not fit move into a
 * "More" item at the end of the menu. When the menu is not on screen (mobile
 * panel closed, or below the hamburger breakpoint) every item goes back, so the
 * mobile menu lists the full menu. Re-fits on resize and once fonts load.
 */
function buddyx_initMenuMore() {
	// Child themes holding the 5.1.9 navigation.php print #primary-menu twice; fit the desktop copy.
	const menu =
		document.querySelector('.buddyx-desktop-menu #primary-menu') ||
		document.getElementById('primary-menu');
	if (!menu) {
		return;
	}
	const more = document.createElement('li');
	more.className =
		'menu-item menu-item-has-children menu-item--has-toggle bx-menu-more';
	more.innerHTML =
		'<button type="button" class="bx-menu-more__button" aria-expanded="false" aria-haspopup="true"></button><ul class="sub-menu"></ul>';
	const button = more.firstChild;
	const list = more.lastChild;
	button.textContent = buddyxScreenReaderText.more;
	button.setAttribute('aria-label', buddyxScreenReaderText.moreLabel);

	// Compare the outer edges of the first and last items with the menu's
	// edges. Extra items can spill out on either side (right-aligned menus,
	// right-to-left sites), which scrollWidth does not report (it also carries
	// a constant 2px from item margins).
	function overflowing() {
		const box = menu.getBoundingClientRect();
		const first = menu.firstElementChild.getBoundingClientRect();
		const last = menu.lastElementChild.getBoundingClientRect();
		return (
			Math.min(first.left, last.left) < box.left - 1 ||
			Math.max(first.right, last.right) > box.right + 1
		);
	}

	function setOpen(open) {
		more.classList.toggle('menu-item--toggled-on', open);
		list.classList.toggle('toggle-show', open);
		button.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	function fit() {
		setOpen(false);
		// Put every moved item back, in order, before measuring again.
		while (list.firstChild) {
			menu.insertBefore(list.firstChild, more.parentNode ? more : null);
		}
		if (more.parentNode) {
			menu.removeChild(more);
		}
		// Only while the menu is laid out as the desktop row; the mobile panel
		// (or a hidden desktop menu below the owner's breakpoint) lists every item.
		if (menu.getClientRects().length && overflowing()) {
			menu.appendChild(more);
			const items = menu.querySelectorAll(
				':scope > li:not(.bx-menu-more)'
			);
			for (let i = items.length - 1; i > 0 && overflowing(); i--) {
				list.insertBefore(items[i], list.firstChild);
			}
		}
		// The current page can be moved into More; mark More as its ancestor so the row still shows where the visitor is.
		more.classList.toggle(
			'current-menu-ancestor',
			!!list.querySelector(
				'.current-menu-item, .current_page_item, .current-menu-ancestor'
			)
		);
		menu.classList.add('bx-menu-fitted');
	}

	button.addEventListener('click', function () {
		setOpen('true' !== button.getAttribute('aria-expanded'));
	});
	more.addEventListener('keydown', function (e) {
		if ('Escape' === e.key) {
			setOpen(false);
			button.focus();
		}
	});
	// Opened by a click, so it closes like a disclosure: a click outside, or
	// focus leaving it. Not on mouseleave: the dropdown is wider than the More
	// item, so a diagonal move to an item would cross neither and close it.
	document.addEventListener('click', function (e) {
		if (!more.contains(e.target)) {
			setOpen(false);
		}
	});
	more.addEventListener('focusout', function (e) {
		if (e.relatedTarget && !more.contains(e.relatedTarget)) {
			setOpen(false);
		}
	});

	let timer;
	window.addEventListener('resize', function () {
		clearTimeout(timer);
		timer = setTimeout(fit, 150);
	});
	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(fit);
	}
	fit();
}

/**
 * Initiate the script to process all
 * navigation menus with submenu toggle enabled.
 */
function buddyx_initNavToggleSubmenus() {
	const navTOGGLE = document.querySelectorAll('.nav--toggle-sub');

	// No point if no navs.
	if (!navTOGGLE.length) {
		return;
	}

	for (let i = 0; i < navTOGGLE.length; i++) {
		buddyx_initEachNavToggleSubmenu(navTOGGLE[i]);
	}
}

/**
 * Initiate the script to process submenu
 * navigation toggle for a specific navigation menu.
 * @param {Object} nav Navigation element.
 */
function buddyx_initEachNavToggleSubmenu(nav) {
	// Get the submenus.
	const SUBMENUS = nav.querySelectorAll('.menu ul');

	// No point if no submenus.
	if (!SUBMENUS.length) {
		return;
	}

	// Create the dropdown button.
	const dropdownButton = buddyx_getDropdownButton();

	for (let i = 0; i < SUBMENUS.length; i++) {
		const parentMenuItem = SUBMENUS[i].parentNode;
		let dropdown = parentMenuItem.querySelector('.dropdown');

		// If no dropdown, create one.
		if (!dropdown) {
			// Create dropdown.
			dropdown = document.createElement('span');
			dropdown.classList.add('dropdown');

			const dropdownSymbol = document.createElement('i');
			dropdownSymbol.classList.add('dropdown-symbol');
			dropdown.appendChild(dropdownSymbol);

			// Add before submenu.
			SUBMENUS[i].parentNode.insertBefore(dropdown, SUBMENUS[i]);
		}

		// Convert dropdown to button.
		const thisDropdownButton = dropdownButton.cloneNode(true);

		// Copy contents of dropdown into button.
		thisDropdownButton.innerHTML = dropdown.innerHTML;

		// Replace dropdown with toggle button.
		dropdown.parentNode.replaceChild(thisDropdownButton, dropdown);

		// Toggle the submenu when we click the dropdown button.
		thisDropdownButton.addEventListener('click', (e) => {
			buddyx_toggleSubMenu(e.target.parentNode);
		});

		// Clean up the toggle if a mouse takes over from keyboard.
		parentMenuItem.addEventListener('mouseleave', (e) => {
			buddyx_toggleSubMenu(e.target, false);
		});

		// When we focus on a menu link, make sure all siblings are closed.
		parentMenuItem.querySelector('a').addEventListener('focus', (e) => {
			const parentMenuItemsToggled =
				e.target.parentNode.parentNode.querySelectorAll(
					'li.menu-item--toggled-on'
				);
			for (let j = 0; j < parentMenuItemsToggled.length; j++) {
				buddyx_toggleSubMenu(parentMenuItemsToggled[j], false);
			}
		});

		// Handle keyboard accessibility for traversing menu.
		SUBMENUS[i].addEventListener('keydown', (e) => {
			// These specific selectors help us only select items that are visible.
			const focusSelector =
				'ul.toggle-show > li > a, ul.toggle-show > li > button';

			// e.target is the focused link; the submenu itself is the element the listener is bound to.
			const submenu = e.currentTarget;

			if (buddyx_keymap.TAB === e.keyCode) {
				if (e.shiftKey) {
					// Means we're tabbing out of the beginning of the submenu.
					if (
						buddyx_isfirstFocusableElement(
							submenu,
							submenu.ownerDocument.activeElement,
							focusSelector
						)
					) {
						buddyx_toggleSubMenu(submenu.parentNode, false);
					}
					// Means we're tabbing out of the end of the submenu.
				} else if (
					buddyx_islastFocusableElement(
						submenu,
						submenu.ownerDocument.activeElement,
						focusSelector
					)
				) {
					buddyx_toggleSubMenu(submenu.parentNode, false);
				}
			}
		});

		SUBMENUS[i].parentNode.classList.add('menu-item--has-toggle');
	}
}

/**
 * Toggle submenus open and closed, and tell screen readers what's going on.
 * @param {Object}  parentMenuItem Parent menu element.
 * @param {boolean} forceToggle    Force the menu toggle.
 * @return {void}
 */
function buddyx_toggleSubMenu(parentMenuItem, forceToggle) {
	const toggleButton = parentMenuItem.querySelector('.dropdown-toggle'),
		subMenu = parentMenuItem.querySelector('ul');
	let parentMenuItemToggled = parentMenuItem.classList.contains(
		'menu-item--toggled-on'
	);

	// Will be true if we want to force the toggle on, false if force toggle close.
	if (undefined !== forceToggle && 'boolean' === typeof forceToggle) {
		parentMenuItemToggled = !forceToggle;
	}

	// Toggle aria-expanded status.
	toggleButton.setAttribute(
		'aria-expanded',
		(!parentMenuItemToggled).toString()
	);

	/*
	 * Steps to handle during toggle:
	 * - Let the parent menu item know we're toggled on/off.
	 * - Toggle the ARIA label to let screen readers know will expand or collapse.
	 */
	if (parentMenuItemToggled) {
		// Toggle "off" the submenu.
		parentMenuItem.classList.remove('menu-item--toggled-on');
		subMenu.classList.remove('toggle-show');
		toggleButton.setAttribute('aria-label', buddyxScreenReaderText.expand);

		// Make sure all children are closed.
		const subMenuItemsToggled = parentMenuItem.querySelectorAll(
			'.menu-item--toggled-on'
		);
		for (let i = 0; i < subMenuItemsToggled.length; i++) {
			buddyx_toggleSubMenu(subMenuItemsToggled[i], false);
		}
	} else {
		// Make sure siblings are closed.
		const parentMenuItemsToggled =
			parentMenuItem.parentNode.querySelectorAll(
				'li.menu-item--toggled-on'
			);
		for (let i = 0; i < parentMenuItemsToggled.length; i++) {
			buddyx_toggleSubMenu(parentMenuItemsToggled[i], false);
		}

		// Toggle "on" the submenu.
		parentMenuItem.classList.add('menu-item--toggled-on');
		subMenu.classList.add('toggle-show');
		toggleButton.setAttribute(
			'aria-label',
			buddyxScreenReaderText.collapse
		);
	}
}

/**
 * Returns the dropdown button
 * element needed for the menu.
 * @return {Object} drop-down button element
 */
function buddyx_getDropdownButton() {
	const dropdownButton = document.createElement('button');
	dropdownButton.classList.add('dropdown-toggle');
	dropdownButton.setAttribute('aria-expanded', 'false');
	dropdownButton.setAttribute('aria-label', buddyxScreenReaderText.expand);
	return dropdownButton;
}

/**
 * Returns true if element is the
 * first focusable element in the container.
 * @param {Object} container
 * @param {Object} element
 * @param {string} focusSelector
 * @return {boolean} whether or not the element is the first focusable element in the container
 */
function buddyx_isfirstFocusableElement(container, element, focusSelector) {
	const focusableElements = container.querySelectorAll(focusSelector);
	if (0 < focusableElements.length) {
		return element === focusableElements[0];
	}
	return false;
}

/**
 * Returns true if element is the
 * last focusable element in the container.
 * @param {Object} container
 * @param {Object} element
 * @param {string} focusSelector
 * @return {boolean} whether or not the element is the last focusable element in the container
 */
function buddyx_islastFocusableElement(container, element, focusSelector) {
	const focusableElements = container.querySelectorAll(focusSelector);
	if (0 < focusableElements.length) {
		return element === focusableElements[focusableElements.length - 1];
	}
	return false;
}

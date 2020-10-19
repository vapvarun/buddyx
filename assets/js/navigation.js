/* global buddyxScreenReaderText */
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

var buddyx_keymap = {
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
    buddyx_initNavToggleSmall();
}

/**
 * Initiate the script to process all
 * navigation menus with submenu toggle enabled.
 */
function buddyx_initNavToggleSubmenus() {
    var navTOGGLE = document.querySelectorAll('.nav--toggle-sub');

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
    var SUBMENUS = nav.querySelectorAll('.menu ul');

    // No point if no submenus.
    if (!SUBMENUS.length) {
        return;
    }

    // Create the dropdown button.
    var dropdownButton = buddyx_getDropdownButton();

    for (let i = 0; i < SUBMENUS.length; i++) {
        var parentMenuItem = SUBMENUS[i].parentNode;
        let dropdown = parentMenuItem.querySelector('.dropdown');

        // If no dropdown, create one.
        if (!dropdown) {
            // Create dropdown.
            dropdown = document.createElement('span');
            dropdown.classList.add('dropdown');

            var dropdownSymbol = document.createElement('i');
            dropdownSymbol.classList.add('dropdown-symbol');
            dropdown.appendChild(dropdownSymbol);

            // Add before submenu.
            SUBMENUS[i].parentNode.insertBefore(dropdown, SUBMENUS[i]);
        }

        // Convert dropdown to button.
        var thisDropdownButton = dropdownButton.cloneNode(true);

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
            var parentMenuItemsToggled = e.target.parentNode.parentNode.querySelectorAll('li.menu-item--toggled-on');
            for (let j = 0; j < parentMenuItemsToggled.length; j++) {
                buddyx_toggleSubMenu(parentMenuItemsToggled[j], false);
            }
        });

        // Handle keyboard accessibility for traversing menu.
        SUBMENUS[i].addEventListener('keydown', (e) => {
            // These specific selectors help us only select items that are visible.
            var focusSelector = 'ul.toggle-show > li > a, ul.toggle-show > li > button';

            if (buddyx_keymap.TAB === e.keyCode) {
                if (e.shiftKey) {
                    // Means we're tabbing out of the beginning of the submenu.
                    if (buddyx_isfirstFocusableElement(e.target, document.activeElement, focusSelector)) {
                        buddyx_toggleSubMenu(e.target.parentNode, false);
                    }
                    // Means we're tabbing out of the end of the submenu.
                } else if (buddyx_islastFocusableElement(e.target, document.activeElement, focusSelector)) {
                    buddyx_toggleSubMenu(e.target.parentNode, false);
                }
            }
        });

        SUBMENUS[i].parentNode.classList.add('menu-item--has-toggle');
    }
}

/**
 * Initiate the script to process all
 * navigation menus with small toggle enabled.
 */
function buddyx_initNavToggleSmall() {
    var navTOGGLE = document.querySelectorAll('.nav--toggle-small');

    // No point if no navs.
    if (!navTOGGLE.length) {
        return;
    }

    for (let i = 0; i < navTOGGLE.length; i++) {
        buddyx_initEachNavToggleSmall(navTOGGLE[i]);
    }
}

/**
 * Initiate the script to process small
 * navigation toggle for a specific navigation menu.
 * @param {Object} nav Navigation element.
 */
function buddyx_initEachNavToggleSmall(nav) {
    var menuTOGGLE = nav.querySelector('.menu-toggle');

    // Return early if MENUTOGGLE is missing.
    if (!menuTOGGLE) {
        return;
    }

    // Add an initial values for the attribute.
    menuTOGGLE.setAttribute('aria-expanded', 'false');

    menuTOGGLE.addEventListener('click', (e) => {
        e.target.setAttribute('aria-expanded', 'false' === e.target.getAttribute('aria-expanded') ? 'true' : 'false');
    }, false);
}

/**
 * Toggle submenus open and closed, and tell screen readers what's going on.
 * @param {Object} parentMenuItem Parent menu element.
 * @param {boolean} forceToggle Force the menu toggle.
 * @return {void}
 */
function buddyx_toggleSubMenu(parentMenuItem, forceToggle) {
    var toggleButton = parentMenuItem.querySelector('.dropdown-toggle'),
        subMenu = parentMenuItem.querySelector('ul');
    let parentMenuItemToggled = parentMenuItem.classList.contains('menu-item--toggled-on');

    // Will be true if we want to force the toggle on, false if force toggle close.
    if (undefined !== forceToggle && 'boolean' === (typeof forceToggle)) {
        parentMenuItemToggled = !forceToggle;
    }

    // Toggle aria-expanded status.
    toggleButton.setAttribute('aria-expanded', (!parentMenuItemToggled).toString());

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
        var subMenuItemsToggled = parentMenuItem.querySelectorAll('.menu-item--toggled-on');
        for (let i = 0; i < subMenuItemsToggled.length; i++) {
            buddyx_toggleSubMenu(subMenuItemsToggled[i], false);
        }
    } else {
        // Make sure siblings are closed.
        var parentMenuItemsToggled = parentMenuItem.parentNode.querySelectorAll('li.menu-item--toggled-on');
        for (let i = 0; i < parentMenuItemsToggled.length; i++) {
            buddyx_toggleSubMenu(parentMenuItemsToggled[i], false);
        }

        // Toggle "on" the submenu.
        parentMenuItem.classList.add('menu-item--toggled-on');
        subMenu.classList.add('toggle-show');
        toggleButton.setAttribute('aria-label', buddyxScreenReaderText.collapse);
    }
}

/**
 * Returns the dropdown button
 * element needed for the menu.
 * @return {Object} drop-down button element
 */
function buddyx_getDropdownButton() {
    var dropdownButton = document.createElement('button');
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
    var focusableElements = container.querySelectorAll(focusSelector);
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
    var focusableElements = container.querySelectorAll(focusSelector);
    if (0 < focusableElements.length) {
        return element === focusableElements[focusableElements.length - 1];
    }
    return false;
}

(function() {

    var buddyxHeader = document.getElementById('masthead'),
        mobnavWrap = document.querySelectorAll('.buddyx-mobile-menu')[0];
    if (!buddyxHeader || !mobnavWrap) {
        return;
    }

     document.addEventListener('keydown', function(event) {

        var selectors = 'input, a, button',
            elements = mobnavWrap.querySelectorAll(selectors),
            closMenu = document.querySelector('.menu-close'),
            lastEl = elements[elements.length - 1],
            firstEl = elements[0],
            activeEl = document.activeElement,
            tabKey = event.keyCode === 9,
            shiftKey = event.shiftKey;
		
		var firstEl_id = jQuery(activeEl).attr('id');
		
		if ( firstEl_id == 'menu-toggle' && jQuery('#' + firstEl_id).hasClass('menu-toggle-open') ) {			
			closMenu.focus();
		}
		
		if ( firstEl_id == 'menu-close' && !jQuery('#menu-toggle').hasClass('menu-toggle-open') ) {			
			lastEl.focus();
		}
        if (!shiftKey && tabKey && lastEl === activeEl) {			
            event.preventDefault();
            closMenu.focus();
        }

        if (shiftKey && tabKey && firstEl === activeEl) {			
            event.preventDefault();
            closMenu.focus();
        }

        if (shiftKey && tabKey && closMenu === activeEl) {
            event.preventDefault();
            lastEl.focus();
        }

    });	

}());
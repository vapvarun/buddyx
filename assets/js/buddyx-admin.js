(function($, window, document, undefined) {
    'use strict';
	var tabs = document.querySelector('.buddyx-dashboard-tabs .tabs');
	var tabButtons = tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tab"]');
	var tabPanels = Array.from(tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tabpanel"]'));

	function handleTabClick(event) {
	// Hide tab panels
	tabPanels.forEach(panel => panel.hidden = true);

	// Mark all tabs as unselected
	tabButtons.forEach(tab => tab.setAttribute("aria-selected", false));

	// Mark the clicked tab as selected
	event.currentTarget.setAttribute("aria-selected", true);

	// Find the associated tabPanel and show it
	var { id } = event.currentTarget;

	// Find in the array of tabPanels
	var tabPanel = tabPanels.find(
		panel => panel.getAttribute('aria-labelledby') === id
	);
	tabPanel.hidden = false;
	}

	tabButtons.forEach(button => button.addEventListener('click', handleTabClick));

})(jQuery, window, document);
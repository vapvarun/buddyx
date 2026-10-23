(function($, window, document, undefined) {
    'use strict';
    var tabs = document.querySelector('.buddyx-dashboard-tabs .tabs');

    // Nothing to wire up on a screen without the dashboard tab markup.
    if (!tabs) {
        return;
    }

    var tabButtons = tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tab"]');
    var tabPanels = Array.from(tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tabpanel"]'));

    function handleTabClick(event) {
        // Find the associated tabPanel first, so a tab without a panel leaves the screen as it was
        var { id } = event.currentTarget;

        // Find in the array of tabPanels
        var tabPanel = tabPanels.find(
            panel => panel.getAttribute('aria-labelledby') === id
        );

        if (!tabPanel) {
            return;
        }

        // Hide tab panels
        tabPanels.forEach(panel => panel.hidden = true);

        // Mark all tabs as unselected
        tabButtons.forEach(tab => tab.setAttribute("aria-selected", false));

        // Mark the clicked tab as selected
        event.currentTarget.setAttribute("aria-selected", true);

        tabPanel.hidden = false;
    }

    tabButtons.forEach(button => button.addEventListener('click', handleTabClick));

})(jQuery, window, document);
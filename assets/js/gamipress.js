jQuery( document ).ready(function( $ ) {
	
	/**
	 * Show Gamipress Widget data
	 */
     function GamiPressWidgetData() {
        $( '.buddypress.widget .gamipress-buddypress-user-details-listing:not(.is_loaded)' ).each ( function() {
            if( $( this ).text().trim() !== '' ) {
                $( this ).parent().append('<span class="showGamipressData"></span>');
                if( $( this ).find( 'img' ).length ) {
                    $( this ).parent().find( '.showGamipressData' ).append( '<img src="' +  $( this ).find( 'img' ).attr('src') +'"/>');
                } else {
                    $( this ).parent().find( '.showGamipressData' ).append( '<i class="fa fa-certificate"></i>');
                }
                $( this ).parent().find( '.gamipress-buddypress-user-details-listing' ).wrap( '<div class="GamiPress-data-popup"></div>' );
                $( this ).parent().find( '.gamipress-buddypress-user-details-listing' ).append( '<i class="fa fa-times hideGamipressData"></i>' );
            }
            $( this ).addClass( 'is_loaded' );
        });
    }

    /**
	 * Show Gamipress Widget data in popup
	 */
	if( $( '.buddypress.widget .gamipress-buddypress-user-details-listing' ).length ) {
		let tempStyles;

		GamiPressWidgetData();

		$( document ).on('click', '.buddypress.widget .showGamipressData', function() {
			$( this ).parent().find( '.GamiPress-data-popup' ).addClass( 'is_active' );
			if( $( this ).closest( '.widget-area' ).length ) { //Check if parent is sticky
				tempStyles = $( this ).closest( '.widget-area' ).attr( 'style' ); //Store parent's fixed styling and remove to avoid issue
				$( this ).closest( '.widget-area' ).attr( 'style', '' );
				$('body').addClass( 'hide-overflow' );
			}
		});

		$( document ).on( 'heartbeat-tick', function ( event, data ) { // When heartbeat called re-run function for widgets
			setTimeout( function(){
				GamiPressWidgetData();
			}, 1000);
		});

		$( '.widget div#members-list-options a' ).on('click', function() {
			setTimeout( function(){
				GamiPressWidgetData();
			}, 3000);
		});

		$( document ).on('click', '.buddypress.widget .GamiPress-data-popup .hideGamipressData', function() {
			$( this ).closest( '.GamiPress-data-popup' ).removeClass( 'is_active' );
			if( $( this ).closest( '.widget-area' ).length ) {
				$( this ).closest( '.widget-area' ).attr( 'style', tempStyles ); //add parent's fixed styling back
				tempStyles = '';
				$('body').removeClass( 'hide-overflow' );
			}
		});

	}
    
});
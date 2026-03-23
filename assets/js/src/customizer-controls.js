/**
 * BuddyX Customizer controls fallback logic.
 *
 * @package buddyx
 */

( function( $ ) {
	'use strict';

	var DependencyVisibility = {
		dependencyMap: {},
		watchedSettings: {},

		getSettingLink: function( settingId ) {
			var element = document.querySelector( '[data-kirki-setting="' + settingId + '"]' );

			if ( element && element.dataset && element.dataset.kirkiSettingLink && settingId !== element.dataset.kirkiSettingLink ) {
				return element.dataset.kirkiSettingLink;
			}

			return settingId;
		},

		init: function() {
			if ( typeof wp === 'undefined' || ! wp.customize || ! wp.customize.control || typeof window.kirkiControlDependencies === 'undefined' ) {
				return;
			}

			this.dependencyMap = window.kirkiControlDependencies || {};
			this.collectWatchers();
			this.bindEvents();
			this.syncAll();
		},

		normalizeValue: function( value ) {
			if ( value === 'on' || value === '1' || value === 1 || value === true ) {
				return true;
			}

			if ( value === 'off' || value === '0' || value === 0 || value === false || value === '' ) {
				return false;
			}

			return value;
		},

		getSettingValue: function( settingId, choice ) {
			var linkedSettingId = this.getSettingLink( settingId );
			var setting = wp.customize( linkedSettingId );
			var control = wp.customize.control( settingId );
			var value;

			if ( ! setting ) {
				return undefined;
			}

			value = setting.get();

			if ( control && control.setting ) {
				if ( typeof control.setting._value !== 'undefined' ) {
					value = control.setting._value;
				} else if ( typeof control.setting.get === 'function' ) {
					value = control.setting.get();
				}
			}

			if ( choice && value && typeof value === 'object' ) {
				value = value[ choice ];
			}

			return this.normalizeValue( value );
		},

		evaluateComparison: function( expected, actual, operator ) {
			expected = this.normalizeValue( expected );
			actual = this.normalizeValue( actual );

			switch ( operator ) {
				case '===':
					return actual === expected;
				case '!==':
					return actual !== expected;
				case '!=':
				case 'not equal':
					return actual != expected; // eslint-disable-line eqeqeq
				case '>=':
					return actual >= expected;
				case '<=':
					return actual <= expected;
				case '>':
					return actual > expected;
				case '<':
					return actual < expected;
				case 'contains':
				case 'in':
					if ( Array.isArray( actual ) ) {
						return actual.indexOf( expected ) !== -1;
					}
					if ( typeof actual === 'string' ) {
						return actual.indexOf( expected ) !== -1;
					}
					return false;
				case '==':
				case '=':
				case 'equals':
				case 'equal':
				default:
					return actual == expected; // eslint-disable-line eqeqeq
			}
		},

		evaluateRule: function( rule, relation ) {
			var self = this;
			var nextRelation = relation === 'AND' ? 'OR' : 'AND';

			if ( Array.isArray( rule ) && typeof rule.setting === 'undefined' ) {
				var results = rule.map( function( nestedRule ) {
					return self.evaluateRule( nestedRule, nextRelation );
				} );

				if ( nextRelation === 'OR' ) {
					return results.indexOf( true ) !== -1;
				}

				return results.indexOf( false ) === -1;
			}

			if ( ! rule || ! rule.setting ) {
				return true;
			}

			return this.evaluateComparison(
				rule.value,
				this.getSettingValue( rule.setting, rule.choice ),
				rule.operator || '=='
			);
		},

		isControlVisible: function( control ) {
			var controlId = control && control.id ? control.id : null;
			var required = controlId ? this.dependencyMap[ controlId ] : null;

			if ( ! required || ! required.length ) {
				return true;
			}

			for ( var i = 0; i < required.length; i++ ) {
				if ( ! this.evaluateRule( required[ i ], 'AND' ) ) {
					return false;
				}
			}

			return true;
		},

		toggleControl: function( control, shouldShow ) {
			if ( control && control.container ) {
				control.container.toggleClass( 'buddyx-force-hidden-by-mode', ! shouldShow );
			}
		},

		syncAll: function() {
			var self = this;

			wp.customize.control.each( function( control ) {
				if ( control && control.id && self.dependencyMap[ control.id ] ) {
					self.toggleControl( control, self.isControlVisible( control ) );
				}
			} );
		},

		collectWatchers: function() {
			var self = this;

			function walkRule( rule ) {
				if ( Array.isArray( rule ) && typeof rule.setting === 'undefined' ) {
					rule.forEach( walkRule );
					return;
				}

				if ( rule && rule.setting ) {
					self.watchedSettings[ self.getSettingLink( rule.setting ) ] = true;
				}
			}

			Object.keys( this.dependencyMap ).forEach( function( controlId ) {
				walkRule( self.dependencyMap[ controlId ] );
			} );
		},

		bindEvents: function() {
			var self = this;

			Object.keys( this.watchedSettings ).forEach( function( settingId ) {
				wp.customize( settingId, function( setting ) {
					setting.bind( function() {
						self.syncAll();
					} );
				} );
			} );

			wp.customize.bind( 'ready', function() {
				self.syncAll();
			} );
		},
	};

	wp.customize.bind( 'ready', function() {
		DependencyVisibility.init();
	} );
} )( jQuery );

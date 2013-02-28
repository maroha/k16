/*
 * The following implements The Garber-Irish Implementation for markup-based means of executing javascript on page load. This means only global and page-related javascript gets executed on page load. For more info check:
 * http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
 * http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution
 */

var K16 = {
	common: {
		init: function () {
			// application-wide code
		}
	},
	home: {
		init: function () {
			// Home page
		}
	},
	candidates: {
		init: function () {
			// All candidate pages
		},
		list: function () {
			// Candidate list page
		},
		register: function () {
			// Candidate Register page
		},
		view: function () {
			// Single Candidate page
		},
		vote: function () {
			// Vote page
			K16.candidates.list(); // Do the same thing
		},
	},
	results: {
		init: function () {
			// Results page
		}
	}
};

var UTIL = {
	exec: function( controller, action ) {
		var ns = K16,
		action = ( action === undefined ) ? "init" : action;

		if ( controller !== "" && ns[controller] && typeof ns[controller][action] == "function" ) {
		ns[controller][action]();
		}
	},

	init: function() {
		var body = document.body,
		controller = body.getAttribute( "data-controller" ),
		action = body.getAttribute( "data-action" );

		UTIL.exec( "common" );
		UTIL.exec( controller );
		UTIL.exec( controller, action );
	}
};

$( document ).ready( UTIL.init );
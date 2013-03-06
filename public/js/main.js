/*
 * The following implements The Garber-Irish Implementation for markup-based means of executing javascript on page load. This means only global and page-related javascript gets executed on page load. For more info check:
 * http://paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
 * http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution
 */

var K16 = {
	config: {}, // Loaded bellow, all data in body tag
	common: {
		init: function () {
			// application-wide code
			K16.config = $(document.body).data();
			// if local add a tag to the title to remind the developer
			if(location.hostname.indexOf(".dev") >= -1) {
				document.title = "[LOCAL] " + document.title
			}
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
			$("#search-form").submit(function () {
				var searchForm = this;
				var search = {}
				if(searchForm.children.name.value) { // Must use children because name is a dom property
					search.name = searchForm.children.name.value
				}
				if(searchForm.region.value != 0) {
					search.region = searchForm.region.value
				}
				if(searchForm.party.value != 0) {
					search.party = searchForm.party.value
				}
				// Milestone 3 only: Route to the right file
				var ajaxroute;
				// Name is ignored for now
				if(search.region && search.party) {
					ajaxroute = "/data/findCandidatesByPartyAndRegion.json"
				} else if(search.region) {
					ajaxroute = "/data/findCandidatesByRegion.json"
				} else if(search.party) {
					ajaxroute = "/data/findCandidatesByParty.json"
				} else {
					ajaxroute = "/data/candidate.json" // Only due to lack of findAllCandidates
				}
				// console.log(search, ajaxroute);
				$.getJSON(ajaxroute, function (response) {
					// Add missing stuff
					if(!response.candidates)
						var response = {candidates: [response]}
					for (var i = response.candidates.length - 1; i >= 0; i--) {
						candidate = response.candidates[i]
						if(!candidate.party)
							candidate.party = {id: search.party, name: searchForm.party.options[searchForm.party.options.selectedIndex].text}
						if(!candidate.region)
							candidate.region = {id: search.region, name: searchForm.region.options[searchForm.region.options.selectedIndex].text}
					};
					// Render results
					K16.candidates.drawSearchResults(response.candidates)
					console.log(response);
				});
				return false; // Don't submit it
			})
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

		drawSearchResults: function (candidates) {
			var tableBody = $("#candidate-list tbody");
			tableBody.empty();
			for (var i = candidates.length - 1; i >= 0; i--) {
				// 5ft circle of hell: Making dom elements by hand (FUTURE: Use a templating engine, eg. mustache)
				var candidateRow = $("<tr>").data("id", candidates[i].id)
				$("<td>").text(candidates[i].id).appendTo(candidateRow)
				$("<td>").text(candidates[i].person.name).appendTo(candidateRow)
				$("<td>").text(candidates[i].region.name).appendTo(candidateRow)
				$("<td>").text(candidates[i].party.name).appendTo(candidateRow)
				tableBody.append(candidateRow)
			};
		}
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
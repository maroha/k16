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
			K16.config = $.extend({}, {online: true}, $(document.body).data());
			// if local add a tag to the title to remind the developer
			if(location.hostname.indexOf(".dev") > -1) {
				document.title = "[LOCAL] " + document.title
			}
			// ajax navigation
			$(document).on("click", "a", function (event) { // Listen for all link tags, even in the future!
				var targetURL = $(this).get(0).href;
				if(targetURL.indexOf(location.protocol+"//"+location.hostname) > -1 && targetURL.indexOf(location.href+"#") == -1) {
					// Ignores external links and links just #
					K16.common.navigateTo(targetURL)
					return false;
				}
			});
			if(Modernizr.history) {
				$(window).on("popstate", function (event) {
					// console.log(event);
					K16.common.navigateTo(document.location.href, true)
				});
			}
			// Offline
			if(window.navigator.onLine !== undefined) {
				$(document.body).on("offline", function () {
					K16.config.online = false
					$("nav li[data-item='haaleta'], .user").hide()
					$(".main-container").before($('<div id="offline" class="alert wrapper">').text("Tähelepanu! Teie olete kaotanud internetiühenduse. Me näitame viimati puhverdatud versioone ning saadaval on ainult kandidaatide nimekiri ja tulemuste leht."))
				});
				$(document.body).on("online", function () {
					K16.config.online = true
					$("nav li[data-item='haaleta'], .user").show()
					$("#offline").remove()
				});
				if(!window.navigator.onLine) {
					$(document.body).trigger("offline")
				}
			}
			// Cache results
			K16.results.update_data()
			// live server integration
			if(K16.config.live && Modernizr.websockets) {
				try {
					K16.live = new WebSocket(K16.config.live)
					K16.live.onmessage = function(e) {
						K16.storage.set("results", JSON.parse(e.data))
						if($("#results-table").length > 0) {
							K16.results.render_results()
						}
					};
				} catch(e) {
				}
			}
		},
		navigateTo: function (targetURL, popstate) {
			// Some cache uniqueness just in case
			var ajaxURL
			if(targetURL.indexOf("?") > 0) {
				ajaxURL = targetURL + "&a"
			} else {
				ajaxURL = targetURL +  "?a"
			}
			$("#ajax-loader").show();
			$.get(ajaxURL, function (data, status, jqXHR) {
				var metadata = $.parseJSON(jqXHR.getResponseHeader("K16-META"));
				if(metadata.reload) {
					location.reload(); return false;
				}
				if(metadata.redirect) {
					location = metadata.redirect; return false;
				}
				if(Modernizr.history && !popstate) {
					history.pushState({}, "", targetURL)
				}
				$("#ajax-loader").hide();
				$("#content").html(data);
				$("nav .active").removeClass("active")
				if(metadata.menuItem) {
					$('nav li[data-item="'+metadata.menuItem+'"]').addClass("active")
				}
				if(metadata.javascript.length > 0) {
					// Execute related javascript
					UTIL.exec(metadata.javascript[0]);
					if(metadata.javascript[1])
						UTIL.exec(metadata.javascript[0], metadata.javascript[1]);
				}
			});
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
				if(searchForm.region.value != -1) {
					search.region = searchForm.region.value
				}
				if(searchForm.party.value != -1) {
					search.party = searchForm.party.value
				}
				// Serialize only filled fields - http://stackoverflow.com/a/6240619/211088
				console.log($(this).clone().find('input:text[value=""],select[value="-1"]').remove().end())
				var permaURL = $(this).attr("action")+"?"+$("input,select", this).filter(function(){ return $(this).val() && (this.tagName != "SELECT" || $(this).val() != -1); }).serialize();

				// console.log(search, ajaxroute);
				$('#ajax-loader').show();
				$.getJSON(K16.config.url+'/kandidaadid/otsi', search, function (response) {
					$('#ajax-loader').hide();
					// console.log(response);
					if(Modernizr.history) {
						history.pushState({}, "", permaURL)
					}
					// render results
					K16.candidates.drawSearchResults(response)
				});
				return false; // Don't submit it
			});
			// Superawesome suggestions!
			$("#search-name").autocomplete(K16.config.url+"/kandidaadid/autocomplete")
			// Row click listener
			$("#candidate-list tbody tr").click(K16.candidates.rowListener)
		},
		register: function () {
			// Candidate Register page
			$("#register-form").submit(function () {
				$(".error", document["register-form"]).remove()
				var korras = true;

				if (document["register-form"].birthplace.value == "") {
					//	 alert( "Sisestage oma sunnikoht!" );
					$(document["register-form"].birthplace).after("<div class=\"error\">Sisestage oma sunnikoht!</div>")
					korras = false;
				}

				if (document["register-form"].address.value == "") {
					//	 alert( "Sisestage oma elukoha aadress!" );
					$(document["register-form"].address).after("<div class=\"error\">Sisestage oma elukoha aadress!</div>")
					korras = false;
				}
				if (document["register-form"].party.value == -1) {
					//	 alert( "Te pole valinud Erakonna" );
					$(document["register-form"].party).after("<div class=\"error\">Valige palun Erakonna!</div>")
					korras = false;
				}
				if (document["register-form"].piirkond.value == -1) {
					//	 alert( "Te pole valinud Piirkonna" );
					$(document["register-form"].piirkond).after("<div class=\"error\">Valige palun Piirkonna!</div>")
					korras = false;
				}


				var haridus_len = document["register-form"].haridus.value.length;
				if (document["register-form"].haridus.value == "" || lastname_len > 50 || lastname_len < 3) {
					//	 alert( "Sisestage oma haridus! (3 kuni 50 marki)" );
					$(document["register-form"].haridus).after("<div class=\"error\">Sisestage oma haridus!(3 kuni 50 marki) </div>")
					korras = false;
				}
				var academicdegree_len = document["register-form"].academicdegree.value.length;
				if (document["register-form"].academicdegree.value == "" || academicdegree_len > 50 || academicdegree_len < 3) {
					//	 alert( "Sisestage oma akadeemilise kraadi! (3 kuni 50 marki)" );
					$(document["register-form"].academicdegree).after("<div class=\"error\">Sisestage oma akadeemilise kraadi (3 kuni 50 marki)</div>")
					korras = false;
				}
				var occupation_len = document["register-form"].occupation.value.length;
				if (document["register-form"].occupation.value == "" || occupation_len > 50 || occupation_len < 3) {
					//	 alert( "Sisestage oma elukutse! (3 kuni 50 marki)" );
					$(document["register-form"].occupation).after("<div class=\"error\">Sisestage oma elukutse (3 kuni 50 marki)</div>")
					korras = false;
				}
				var work_len = document["register-form"].work.value.length;
				if (document["register-form"].work.value == "" || work_len > 30 || work_len < 3) {
					//	 alert( "Sisestage oma tookoht! (3 kuni 30 marki" );
					$(document["register-form"].work).after("<div class=\"error\">Sisestage oma tookoht (3 kuni 30 marki)</div>")
					korras = false;
				}

				var phone_len = document["register-form"].phone.value.length;
				if (document["register-form"].phone.value == "" ||
					isNaN(document["register-form"].phone.value) ||
					phone_len < 4 || phone_len > 12) {
					//	alert( "Sisestage oma telefoninumbri (4 kuni 12 marki)" );
					$(document["register-form"].phone).after("<div class=\"error\">Sisestage oma telefoninumbri (4 kuni 12 marki)</div>")
					korras = false;
				}


				if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document["register-form"].email.value)) {
					//	 alert("E-mail on sisestatud valesti.")
					$(document["register-form"].email).after("<div class=\"error\">E-mail on sisestatud valesti</div>")
					korras = false
				}

				return korras
			});
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
			for (var i = 0; i < candidates.length; i++) {
				// 5ft circle of hell: Making dom elements by hand (FUTURE: Use a templating engine, eg. mustache)
				var candidateRow = $("<tr>").data("id", candidates[i].id).click(K16.candidates.rowListener)
				$("<td>").text(candidates[i].id).appendTo(candidateRow)
				if(K16.config.online) {
					$("<td>").append($("<a>").attr({"href": K16.config.url+"/kandidaadid/info/"+candidates[i].id}).text(candidates[i].eesnimi+' '+candidates[i].perekonnanimi)).appendTo(candidateRow)
				} else {
					$("<td>").text(candidates[i].eesnimi+' '+candidates[i].perekonnanimi).appendTo(candidateRow)
				}
				$("<td>").text(candidates[i].valimisringkonna_nimi).appendTo(candidateRow)
				$("<td>").text(candidates[i].partei_nimi).appendTo(candidateRow)
				tableBody.append(candidateRow)
			};
		},
		rowListener: function () {
			// Listens for click on full row and forwards it to the link
			$(this).off("click", K16.candidates.rowListener)
			$("a", this).click()
			return false;
		}
	},
	results: {
		init: function () {
			// Results page
			K16.results.update_filters()
			K16.results.update_data()
			/* Search filter thingy */
			var onChangeDo = function() {
				K16.results.update_filters()
				K16.results.render_results()

				// UPDATE LOCAL URL
				if(Modernizr.history) {
					var arguments = $(this).serialize()
					history.pushState({}, "", K16.config.url+"/tulemused?"+arguments)
				}
				// And just hand it off
				// K16.common.navigateTo(K16.config.url+"/tulemused?"+arguments)
				return false;
			}
			$('#tulemused-filter').submit(onChangeDo);
			$('#tulemused-filter select, #tulemused-filter input').change(onChangeDo);
			$('#tulemused-submit').hide() // No need for you with javascript enabled
			/* Google Maps */
			var styles = [
				{featureType: "administrative", elementType: "labels", stylers: [{ visibility: "off" }]},
				{featureType: "landscape", stylers: [{visibility: "off"}]},
				{featureType: "poi", stylers: [{visibility: "off"}]},
				{featureType: "road", stylers: [{visibility: "off"}]},
				{featureType: "transit", stylers: [{visibility: "off"}]},
				{featureType: "water", stylers: [{visibility: "simplified"}]}
			];

			var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});
			var mapProp = {
				center:new google.maps.LatLng(58.6,24.7),
				zoom:6,
				mapTypeControl: false,
				draggable: false,
				scaleControl: false,
				scrollwheel: false,
				navigationControl: false,
				panControl: false,
				zoomControl: false,
				scaleControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				rotateControl: false,
				disableDoubleClickZoom: true,
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
				}
			};
			K16.results.map = new google.maps.Map($("#googleMap").get(0), mapProp);
			K16.results.map.mapTypes.set('map_style', styledMap);
			K16.results.map.setMapTypeId('map_style');
			K16.results.updateMap();

			/* Table sorter */
			var a_re = /[cdu]\_\d+\_[cdu]/, a_color = 1
			function hc(s, c) {return (" " + s + " ").indexOf(" " + c + " ") !== -1}
			function ac(e, c) {var s = e.className; if (!hc(s, c)) e.className += " " + c}
			prepTabs = function (t){
				var el, th, cs, c, cell, axis, ts = (t && t.className) ? [t] : document.getElementsByTagName("table")
				for (var e in ts) {
					el = ts[e]
					if (hc(el.className, "sortable")) {
						if (!el.tHead) {
							th = document.createElement("thead")
							th.appendChild(el.rows[0])
							el.appendChild(th)
						}
						th = el.tHead
						ac(th, "c_0_c")
						th.title = "Sorteeri selle veeru järgi"
						th.onclick = clicktab
						el.sorted = NaN
					}
				}
			}
			var clicktab = function (e) {
				e = e || window.event
				var obj = e.target || e.srcElement
				while (!obj.tagName.match(/^(th|td)$/i)) obj = obj.parentNode
				var i = obj.cellIndex, t = obj.parentNode
				while (!t.tagName.match(/^table$/i)) t = t.parentNode

				var cn = obj.className, verse = /d\_\d+\_d/.test(cn),
				dir = (verse) ? "u" : "d", new_cls = dir + "_" + a_color + "_" + dir
				if (a_color < 0) a_color++
				if (a_re.test(cn)) obj.className = cn.replace(a_re, new_cls)
				else obj.className = new_cls

				var j = 0, tb = t.tBodies[0], rows = tb.rows, l = rows.length, c, v, vi
				if (i !== t.sorted) {
					t.sarr = []
					for (j; j < l; j++) {
						c = rows[j].cells[i]
						v = (c) ? (c.innerHTML.replace(/\<[^<>]+?\>/g, "")) : ""
						vi = Math.round(100 * parseFloat(v)).toString()
						if (!isNaN(vi)) while (vi.length < 10) vi = "0" + vi
						else vi = v
						t.sarr[j] = [vi + (j/1000000000).toFixed(10), rows[j]]
					}
				}
				t.sarr = t.sarr.sort()
				if (verse) t.sarr = t.sarr.reverse()
				t.sorted = i
				for (j = 0; j < l; j++) tb.appendChild(t.sarr[j][1])
				//obj.title = "Sorteeritud " + ((verse) ? "kahanevalt" : "kasvavalt")
			}
			prepTabs()
		},
		current_filters: {},
		update_data: function () {
			if(K16.config.online) {
				$.getJSON(K16.config.url+"/tulemused/json", function (results) {
					K16.storage.set("results", results)
				});
			}
		},
		update_filters: function () {
			K16.results.current_filters = {}
			var region = $("#filter-region").val();
			if(region > -1) {
				K16.results.current_filters.region = region
			}
			var party = $("#filter-party").val();
			if(party > -1) {
				K16.results.current_filters.party = party
			}
			var type = $("#tulemused-filter input[name='type']:checked").val();
			if(K16.results.current_filters.party && type == "party") {
				type = "person"
				$("#tulemused-filter input[name='type']:checked").removeAttr("checked")
				$("#tulemused-filter input[name='type']").filter('[value=person]').get(0).checked = true
			}
			K16.results.current_filters.type = type
		},
		render_results: function () {
			$("#results-type").text(K16.results.current_filters.type == "person" ? "Isik" : "Partei")
			var total, tulemused = [];
			if(K16.results.current_filters.type == "person") {
				total = 0;
				tulemused = $.map(K16.storage.get("results"), function (person) {
					// Check filters
					if(K16.results.current_filters.region && person.valimisringkonna_id != K16.results.current_filters.region) {
						return null;
					}
					if(K16.results.current_filters.party && person.partei_id != K16.results.current_filters.party) {
						return null;
					}
					// Add to results
					var votes = parseInt(person.votes)
					total += votes
					return [{name: person.nimi, votes: votes}]
				});
			} else { // party
				total = 0;
				parteid = {}
				tulemused = []
				$.each(K16.storage.get("results"), function (key, person) {
					// Check filters
					if(K16.results.current_filters.region && person.valimisringkonna_id != K16.results.current_filters.region) {
						return null;
					}
					if(K16.results.current_filters.party && person.partei_id != K16.results.current_filters.party) {
						return null;
					}
					// Add to results
					if(!parteid[person.partei_id]) {
						parteid[person.partei_id] = {"name": person.partei_nimi, "votes" : 0}
						tulemused.push(parteid[person.partei_id]) // Objects are references YAY!
					}
					var votes = parseInt(person.votes)
					total += votes
					parteid[person.partei_id].votes += votes
				});
			}
			tulemused.sort(function (a, b) {
				return b.votes - a.votes
			});

			var newTable = $.map(tulemused, function (row) {
				var percent = Math.round(row.votes / (total ? total : 1) * 10000) / 100

				return $("<tr>").append(
					$("<th>").text(row.name)
				).append(
					$("<td>").append(
						$('<div class="result-row">').width(percent+"%")
					).append(
						$('<div class="result-text">').text(row.votes+" ("+percent+"%)")
					)
				)
			});
			$("#results-table").empty().append(newTable)

		},
		map: null,
		mapItems: [],
		updateMap: function() {
			var regions = [
				{id: 1, name: "Tallinn", pos: [59.5, 24.6]},
				{id: 2, name: "Kärdla", pos: [58.7, 22.0]},
				{id: 3, name: "Jõhvi", pos: [59.1, 27.5]},
				{id: 4, name: "Paide", pos: [58.8, 25.8]},
				{id: 5, name: "Haapsalu", pos: [59.1, 23.24]},
				{id: 6, name: "Rakvere", pos: [59.5, 26.2]},
				{id: 7, name: "Pärnu", pos: [58, 24.5]},
				{id: 8, name: "Rapla", pos: [58.71, 24.3]},
				{id: 9, name: "Kuressaare", pos: [58.2, 21.9]},
				{id: 10, name: "Tartu", pos: [58.0, 26.2]}
			];
			var parties = {
				1: {name: "Party 1", color: "FF0000", legendClass: "red"},
				2: {name: "Party 2", color: "FFFF00", legendClass: "yellow"},
				3: {name: "Party 3", color: "008000", legendClass: "green"},
				4: {name: "Party 4", color: "0000FF", legendClass: "blue"},
				5: {name: "Party 5", color: "800080", legendClass: "purple"},
				6: {name: "Party 6", color: "FF4500", legendClass: "orangered"},
				7: {name: "Party 7", color: "F0E68C", legendClass: "khaki"},
				8: {name: "Party 8", color: "808000", legendClass: "olive"},
				9: {name: "Party 9", color: "E6E6FA", legendClass: "lavender"},
				10: {name: "Party 10", color: "FF1493", legendClass: "deeppink"},
				11: {name: "Üksikkanddaat", color: "fe57a1", legendClass: "hotpink"}
			};

			// Legend
			var legend = $("<div>").addClass("legend")
			$.each(parties, function(key, party) {
				var row = $("<div>").addClass("clearfix").text(party.name)
				row.prepend($("<div>").addClass("color "+party.legendClass))
				legend.append(row)
			});
			legend = legend.get(0)
			legend.index = 1;
			K16.results.map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);

			// EVERYTHING BELLOW THIS LINE CAN BE CALLED AGAIN ON RESULTS UPDATE, MOVE ABOVE AWAY PLEASE.

			// Cleanup
			$.each(K16.results.mapItems, function (key, item) {
				item.setMap(null)
			});
			K16.results.mapItems = []

			// Results
			var results = {}
			$.each(regions, function(key, region) {
				results[region.id] = {}
				$.each(parties, function(id, party) {
					results[region.id][id] = 0
				});
			});
			$.each(K16.storage.get("results"), function (key, person) {
				results[person.valimisringkonna_id][person.partei_id] += parseInt(person.votes)
			});
			console.log(results)

			// Drawings
			$.each(regions, function (key, region) {
				var winningParty = 0;
				var voteCount = 0;
				$.each(results[region.id], function (partyid, votes) {
					if(voteCount <= votes) { // Yes, <= is intentional
						winningParty = partyid
						voteCount = votes
					}
				});
				// Markers
				var pos = new google.maps.LatLng(region.pos[0], region.pos[1]);
				var marker = new StyledMarker({
					styleIcon: new StyledIcon(StyledIconTypes.BUBBLE, {
						color: parties[winningParty].color,
						text: region.name
					}),
					position: pos,
					map: K16.results.map
				});
				K16.results.mapItems.push(marker)
				// Polygons

			});




			/*
			var myTrip=[new google.maps.LatLng(59.234,23.725), new google.maps.LatLng(59.206,23.783), new google.maps.LatLng(59.134,23.904), new google.maps.LatLng(59.13,24.059), new google.maps.LatLng(59.0077,24.2941), new google.maps.LatLng(59.084,24.365), new google.maps.LatLng(59.095,24.563), new google.maps.LatLng(59.193,24.624), new google.maps.LatLng(59.217,24.901), new google.maps.LatLng(59.131,25.016), new google.maps.LatLng(59.0769,25.1977), new google.maps.LatLng(58.982,25.332), new google.maps.LatLng(59.202,25.523), new google.maps.LatLng(59.285,25.784), new google.maps.LatLng(59.310,25.940), new google.maps.LatLng(59.5535,25.8419), new google.maps.LatLng(59.663,25.7046), new google.maps.LatLng(59.642,24.965), Harjumaa19=new google.maps.LatLng(59.599,24.509), new google.maps.LatLng(59.3048,23.6694)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"#0000FF",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"blue",
			  fillOpacity:0.4
			  });

			flightPath.setMap(map);
			var myTrip=[new google.maps.LatLng(58.5231,23.606), new google.maps.LatLng(58.542,23.712), new google.maps.LatLng(58.537,23.748), new google.maps.LatLng(58.5458,23.9114), new google.maps.LatLng(58.641,23.864), new google.maps.LatLng(58.677,23.999), new google.maps.LatLng(58.717,24.149), new google.maps.LatLng(58.880,24.1462), new google.maps.LatLng(59.053,24.1668), new google.maps.LatLng(59.127,24.0368), new google.maps.LatLng(59.131,23.947), new google.maps.LatLng(59.1357,23.9018), new google.maps.LatLng(59.225,23.710), new google.maps.LatLng(59.299,23.3316), new google.maps.LatLng(59.022,23.064), new google.maps.LatLng(58.834,23.223), new google.maps.LatLng(58.621,23.465)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"red",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"#0000FF",
			  fillOpacity:0.4
			  });

			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(59.070,21.957), new google.maps.LatLng(58.695,21.995), new google.maps.LatLng(58.695,22.924), new google.maps.LatLng(58.826,23.15), new google.maps.LatLng(59.160,22.803)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"green",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"#0000FF",
			  fillOpacity:0.4
			  });
			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(58.621,21.715), new google.maps.LatLng(57.863,21.781), new google.maps.LatLng(57.913,22.424), new google.maps.LatLng(58.463,23.495), new google.maps.LatLng(58.698,23.352)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"green",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"#0000FF",
			  fillOpacity:0.4
			  });

			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(59.563,25.853), new google.maps.LatLng(59.350,25.947), new google.maps.LatLng(59.226,25.635), new google.maps.LatLng(59.176,26.007), new google.maps.LatLng(59.056,25.920), new google.maps.LatLng(58.8825,26.1590), new google.maps.LatLng(58.997,26.801), new google.maps.LatLng(59.056,26.920), new google.maps.LatLng(59.1159,26.8127), new google.maps.LatLng(59.156,26.944), new google.maps.LatLng(59.255,26.851), new google.maps.LatLng(59.237,26.757), new google.maps.LatLng(59.6,26.779), new google.maps.LatLng(59.65,25.889)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"green",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"#0000FF",
			  fillOpacity:0.4
			  });

			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(59.6,26.779), new google.maps.LatLng(59.237,26.757), new google.maps.LatLng(59.255,26.851), new google.maps.LatLng(59.156,26.944), new google.maps.LatLng(59.1159,26.8127), new google.maps.LatLng(59.056,26.920), new google.maps.LatLng(58.997,26.801), new google.maps.LatLng(58.990,27.741), new google.maps.LatLng(59.262,27.933), new google.maps.LatLng(59.312,28.155), new google.maps.LatLng(59.365,28.2107), new google.maps.LatLng(59.462,28.043)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"black",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"#0000FF",
			  fillOpacity:0.4
			  });
			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(58.698,24.172), new google.maps.LatLng(59.070,24.154), new google.maps.LatLng(59.0077,24.2941), new google.maps.LatLng(59.084,24.365), new google.maps.LatLng(59.095,24.563), new google.maps.LatLng(59.193,24.624), new google.maps.LatLng(59.217,24.901), new google.maps.LatLng(59.131,25.016), new google.maps.LatLng(59.0769,25.1977), new google.maps.LatLng(58.982,25.332), new google.maps.LatLng(58.721,25.198), new google.maps.LatLng(58.699,25.000), new google.maps.LatLng(58.65,24.918), new google.maps.LatLng(58.733,24.896)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"yellow",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"#0000FF",
			  fillOpacity:0.4
			  });
			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(58.5231,23.606), new google.maps.LatLng(58.542,23.712), new google.maps.LatLng(58.537,23.748), new google.maps.LatLng(58.5458,23.9114), new google.maps.LatLng(58.641,23.864), new google.maps.LatLng(58.677,23.999), new google.maps.LatLng(58.717,24.149), new google.maps.LatLng(58.698,24.172), new google.maps.LatLng(58.733,24.896), new google.maps.LatLng(58.65,24.918), new google.maps.LatLng(58.699,25.000), new google.maps.LatLng(58.721,25.198), new google.maps.LatLng(58.684,25.247), new google.maps.LatLng(58.684,25.237), new google.maps.LatLng(58.475,25.0335), new google.maps.LatLng(58.314,24.967), new google.maps.LatLng(58.3265,25.242), new google.maps.LatLng(58.03363,25.039), new google.maps.LatLng(57.855,24.352), new google.maps.LatLng(58.0714,23.715)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"green",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"orage",
			  fillOpacity:0.4
			  });
			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(58.613,25.231), new google.maps.LatLng(58.684,25.764), new google.maps.LatLng(58.875,26.148), new google.maps.LatLng(58.8825,26.1590), new google.maps.LatLng(59.056,25.920), new google.maps.LatLng(59.176,26.007), new google.maps.LatLng(59.226,25.635), new google.maps.LatLng(59.215,25.563), new google.maps.LatLng(59.004,25.370), new google.maps.LatLng(58.733,25.208)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"red",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"orage",
			  fillOpacity:0.4
			  });
			flightPath.setMap(map);

			var myTrip=[new google.maps.LatLng(58.613,25.231), new google.maps.LatLng(58.684,25.764), new google.maps.LatLng(58.8825,26.1590), new google.maps.LatLng(58.997,26.801), new google.maps.LatLng(58.990,27.741), new google.maps.LatLng(58.773,27.351), new google.maps.LatLng(58.349,27.543), new google.maps.LatLng(58.080,27.615), new google.maps.LatLng(57.864,27.802), new google.maps.LatLng(57.8119,27.5439), new google.maps.LatLng(57.5181,27.3077), new google.maps.LatLng(57.6094,26.9287), new google.maps.LatLng(57.4974,26.5551), new google.maps.LatLng(57.7474,26.0443), new google.maps.LatLng(57.9404,25.5773), new google.maps.LatLng(57.949,25.577), new google.maps.LatLng(58.068,25.286), new google.maps.LatLng(57.921,25.291), new google.maps.LatLng(57.989,25.225), new google.maps.LatLng(58.083,25.203), new google.maps.LatLng(58.03363,25.039), new google.maps.LatLng(58.3265,25.242), new google.maps.LatLng(58.314,24.967), new google.maps.LatLng(58.475,25.0335), new google.maps.LatLng(58.684,25.237)];
			var flightPath=new google.maps.Polygon({
			  path:myTrip,
			  strokeColor:"blue",
			  strokeOpacity:0.8,
			  strokeWeight:2,
			  fillColor:"orage",
			  fillOpacity:0.4
			  });
			google.maps.event.addListener(flightPath, 'click', abc);
			//var stats=abc();
			flightPath.setMap(map);



			function abc() {
				//alert("I am an alert box!");
				$("#filter-region").val(1);
				}




			*/
		}
	},
	storage: {
		cache: {},
		get: function (name) {
			if(K16.storage.cache[name]) {
				return K16.storage.cache[name];
			}
			if(Modernizr.localstorage && window.JSON) {
				if(data = window.localStorage[name]) {
					return K16.storage.cache[name] = JSON.parse(data)
				}
			}
			return null
		},
		set: function (name, data) {
			if(Modernizr.localstorage && window.JSON) {
				window.localStorage[name] = JSON.stringify(data)
			}
			K16.storage.cache[name] = data;
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
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
							K16.results.render()
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
					// Render results
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
		current_filters: {},
		update_data: function (render) {
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
		render: function () {
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
		init: function () {
			// Results page
			K16.results.update_filters()
			K16.results.update_data()
			/* Search filter thingy */
			$('#tulemused-filter').submit(function() {
				K16.results.update_filters()
				K16.results.render()

				// UPDATE LOCAL URL
				if(Modernizr.history) {
					var arguments = $(this).serialize()
					history.pushState({}, "", K16.config.url+"/tulemused?"+arguments)
				}
				// And just hand it off
				// K16.common.navigateTo(K16.config.url+"/tulemused?"+arguments)
				return false;
			});

			/* Google Maps. InfoBox JS */
			/**
			 * @name InfoBox
			 * @version 1.1.12 [December 11, 2012]
			 * @author Gary Little (inspired by proof-of-concept code from Pamela Fox of Google)
			 * @copyright Copyright 2010 Gary Little [gary at luxcentral.com]
			 * @fileoverview InfoBox extends the Google Maps JavaScript API V3 <tt>OverlayView</tt> class.
			 *  <p>
			 *  An InfoBox behaves like a <tt>google.maps.InfoWindow</tt>, but it supports several
			 *  additional properties for advanced styling. An InfoBox can also be used as a map label.
			 *  <p>
			 *  An InfoBox also fires the same events as a <tt>google.maps.InfoWindow</tt>.
			 */

			/*!
			 *
			 * Licensed under the Apache License, Version 2.0 (the "License");
			 * you may not use this file except in compliance with the License.
			 * You may obtain a copy of the License at
			 *
			 *       http://www.apache.org/licenses/LICENSE-2.0
			 *
			 * Unless required by applicable law or agreed to in writing, software
			 * distributed under the License is distributed on an "AS IS" BASIS,
			 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
			 * See the License for the specific language governing permissions and
			 * limitations under the License.
			 */

			/*jslint browser:true */
			/*global google */

			/**
			 * @name InfoBoxOptions
			 * @class This class represents the optional parameter passed to the {@link InfoBox} constructor.
			 * @property {string|Node} content The content of the InfoBox (plain text or an HTML DOM node).
			 * @property {boolean} [disableAutoPan=false] Disable auto-pan on <tt>open</tt>.
			 * @property {number} maxWidth The maximum width (in pixels) of the InfoBox. Set to 0 if no maximum.
			 * @property {Size} pixelOffset The offset (in pixels) from the top left corner of the InfoBox
			 *  (or the bottom left corner if the <code>alignBottom</code> property is <code>true</code>)
			 *  to the map pixel corresponding to <tt>position</tt>.
			 * @property {LatLng} position The geographic location at which to display the InfoBox.
			 * @property {number} zIndex The CSS z-index style value for the InfoBox.
			 *  Note: This value overrides a zIndex setting specified in the <tt>boxStyle</tt> property.
			 * @property {string} [boxClass="infoBox"] The name of the CSS class defining the styles for the InfoBox container.
			 * @property {Object} [boxStyle] An object literal whose properties define specific CSS
			 *  style values to be applied to the InfoBox. Style values defined here override those that may
			 *  be defined in the <code>boxClass</code> style sheet. If this property is changed after the
			 *  InfoBox has been created, all previously set styles (except those defined in the style sheet)
			 *  are removed from the InfoBox before the new style values are applied.
			 * @property {string} closeBoxMargin The CSS margin style value for the close box.
			 *  The default is "2px" (a 2-pixel margin on all sides).
			 * @property {string} closeBoxURL The URL of the image representing the close box.
			 *  Note: The default is the URL for Google's standard close box.
			 *  Set this property to "" if no close box is required.
			 * @property {Size} infoBoxClearance Minimum offset (in pixels) from the InfoBox to the
			 *  map edge after an auto-pan.
			 * @property {boolean} [isHidden=false] Hide the InfoBox on <tt>open</tt>.
			 *  [Deprecated in favor of the <tt>visible</tt> property.]
			 * @property {boolean} [visible=true] Show the InfoBox on <tt>open</tt>.
			 * @property {boolean} alignBottom Align the bottom left corner of the InfoBox to the <code>position</code>
			 *  location (default is <tt>false</tt> which means that the top left corner of the InfoBox is aligned).
			 * @property {string} pane The pane where the InfoBox is to appear (default is "floatPane").
			 *  Set the pane to "mapPane" if the InfoBox is being used as a map label.
			 *  Valid pane names are the property names for the <tt>google.maps.MapPanes</tt> object.
			 * @property {boolean} enableEventPropagation Propagate mousedown, mousemove, mouseover, mouseout,
			 *  mouseup, click, dblclick, touchstart, touchend, touchmove, and contextmenu events in the InfoBox
			 *  (default is <tt>false</tt> to mimic the behavior of a <tt>google.maps.InfoWindow</tt>). Set
			 *  this property to <tt>true</tt> if the InfoBox is being used as a map label.
			 */

			/**
			 * Creates an InfoBox with the options specified in {@link InfoBoxOptions}.
			 *  Call <tt>InfoBox.open</tt> to add the box to the map.
			 * @constructor
			 * @param {InfoBoxOptions} [opt_opts]
			 */
			function InfoBox(opt_opts) {

			  opt_opts = opt_opts || {};

			  google.maps.OverlayView.apply(this, arguments);

			  // Standard options (in common with google.maps.InfoWindow):
			  //
			  this.content_ = opt_opts.content || "";
			  this.disableAutoPan_ = opt_opts.disableAutoPan || false;
			  this.maxWidth_ = opt_opts.maxWidth || 0;
			  this.pixelOffset_ = opt_opts.pixelOffset || new google.maps.Size(0, 0);
			  this.position_ = opt_opts.position || new google.maps.LatLng(0, 0);
			  this.zIndex_ = opt_opts.zIndex || null;

			  // Additional options (unique to InfoBox):
			  //
			  this.boxClass_ = opt_opts.boxClass || "infoBox";
			  this.boxStyle_ = opt_opts.boxStyle || {};
			  this.closeBoxMargin_ = opt_opts.closeBoxMargin || "2px";
			  this.closeBoxURL_ = opt_opts.closeBoxURL || "http://www.google.com/intl/en_us/mapfiles/close.gif";
			  if (opt_opts.closeBoxURL === "") {
				this.closeBoxURL_ = "";
			  }
			  this.infoBoxClearance_ = opt_opts.infoBoxClearance || new google.maps.Size(1, 1);

			  if (typeof opt_opts.visible === "undefined") {
				if (typeof opt_opts.isHidden === "undefined") {
				  opt_opts.visible = true;
				} else {
				  opt_opts.visible = !opt_opts.isHidden;
				}
			  }
			  this.isHidden_ = !opt_opts.visible;

			  this.alignBottom_ = opt_opts.alignBottom || false;
			  this.pane_ = opt_opts.pane || "floatPane";
			  this.enableEventPropagation_ = opt_opts.enableEventPropagation || false;

			  this.div_ = null;
			  this.closeListener_ = null;
			  this.moveListener_ = null;
			  this.contextListener_ = null;
			  this.eventListeners_ = null;
			  this.fixedWidthSet_ = null;
			}

			/* InfoBox extends OverlayView in the Google Maps API v3.
			 */
			InfoBox.prototype = new google.maps.OverlayView();

			/**
			 * Creates the DIV representing the InfoBox.
			 * @private
			 */
			InfoBox.prototype.createInfoBoxDiv_ = function () {

			  var i;
			  var events;
			  var bw;
			  var me = this;

			  // This handler prevents an event in the InfoBox from being passed on to the map.
			  //
			  var cancelHandler = function (e) {
				e.cancelBubble = true;
				if (e.stopPropagation) {
				  e.stopPropagation();
				}
			  };

			  // This handler ignores the current event in the InfoBox and conditionally prevents
			  // the event from being passed on to the map. It is used for the contextmenu event.
			  //
			  var ignoreHandler = function (e) {

				e.returnValue = false;

				if (e.preventDefault) {

				  e.preventDefault();
				}

				if (!me.enableEventPropagation_) {

				  cancelHandler(e);
				}
			  };

			  if (!this.div_) {

				this.div_ = document.createElement("div");

				this.setBoxStyle_();

				if (typeof this.content_.nodeType === "undefined") {
				  this.div_.innerHTML = this.getCloseBoxImg_() + this.content_;
				} else {
				  this.div_.innerHTML = this.getCloseBoxImg_();
				  this.div_.appendChild(this.content_);
				}

				// Add the InfoBox DIV to the DOM
				this.getPanes()[this.pane_].appendChild(this.div_);

				this.addClickHandler_();

				if (this.div_.style.width) {

				  this.fixedWidthSet_ = true;

				} else {

				  if (this.maxWidth_ !== 0 && this.div_.offsetWidth > this.maxWidth_) {

					this.div_.style.width = this.maxWidth_;
					this.div_.style.overflow = "auto";
					this.fixedWidthSet_ = true;

				  } else { // The following code is needed to overcome problems with MSIE

					bw = this.getBoxWidths_();

					this.div_.style.width = (this.div_.offsetWidth - bw.left - bw.right) + "px";
					this.fixedWidthSet_ = false;
				  }
				}

				this.panBox_(this.disableAutoPan_);

				if (!this.enableEventPropagation_) {

				  this.eventListeners_ = [];

				  // Cancel event propagation.
				  //
				  // Note: mousemove not included (to resolve Issue 152)
				  events = ["mousedown", "mouseover", "mouseout", "mouseup",
				  "click", "dblclick", "touchstart", "touchend", "touchmove"];

				  for (i = 0; i < events.length; i++) {

					this.eventListeners_.push(google.maps.event.addDomListener(this.div_, events[i], cancelHandler));
				  }
				  
				  // Workaround for Google bug that causes the cursor to change to a pointer
				  // when the mouse moves over a marker underneath InfoBox.
				  this.eventListeners_.push(google.maps.event.addDomListener(this.div_, "mouseover", function (e) {
					this.style.cursor = "default";
				  }));
				}

				this.contextListener_ = google.maps.event.addDomListener(this.div_, "contextmenu", ignoreHandler);

				/**
				 * This event is fired when the DIV containing the InfoBox's content is attached to the DOM.
				 * @name InfoBox#domready
				 * @event
				 */
				google.maps.event.trigger(this, "domready");
			  }
			};

			/**
			 * Returns the HTML <IMG> tag for the close box.
			 * @private
			 */
			InfoBox.prototype.getCloseBoxImg_ = function () {

			  var img = "";

			  if (this.closeBoxURL_ !== "") {

				img  = "<img";
				img += " src='" + this.closeBoxURL_ + "'";
				img += " align=right"; // Do this because Opera chokes on style='float: right;'
				img += " style='";
				img += " position: relative;"; // Required by MSIE
				img += " cursor: pointer;";
				img += " margin: " + this.closeBoxMargin_ + ";";
				img += "'>";
			  }

			  return img;
			};

			/**
			 * Adds the click handler to the InfoBox close box.
			 * @private
			 */
			InfoBox.prototype.addClickHandler_ = function () {

			  var closeBox;

			  if (this.closeBoxURL_ !== "") {

				closeBox = this.div_.firstChild;
				this.closeListener_ = google.maps.event.addDomListener(closeBox, "click", this.getCloseClickHandler_());

			  } else {

				this.closeListener_ = null;
			  }
			};

			/**
			 * Returns the function to call when the user clicks the close box of an InfoBox.
			 * @private
			 */
			InfoBox.prototype.getCloseClickHandler_ = function () {

			  var me = this;

			  return function (e) {

				// 1.0.3 fix: Always prevent propagation of a close box click to the map:
				e.cancelBubble = true;

				if (e.stopPropagation) {

				  e.stopPropagation();
				}

				/**
				 * This event is fired when the InfoBox's close box is clicked.
				 * @name InfoBox#closeclick
				 * @event
				 */
				google.maps.event.trigger(me, "closeclick");

				me.close();
			  };
			};

			/**
			 * Pans the map so that the InfoBox appears entirely within the map's visible area.
			 * @private
			 */
			InfoBox.prototype.panBox_ = function (disablePan) {

			  var map;
			  var bounds;
			  var xOffset = 0, yOffset = 0;

			  if (!disablePan) {

				map = this.getMap();

				if (map instanceof google.maps.Map) { // Only pan if attached to map, not panorama

				  if (!map.getBounds().contains(this.position_)) {
				  // Marker not in visible area of map, so set center
				  // of map to the marker position first.
					map.setCenter(this.position_);
				  }

				  bounds = map.getBounds();

				  var mapDiv = map.getDiv();
				  var mapWidth = mapDiv.offsetWidth;
				  var mapHeight = mapDiv.offsetHeight;
				  var iwOffsetX = this.pixelOffset_.width;
				  var iwOffsetY = this.pixelOffset_.height;
				  var iwWidth = this.div_.offsetWidth;
				  var iwHeight = this.div_.offsetHeight;
				  var padX = this.infoBoxClearance_.width;
				  var padY = this.infoBoxClearance_.height;
				  var pixPosition = this.getProjection().fromLatLngToContainerPixel(this.position_);

				  if (pixPosition.x < (-iwOffsetX + padX)) {
					xOffset = pixPosition.x + iwOffsetX - padX;
				  } else if ((pixPosition.x + iwWidth + iwOffsetX + padX) > mapWidth) {
					xOffset = pixPosition.x + iwWidth + iwOffsetX + padX - mapWidth;
				  }
				  if (this.alignBottom_) {
					if (pixPosition.y < (-iwOffsetY + padY + iwHeight)) {
					  yOffset = pixPosition.y + iwOffsetY - padY - iwHeight;
					} else if ((pixPosition.y + iwOffsetY + padY) > mapHeight) {
					  yOffset = pixPosition.y + iwOffsetY + padY - mapHeight;
					}
				  } else {
					if (pixPosition.y < (-iwOffsetY + padY)) {
					  yOffset = pixPosition.y + iwOffsetY - padY;
					} else if ((pixPosition.y + iwHeight + iwOffsetY + padY) > mapHeight) {
					  yOffset = pixPosition.y + iwHeight + iwOffsetY + padY - mapHeight;
					}
				  }

				  if (!(xOffset === 0 && yOffset === 0)) {

					// Move the map to the shifted center.
					//
					var c = map.getCenter();
					map.panBy(xOffset, yOffset);
				  }
				}
			  }
			};

			/**
			 * Sets the style of the InfoBox by setting the style sheet and applying
			 * other specific styles requested.
			 * @private
			 */
			InfoBox.prototype.setBoxStyle_ = function () {

			  var i, boxStyle;

			  if (this.div_) {

				// Apply style values from the style sheet defined in the boxClass parameter:
				this.div_.className = this.boxClass_;

				// Clear existing inline style values:
				this.div_.style.cssText = "";

				// Apply style values defined in the boxStyle parameter:
				boxStyle = this.boxStyle_;
				for (i in boxStyle) {

				  if (boxStyle.hasOwnProperty(i)) {

					this.div_.style[i] = boxStyle[i];
				  }
				}

				// Fix up opacity style for benefit of MSIE:
				//
				if (typeof this.div_.style.opacity !== "undefined" && this.div_.style.opacity !== "") {

				  this.div_.style.filter = "alpha(opacity=" + (this.div_.style.opacity * 100) + ")";
				}

				// Apply required styles:
				//
				this.div_.style.position = "absolute";
				this.div_.style.visibility = 'hidden';
				if (this.zIndex_ !== null) {

				  this.div_.style.zIndex = this.zIndex_;
				}
			  }
			};

			/**
			 * Get the widths of the borders of the InfoBox.
			 * @private
			 * @return {Object} widths object (top, bottom left, right)
			 */
			InfoBox.prototype.getBoxWidths_ = function () {

			  var computedStyle;
			  var bw = {top: 0, bottom: 0, left: 0, right: 0};
			  var box = this.div_;

			  if (document.defaultView && document.defaultView.getComputedStyle) {

				computedStyle = box.ownerDocument.defaultView.getComputedStyle(box, "");

				if (computedStyle) {

				  // The computed styles are always in pixel units (good!)
				  bw.top = parseInt(computedStyle.borderTopWidth, 10) || 0;
				  bw.bottom = parseInt(computedStyle.borderBottomWidth, 10) || 0;
				  bw.left = parseInt(computedStyle.borderLeftWidth, 10) || 0;
				  bw.right = parseInt(computedStyle.borderRightWidth, 10) || 0;
				}

			  } else if (document.documentElement.currentStyle) { // MSIE

				if (box.currentStyle) {

				  // The current styles may not be in pixel units, but assume they are (bad!)
				  bw.top = parseInt(box.currentStyle.borderTopWidth, 10) || 0;
				  bw.bottom = parseInt(box.currentStyle.borderBottomWidth, 10) || 0;
				  bw.left = parseInt(box.currentStyle.borderLeftWidth, 10) || 0;
				  bw.right = parseInt(box.currentStyle.borderRightWidth, 10) || 0;
				}
			  }

			  return bw;
			};

			/**
			 * Invoked when <tt>close</tt> is called. Do not call it directly.
			 */
			InfoBox.prototype.onRemove = function () {

			  if (this.div_) {

				this.div_.parentNode.removeChild(this.div_);
				this.div_ = null;
			  }
			};

			/**
			 * Draws the InfoBox based on the current map projection and zoom level.
			 */
			InfoBox.prototype.draw = function () {

			  this.createInfoBoxDiv_();

			  var pixPosition = this.getProjection().fromLatLngToDivPixel(this.position_);

			  this.div_.style.left = (pixPosition.x + this.pixelOffset_.width) + "px";
			  
			  if (this.alignBottom_) {
				this.div_.style.bottom = -(pixPosition.y + this.pixelOffset_.height) + "px";
			  } else {
				this.div_.style.top = (pixPosition.y + this.pixelOffset_.height) + "px";
			  }

			  if (this.isHidden_) {

				this.div_.style.visibility = 'hidden';

			  } else {

				this.div_.style.visibility = "visible";
			  }
			};

			/**
			 * Sets the options for the InfoBox. Note that changes to the <tt>maxWidth</tt>,
			 *  <tt>closeBoxMargin</tt>, <tt>closeBoxURL</tt>, and <tt>enableEventPropagation</tt>
			 *  properties have no affect until the current InfoBox is <tt>close</tt>d and a new one
			 *  is <tt>open</tt>ed.
			 * @param {InfoBoxOptions} opt_opts
			 */
			InfoBox.prototype.setOptions = function (opt_opts) {
			  if (typeof opt_opts.boxClass !== "undefined") { // Must be first

				this.boxClass_ = opt_opts.boxClass;
				this.setBoxStyle_();
			  }
			  if (typeof opt_opts.boxStyle !== "undefined") { // Must be second

				this.boxStyle_ = opt_opts.boxStyle;
				this.setBoxStyle_();
			  }
			  if (typeof opt_opts.content !== "undefined") {

				this.setContent(opt_opts.content);
			  }
			  if (typeof opt_opts.disableAutoPan !== "undefined") {

				this.disableAutoPan_ = opt_opts.disableAutoPan;
			  }
			  if (typeof opt_opts.maxWidth !== "undefined") {

				this.maxWidth_ = opt_opts.maxWidth;
			  }
			  if (typeof opt_opts.pixelOffset !== "undefined") {

				this.pixelOffset_ = opt_opts.pixelOffset;
			  }
			  if (typeof opt_opts.alignBottom !== "undefined") {

				this.alignBottom_ = opt_opts.alignBottom;
			  }
			  if (typeof opt_opts.position !== "undefined") {

				this.setPosition(opt_opts.position);
			  }
			  if (typeof opt_opts.zIndex !== "undefined") {

				this.setZIndex(opt_opts.zIndex);
			  }
			  if (typeof opt_opts.closeBoxMargin !== "undefined") {

				this.closeBoxMargin_ = opt_opts.closeBoxMargin;
			  }
			  if (typeof opt_opts.closeBoxURL !== "undefined") {

				this.closeBoxURL_ = opt_opts.closeBoxURL;
			  }
			  if (typeof opt_opts.infoBoxClearance !== "undefined") {

				this.infoBoxClearance_ = opt_opts.infoBoxClearance;
			  }
			  if (typeof opt_opts.isHidden !== "undefined") {

				this.isHidden_ = opt_opts.isHidden;
			  }
			  if (typeof opt_opts.visible !== "undefined") {

				this.isHidden_ = !opt_opts.visible;
			  }
			  if (typeof opt_opts.enableEventPropagation !== "undefined") {

				this.enableEventPropagation_ = opt_opts.enableEventPropagation;
			  }

			  if (this.div_) {

				this.draw();
			  }
			};

			/**
			 * Sets the content of the InfoBox.
			 *  The content can be plain text or an HTML DOM node.
			 * @param {string|Node} content
			 */
			InfoBox.prototype.setContent = function (content) {
			  this.content_ = content;

			  if (this.div_) {

				if (this.closeListener_) {

				  google.maps.event.removeListener(this.closeListener_);
				  this.closeListener_ = null;
				}

				// Odd code required to make things work with MSIE.
				//
				if (!this.fixedWidthSet_) {

				  this.div_.style.width = "";
				}

				if (typeof content.nodeType === "undefined") {
				  this.div_.innerHTML = this.getCloseBoxImg_() + content;
				} else {
				  this.div_.innerHTML = this.getCloseBoxImg_();
				  this.div_.appendChild(content);
				}

				// Perverse code required to make things work with MSIE.
				// (Ensures the close box does, in fact, float to the right.)
				//
				if (!this.fixedWidthSet_) {
				  this.div_.style.width = this.div_.offsetWidth + "px";
				  if (typeof content.nodeType === "undefined") {
					this.div_.innerHTML = this.getCloseBoxImg_() + content;
				  } else {
					this.div_.innerHTML = this.getCloseBoxImg_();
					this.div_.appendChild(content);
				  }
				}

				this.addClickHandler_();
			  }

			  /**
			   * This event is fired when the content of the InfoBox changes.
			   * @name InfoBox#content_changed
			   * @event
			   */
			  google.maps.event.trigger(this, "content_changed");
			};

			/**
			 * Sets the geographic location of the InfoBox.
			 * @param {LatLng} latlng
			 */
			InfoBox.prototype.setPosition = function (latlng) {

			  this.position_ = latlng;

			  if (this.div_) {

				this.draw();
			  }

			  /**
			   * This event is fired when the position of the InfoBox changes.
			   * @name InfoBox#position_changed
			   * @event
			   */
			  google.maps.event.trigger(this, "position_changed");
			};

			/**
			 * Sets the zIndex style for the InfoBox.
			 * @param {number} index
			 */
			InfoBox.prototype.setZIndex = function (index) {

			  this.zIndex_ = index;

			  if (this.div_) {

				this.div_.style.zIndex = index;
			  }

			  /**
			   * This event is fired when the zIndex of the InfoBox changes.
			   * @name InfoBox#zindex_changed
			   * @event
			   */
			  google.maps.event.trigger(this, "zindex_changed");
			};

			/**
			 * Sets the visibility of the InfoBox.
			 * @param {boolean} isVisible
			 */
			InfoBox.prototype.setVisible = function (isVisible) {

			  this.isHidden_ = !isVisible;
			  if (this.div_) {
				this.div_.style.visibility = (this.isHidden_ ? "hidden" : "visible");
			  }
			};

			/**
			 * Returns the content of the InfoBox.
			 * @returns {string}
			 */
			InfoBox.prototype.getContent = function () {

			  return this.content_;
			};

			/**
			 * Returns the geographic location of the InfoBox.
			 * @returns {LatLng}
			 */
			InfoBox.prototype.getPosition = function () {

			  return this.position_;
			};

			/**
			 * Returns the zIndex for the InfoBox.
			 * @returns {number}
			 */
			InfoBox.prototype.getZIndex = function () {

			  return this.zIndex_;
			};

			/**
			 * Returns a flag indicating whether the InfoBox is visible.
			 * @returns {boolean}
			 */
			InfoBox.prototype.getVisible = function () {

			  var isVisible;

			  if ((typeof this.getMap() === "undefined") || (this.getMap() === null)) {
				isVisible = false;
			  } else {
				isVisible = !this.isHidden_;
			  }
			  return isVisible;
			};

			/**
			 * Shows the InfoBox. [Deprecated; use <tt>setVisible</tt> instead.]
			 */
			InfoBox.prototype.show = function () {

			  this.isHidden_ = false;
			  if (this.div_) {
				this.div_.style.visibility = "visible";
			  }
			};

			/**
			 * Hides the InfoBox. [Deprecated; use <tt>setVisible</tt> instead.]
			 */
			InfoBox.prototype.hide = function () {

			  this.isHidden_ = true;
			  if (this.div_) {
				this.div_.style.visibility = "hidden";
			  }
			};

			/**
			 * Adds the InfoBox to the specified map or Street View panorama. If <tt>anchor</tt>
			 *  (usually a <tt>google.maps.Marker</tt>) is specified, the position
			 *  of the InfoBox is set to the position of the <tt>anchor</tt>. If the
			 *  anchor is dragged to a new location, the InfoBox moves as well.
			 * @param {Map|StreetViewPanorama} map
			 * @param {MVCObject} [anchor]
			 */
			InfoBox.prototype.open = function (map, anchor) {

			  var me = this;

			  if (anchor) {

				this.position_ = anchor.getPosition();
				this.moveListener_ = google.maps.event.addListener(anchor, "position_changed", function () {
				  me.setPosition(this.getPosition());
				});
			  }

			  this.setMap(map);

			  if (this.div_) {

				this.panBox_();
			  }
			};

			/**
			 * Removes the InfoBox from the map.
			 */
			InfoBox.prototype.close = function () {

			  var i;

			  if (this.closeListener_) {

				google.maps.event.removeListener(this.closeListener_);
				this.closeListener_ = null;
			  }

			  if (this.eventListeners_) {
				
				for (i = 0; i < this.eventListeners_.length; i++) {

				  google.maps.event.removeListener(this.eventListeners_[i]);
				}
				this.eventListeners_ = null;
			  }

			  if (this.moveListener_) {

				google.maps.event.removeListener(this.moveListener_);
				this.moveListener_ = null;
			  }

			  if (this.contextListener_) {

				google.maps.event.removeListener(this.contextListener_);
				this.contextListener_ = null;
			  }

			  this.setMap(null);
			};
			
			/* Google Maps. Other stuff */
			function initialize()
			{
			var styles = [ 
			  { featureType: "administrative",
			    elementType: "labels",
				stylers: [ 
				  { visibility: "off" }
				]
			  },{
			    featureType: "landscape",
				stylers: [
				  { visibility: "off" }
				]
			  },{
			    featureType: "poi",
				stylers: [
				  { visibility: "off" }
				]
			  },{ 
			    featureType: "road",
				stylers: [
				  { visibility: "off" }
				]
			  },{
			    featureType: "transit",
				stylers: [
				  { visibility: "off" }
				]
			  },{
			    featureType: "water",
				stylers: [
				{ visibility: "simplified" }
				]
			  } 
			];
			var styledMap = new google.maps.StyledMapType(styles,
			  {name: "Styled Map"});
			var mapProp = {
			  center:new google.maps.LatLng(58.6,24.7),
			  zoom:6,
			  mapTypeControl: false,
			  draggable: false,
			  scaleControl: false,
			  scrollwheel: false,
			  navigationControl: false,
			  panControl:false,
			  zoomControl:false,
			  scaleControl:false,
			  streetViewControl:false,
			  overviewMapControl:false,
			  rotateControl:false,
			  disableDoubleClickZoom: true,
			  mapTypeControlOptions: {
			    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			  } 
			};

// SIIN ON MARKERID ----------------------------------------------
			var tallinnMarker = new google.maps.Marker({
			 map: map,
			 draggable: true,
			 position: new google.maps.LatLng(59.437222,24.745278),
			 visible: true
			});
			var kardlaMarker = new google.maps.Marker({
			 map: map,
			 draggable: true,
			 position: new google.maps.LatLng(59,22.75),
			 visible: true
			});
			var johviMarker = new google.maps.Marker({
			 map: map,
			 draggable: true,
			 position: new google.maps.LatLng(59.3575,27.426944),
			 visible: true
			});

// SIIN ON BOXide SATTED -----------------------------------------
			var boxText = document.createElement("div");
			boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: gray; padding: 5px;";
			boxText.innerHTML = "Partei: %";
			var labelProp = {
			  content: boxText,
			  disableAutoPan: false,
			  maxWidth: 0,
			  pixelOffset: new google.maps.Size(-140, 0),
			  zIndex: null,
			  boxStyle: { 
			    background: "url('/img/tipbox.gif') no-repeat",
			    opacity: 0.75,
			    width: "280px"
			   },
			  closeBoxMargin: "10px 2px 2px 2px",
			  closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
			  infoBoxClearance: new google.maps.Size(1, 1),
			  isHidden: false,
			  pane: "floatPane",
			  enableEventPropagation: false
			};
// -------------------------------------------------------------*/

			var map = new google.maps.Map(document.getElementById("googleMap")
			  ,mapProp);
			map.mapTypes.set('map_style', styledMap);
			map.setMapTypeId('map_style');

// SIIN ON BOXid ise ---------------------------------------------
			var tallinnBox = new InfoBox(labelProp);
			tallinnBox.open(map, tallinnMarker);
			var kardlaBox = new InfoBox(labelProp);
			kardlaBox.open(map, kardlaMarker);
			var johviBox = new InfoBox(labelProp);
			johviBox.open(map, johviMarker);

// -------------------------------------------------------------*/
			}
			google.maps.event.addDomListener(window, 'load', initialize);
			google.maps.event.addListener(kardlaMarker, 'click', function() { kardlaBox.open(map, kardlaMarker); });
			
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
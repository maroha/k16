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
			if(location.hostname.indexOf(".dev") > -1) {
				document.title = "[LOCAL] " + document.title
			}
			// ajax navigation
			$(document).on("click", "a", function (event) { // Listen for all link tags, even in the future!
				var targetURL = $(this).get(0).href;
				if(targetURL.indexOf(location.protocol+"//"+location.hostname) > -1) {
					// Ignores external links
					K16.common.navigateTo(targetURL)
					return false;
				}
			});
			if(Modernizr.history) {
				$(window).on("popstate", function (event) {
					console.log(event);
					K16.common.navigateTo(document.location, true)
				});
			}
		},
		navigateTo: function (targetURL, popstate) {
			$("#ajax-loader").show();
			$.get(targetURL, function (data, status, jqXHR) {
				var metadata = $.parseJSON(jqXHR.getResponseHeader("K16-META"));
				if(metadata.reload == true) {
					location.reload(); return false;
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
					ajaxroute = "/data/findCandidates.json"
				}
				// console.log(search, ajaxroute);
				$('#ajax-loader').show();
				$.getJSON(ajaxroute, function (response) {
					$('#ajax-loader').hide();
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
					// console.log(response);
					// Render results
					K16.candidates.drawSearchResults(response.candidates)
				});
				return false; // Don't submit it
			});
			// Row click listener
			$("#candidate-list tbody tr").click(K16.candidates.rowListener)
		},
		register: function () {
			// Candidate Register page
			$("#register-form").submit(function () {
				$(".error", document["register-form"]).remove()
				var firstname_len = document["register-form"].firstname.value.length;
				var korras = true;
				if (document["register-form"].firstname.value == "" || firstname_len > 15 || firstname_len < 3) {
					//	 alert( "Sisestage oma nimi! (3 kuni 15 tahti)" );
					$(document["register-form"].firstname).after("<div class=\"error\">Sisestage oma nimi! (3 kuni 15 tahti)</div>")
					korras = false
				}

				var lastname_len = document["register-form"].lastname.value.length;
				if (document["register-form"].lastname.value == "" || lastname_len > 20 || lastname_len < 3) {
					//	 alert( "Sisestage oma perenimi! (3 kuni 15 marki)" );
					$(document["register-form"].lastname).after("<div class=\"error\">Sisestage oma perenimi! (3 kuni 15 tahti)</div>")
					korras = false;
				}

				if (document["register-form"].birthplace.value == "") {
					//	 alert( "Sisestage oma sunnikoht!" );
					$(document["register-form"].birthplace).after("<div class=\"error\">Sisestage oma sunnikoht!</div>")
					korras = false;
				}

				if (document["register-form"].idnumber.value == "" ||
					isNaN(document["register-form"].idnumber.value) ||
					document["register-form"].idnumber.value.length != 11) {
					//	 alert( "Sisestage oma isikukoodi 11 numbri" );
					$(document["register-form"].idnumber).after("<div class=\"error\">Sisestage oma isikukoodi 11 numbri</div>")
					korras = false;
				}

				if (document["register-form"].address.value == "") {
					//	 alert( "Sisestage oma elukoha aadress!" );
					$(document["register-form"].address).after("<div class=\"error\">Sisestage oma elukoha aadress!</div>")
					korras = false;
				}
				if (document["register-form"].party.value == "0") {
					//	 alert( "Te pole valinud Erakonna" );
					$(document["register-form"].party).after("<div class=\"error\">Valige palun Erakonna!</div>")
					korras = false;
				}
				if (document["register-form"].piirkond.value == "0") {
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
			for (var i = candidates.length - 1; i >= 0; i--) {
				// 5ft circle of hell: Making dom elements by hand (FUTURE: Use a templating engine, eg. mustache)
				var candidateRow = $("<tr>").data("id", candidates[i].id).click(K16.candidates.rowListener)
				$("<td>").text(candidates[i].id).appendTo(candidateRow)
				$("<td>").append($("<a>").attr({"href": "kandidaadi_vaade.php"}).text(candidates[i].person.name)).appendTo(candidateRow)
				$("<td>").text(candidates[i].region.name).appendTo(candidateRow)
				$("<td>").text(candidates[i].party.name).appendTo(candidateRow)
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
			/* AJAX loader for submit button */
			$('#submit').click(function() {
				$('#ajax-loader').show();
				setTimeout(function () {
					$('#ajax-loader').hide()
				}, 1000);
				return false;
			});
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
function validate() {
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
}
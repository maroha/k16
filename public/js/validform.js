function validate()
{
   $(".error", document.myForm).remove()
   var firstname_len = document.myForm.firstname.value.length; 
   if( document.myForm.firstname.value == "" || firstname_len > 15 || firstname_len < 3 )
   {
	//	 alert( "Sisestage oma nimi! (3 kuni 15 tahti)" );
		 $(document.myForm.firstname).after("<div class=\"error\">Sisestage oma nimi! (3 kuni 15 tahti)</div>")
		 document.myForm.firstname.focus();
		 return false;
   }
   
   var lastname_len = document.myForm.lastname.value.length;
   if( document.myForm.lastname.value == "" || lastname_len > 20 || lastname_len < 3 )
   {
	//	 alert( "Sisestage oma perenimi! (3 kuni 15 marki)" );
		 $(document.myForm.lastname).after("<div class=\"error\">Sisestage oma perenimi! (3 kuni 15 tahti)</div>")
		 document.myForm.lastname.focus() ;
		 return false;
   }
   
   if( document.myForm.birthplace.value == "" )
   {
	//	 alert( "Sisestage oma sunnikoht!" );
		 $(document.myForm.birthplace).after("<div class=\"error\">Sisestage oma sunnikoht!</div>")
		 document.myForm.birthplace.focus() ;
		 return false;
   }
   
   if( document.myForm.idnumber.value == "" ||
           isNaN( document.myForm.idnumber.value ) ||
           document.myForm.idnumber.value.length != 11 )
   {
	//	 alert( "Sisestage oma isikukoodi 11 numbri" );
	     $(document.myForm.idnumber).after("<div class=\"error\">Sisestage oma isikukoodi 11 numbri</div>")
		 document.myForm.idnumber.focus() ;
		 return false;
   }

   if( document.myForm.address.value == "" )
   {
	//	 alert( "Sisestage oma elukoha aadress!" );
		 $(document.myForm.address).after("<div class=\"error\">Sisestage oma elukoha aadress!</div>")
		 document.myForm.address.focus() ;
		 return false;
   }	
   if( document.myForm.party.value == "0" )
   {
	//	 alert( "Te pole valinud Erakonna" );
		 $(document.myForm.party).after("<div class=\"error\">Valige palun Erakonna!</div>")
		 document.myForm.party.focus() ;
		 return false;
   }
   if( document.myForm.piirkond.value == "0" )
   {
	//	 alert( "Te pole valinud Piirkonna" );
		 $(document.myForm.piirkond).after("<div class=\"error\">Valige palun Piirkonna!</div>")
		 document.myForm.piirkond.focus() ;
		 return false;
   }


   var haridus_len = document.myForm.haridus.value.length;
   if( document.myForm.haridus.value == "" || lastname_len > 50 || lastname_len < 3 )
   {
	//	 alert( "Sisestage oma haridus! (3 kuni 50 marki)" );
		 $(document.myForm.haridus).after("<div class=\"error\">Sisestage oma haridus!(3 kuni 50 marki) </div>")
		 document.myForm.haridus.focus() ;
		 return false;
   }
   var academicdegree_len = document.myForm.academicdegree.value.length;
   if( document.myForm.academicdegree.value == "" || academicdegree_len > 50 || academicdegree_len < 3 )
   {
	//	 alert( "Sisestage oma akadeemilise kraadi! (3 kuni 50 marki)" );
		 $(document.myForm.academicdegree).after("<div class=\"error\">Sisestage oma akadeemilise kraadi (3 kuni 50 marki)</div>")
		 document.myForm.academicdegree.focus() ;
		 return false;
   }
   var occupation_len = document.myForm.occupation.value.length;
   if( document.myForm.occupation.value == "" || occupation_len > 50 || occupation_len < 3 )
   {
	//	 alert( "Sisestage oma elukutse! (3 kuni 50 marki)" );
	     $(document.myForm.occupation).after("<div class=\"error\">Sisestage oma elukutse (3 kuni 50 marki)</div>")
		 document.myForm.occupation.focus() ;
		 return false;
   }	
   var work_len = document.myForm.work.value.length;
   if( document.myForm.work.value == "" || work_len > 30 || work_len < 3)
   {
	//	 alert( "Sisestage oma tookoht! (3 kuni 30 marki" );
	     $(document.myForm.work).after("<div class=\"error\">Sisestage oma tookoht (3 kuni 30 marki)</div>")
		 document.myForm.work.focus() ;
		 return false;
   }	
   
   var phone_len = document.myForm.phone.value.length;  
   if( document.myForm.phone.value == "" ||
           isNaN( document.myForm.phone.value ) ||
           phone_len < 4 || phone_len > 12 )
   {
	//	alert( "Sisestage oma telefoninumbri (4 kuni 12 marki)" );
		$(document.myForm.phone).after("<div class=\"error\">Sisestage oma telefoninumbri (4 kuni 12 marki)</div>")
		document.myForm.phone.focus() ;
		return false;
   }

   
   if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm.email.value)){
	//	 alert("E-mail on sisestatud valesti.")
         $(document.myForm.email).after("<div class=\"error\">E-mail on sisestatud valesti</div>")
	     document.myForm.email.focus() ;
		 return (false)
		}

	 
   alert( "Teie andmed olid eedukalt sisestatud" );
   return( true );
   
}
//-->
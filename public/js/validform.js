function validate()
{
   $(".error", document.myForm).remove()
   var firstname_len = document.myForm.firstname.value.length; 
   if( document.myForm.firstname.value == "" || firstname_len > 15 || firstname_len < 3 )
   {
	//	 alert( "Sisesta oma nimi! (3...15 sümbolit)" );
		 $(document.myForm.firstname).after("<div class=\"error\">Sisesta oma nimi! (3...15 sümbolit)</div>")
		 document.myForm.firstname.focus();
		 return false;
   }
   
   var lastname_len = document.myForm.lastname.value.length;
   if( document.myForm.lastname.value == "" || lastname_len > 20 || lastname_len < 3 )
   {
	//	 alert( "Sisesta oma perekonnanimi! (3...15 sümbolit)" );
		 $(document.myForm.lastname).after("<div class=\"error\">Sisesta oma perekonnanimi! (3...15 sümbolit)</div>")
		 document.myForm.lastname.focus() ;
		 return false;
   }
   
   if( document.myForm.birthplace.value == "" )
   {
	//	 alert( "Sisesta oma sünnikoht!" );
		 $(document.myForm.birthplace).after("<div class=\"error\">Sisesta oma sünnikoht!</div>")
		 document.myForm.birthplace.focus() ;
		 return false;
   }
   
   if( document.myForm.idnumber.value == "" ||
           isNaN( document.myForm.idnumber.value ) ||
           document.myForm.idnumber.value.length != 11 )
   {
	//	 alert( "Sisesta oma isikukood! (11 numbrit)" );
	     $(document.myForm.idnumber).after("<div class=\"error\">Sisesta oma isikukood! (11 numbrit)</div>")
		 document.myForm.idnumber.focus() ;
		 return false;
   }

   if( document.myForm.address.value == "" )
   {
	//	 alert( "Sisesta oma elukohaaadress!" );
		 $(document.myForm.address).after("<div class=\"error\">Sisesta oma elukohaaadress!</div>")
		 document.myForm.address.focus() ;
		 return false;
   }	
   if( document.myForm.party.value == "0" )
   {
	//	 alert( "Määra oma parteiline kuuluvus!" );
		 $(document.myForm.party).after("<div class=\"error\">Määra oma parteiline kuuluvus!</div>")
		 document.myForm.party.focus() ;
		 return false;
   }
   if( document.myForm.piirkond.value == "0" )
   {
	//	 alert( "Vali oma kandideerimispiirkond!" );
		 $(document.myForm.piirkond).after("<div class=\"error\">Vali oma kandideerimispiirkond!</div>")
		 document.myForm.piirkond.focus() ;
		 return false;
   }


   var haridus_len = document.myForm.haridus.value.length;
   if( document.myForm.haridus.value == "" || lastname_len > 50 || lastname_len < 3 )
   {
	//	 alert( "Sisesta oma haridus! (3...50 märki)" );
		 $(document.myForm.haridus).after("<div class=\"error\">Sisesta oma haridus! (3...50 märki)</div>")
		 document.myForm.haridus.focus() ;
		 return false;
   }
   var academicdegree_len = document.myForm.academicdegree.value.length;
   if( document.myForm.academicdegree.value == "" || academicdegree_len > 50 || academicdegree_len < 3 )
   {
	//	 alert( "Sisesta oma akadeemiline kraad! (3...50 märki)" );
		 $(document.myForm.academicdegree).after("<div class=\"error\">Sisesta oma akadeemiline kraad! (3...50 märki)</div>")
		 document.myForm.academicdegree.focus() ;
		 return false;
   }
   var occupation_len = document.myForm.occupation.value.length;
   if( document.myForm.occupation.value == "" || occupation_len > 50 || occupation_len < 3 )
   {
	//	 alert( "Sisesta oma elukutse! (3...50 märki)" );
	     $(document.myForm.occupation).after("<div class=\"error\">Sisesta oma elukutse! (3...50 märki)</div>")
		 document.myForm.occupation.focus() ;
		 return false;
   }	
   var work_len = document.myForm.work.value.length;
   if( document.myForm.work.value == "" || work_len > 30 || work_len < 3)
   {
	//	 alert( "Sisesta oma töökoht! (3...30 märki)" );
	     $(document.myForm.work).after("<div class=\"error\">Sisesta oma töökoht! (3...30 märki)</div>")
		 document.myForm.work.focus() ;
		 return false;
   }	
   
   var phone_len = document.myForm.phone.value.length;  
   if( document.myForm.phone.value == "" ||
           isNaN( document.myForm.phone.value ) ||
           phone_len < 4 || phone_len > 12 )
   {
	//	alert( "Sisesta oma telefoninumber (4...12 numbrit)" );
		$(document.myForm.phone).after("<div class=\"error\">Sisesta oma telefoninumber (4...12 numbrit)</div>")
		document.myForm.phone.focus() ;
		return false;
   }

   
   if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm.email.value)){
	//	 alert("Sisesta korrektne meiliaadress!")
         $(document.myForm.email).after("<div class=\"error\">Sisesta korrektne meiliaadress!</div>")
	     document.myForm.email.focus() ;
		 return (false)
		}

	 
   alert( "Andmed on edukalt sisestatud!" );
   return( true );
   
}
//-->
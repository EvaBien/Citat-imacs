

// index : ON CLICK BUTTON SIGNAL --> pop-up appear

// Récupérer l'id dans url
// Envoyer vers api(getsignalementbyid)
// Display signalement (?) display citations

// On click sur les boutons appeler delete, edit signalement, edit citation...






///////// API UPDATE CITATION ////////


//////// API DELETE CITATION /////////


/////// API UPDATE SIGNALEMENT STATUT /////





//// REDIRECTION /////
// document.location.href="./";


/*******************************************************/
/////////////////// GESTION EVENEMENTS /////////////////
/******************************************************/

/////////////////// POP UP SIGNALEMENT FORM //////////////////
function signalPopUp(){
  displayCover();
  document.getElementById("pop_signal").style.display = "block";
}


//////////////////// MODIF CITATION  ///////////////////
      //////////////// POP UP APPEAR ///////////
function displayEditCitation(){
	displayCover();
 	document.getElementById("pop-edit").style.display = "block";
}

//////////////////// DELETE CITATION  ///////////////////
      //////////////// POP UP APPEAR ///////////
function displayDelCitation(){
	displayCover();
 	document.getElementById("pop-delete").style.display = "block";
}

//////////////// POP UP VANISH ///////////////
function cancelPopUpAdmin(){
	document.getElementById("cover").style.display = "none";
 	document.getElementById("pop-delete").style.display = "none";
  	document.getElementById("pop-edit").style.display = "none";
}

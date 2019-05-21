

// index : ON CLICK BUTTON SIGNAL --> pop-up appear

// Récupérer l'id dans url
// Envoyer vers api(getsignalementbyid)
// Display signalement (?) display citations

// On click sur les boutons appeler delete, edit signalement, edit citation...

/****************************************************/
////////////////////// APPELS API ////////////////////
/****************************************************/


//////// API GET TYPE SIGNALEMENT //////
function SignalById(){

}


///////// API UPDATE CITATION ////////
function updateCitation(){

}

//////// API DELETE CITATION /////////
function deleteCitation(idCitation){

}

/////// API UPDATE SIGNALEMENT STATUT /////
function updateSignalStatus(signalId){

}




//// REDIRECTION /////
// document.location.href="./";










/*******************************************************/
/////////////////// GESTION EVENEMENTS /////////////////
/******************************************************/

//////////////////////////////////////////////////////////////////
////////////////////////// AU CHARGEMENT ////////////////////////

document.addEventListener('DOMContentLoaded', function(){
  SignalById();
});




//////////////////////////////////////////////////////////////////
//////////////////////// ON CLICK BUTTONS /////////S//////////////


////////////////////// DELETE VALID BUTTON ///////////////////////


/////////////////////// UPDATE VALID BUTTON //////////////////////


////////////////////// NOTHING  VALID BUTTON /////////////////////



//////////////////////////////////////////////////////////////////
///////////////////////////// POP UP ////////////////////////////


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

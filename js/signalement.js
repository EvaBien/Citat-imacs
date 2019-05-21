

// index : ON CLICK BUTTON SIGNAL --> pop-up appear

// Récupérer l'id dans url
// Envoyer vers api(getsignalementbyid)
// Display signalement (?) display citations

// On click sur les boutons appeler delete, edit signalement, edit citation...

/****************************************************/
////////////////////// APPELS API ////////////////////
/****************************************************/

///// API CREATE SIGNALEMENT //////
function createSignal(){

  alert("Votre signalement a bien été envoyé ! \n Redirection...");
}




//////// API GET TYPE SIGNALEMENT //////
function SignalById(){


}


///////// API UPDATE CITATION ////////
function updateCitation(){

alert("Citation éditée ! \n Redirection...");
document.location.href="./";
}

//////// API DELETE CITATION /////////
function deleteCitation(idCitation){

alert("Citation supprimée ! \n Redirection...");
document.location.href="./";
}

/////// API UPDATE SIGNALEMENT STATUT /////
function updateSignalStatus(signalId){

alert("Aucune modification appliquée ! \n Redirection...");
document.location.href="./";
}












/*******************************************************/
/////////////////// GESTION EVENEMENTS /////////////////
/******************************************************/

//////////////////////////////////////////////////////////////////
////////////////////////// AU CHARGEMENT ////////////////////////

document.addEventListener('DOMContentLoaded', function(){
  SignalById();
});

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

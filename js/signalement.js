

// index : ON CLICK BUTTON SIGNAL --> pop-up appear

// Récupérer l'id dans url
// Envoyer vers api(getsignalementbyid)
// Display signalement (?) display citations

// On click sur les boutons appeler delete, edit signalement, edit citation...

//////////////////// MODIF CITATION  ///////////////////
      //////////////// POP UP APPEAR ///////////


      //////////////// POP UP VANISH ///////////


      /////////// VALID UPDATE CITATION ////////

//////////////////// DELETE CITATION  ///////////////////
      //////////////// POP UP APPEAR ///////////


      //////////////// POP UP VANISH ///////////


      /////////// VALID DELETE CITATION ////////


function signalPopUp(){
  displayCover();
  document.getElementById("pop_signal").style.display = "block";
}

/*******************************************************/
/////////////////// GESTION EVENEMENTS /////////////////
/******************************************************/

///////////////////////// POP UP //////////////////////
function displayEditCitation(){
	displayCover();
 	document.getElementById("pop-edit").style.display = "block";
}

function displayDelCitation(){
	displayCover();
 	document.getElementById("pop-delete").style.display = "block";
}

function cancelPopUpAdmin(){
	document.getElementById("cover").style.display = "none";
 	document.getElementById("pop-delete").style.display = "none";
  	document.getElementById("pop-edit").style.display = "none";
}

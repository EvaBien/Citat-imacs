include('../Route/routeur.php');


// index : ON CLICK BUTTON SIGNAL --> pop-up appear


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

//admin : POP UP

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

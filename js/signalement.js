// index : ON CLICK BUTTON SIGNAL --> pop-up appear

function signalPopUp(){
  displayCover();
  document.getElementById("pop_signal").style.display = "block";
}

//admin : POP UP

function editCitation(){
	displayCover();
 	document.getElementById("pop-edit").style.display = "block";
}

function delCitation(){
	displayCover();
 	document.getElementById("pop-delete").style.display = "block";
}

function cancelPopUpAdmin(){
	document.getElementById("cover").style.display = "none";
 	document.getElementById("pop-delete").style.display = "none";
  	document.getElementById("pop-edit").style.display = "none";
}
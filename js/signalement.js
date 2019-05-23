

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
  event.preventDefault();

  let formData = new FormData();
  let data = new Object();

  let url = "./signalements/New";
  let mailSignal = document.getElementById("mailSignal").value;
  let typeSignal = document.getElementById("type_signalement").value;
  let messageSignal = document.getElementById("messageSignal").value;
  let idCitationSignal = document.getElementById("idCitationSignal").value;

  data['url']= url;
  formData.append('url',url);

  data['typeSignal'] = typeSignal;
  formData.append('typeSignal',typeSignal);
  
  data['mailSignal'] = mailSignal;
  formData.append('mailSignal',mailSignal);

  data['messageSignal'] = messageSignal;
  formData.append('messageSignal',messageSignal);

  data['idCitationSignal'] = idCitationSignal;
  formData.append('idCitationSignal',idCitationSignal);

  data = JSON.stringify(data);
  formData.append('getData',data);


  fetch("./api/Route/routeur.php", {
 		method: "POST",
 		body: formData})
     .then( response => response.json() )
 		.then( data => {
      alert("Votre signalement a bien été envoyé ! \n Redirection...");
       // location.reload();
     })
     .catch( error => {
       window.alert(error);
 		});
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
function signalPopUp(idCitation){
  let blockSignal = document.getElementById("formSignal");
  let inputIdCitation = document.createElement("input");
  inputIdCitation.setAttribute('type','hidden');
  inputIdCitation.setAttribute('value',idCitation);
  inputIdCitation.setAttribute('id',idCitationSignal);
  blockSignal.appendChild(inputIdCitation);
  console.log(inputIdCitaiton);
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

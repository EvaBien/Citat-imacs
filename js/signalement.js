

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
      console.log(data);
      alert("Votre signalement a bien été envoyé ! \n Redirection...");
     })
     .catch( error => {
       window.alert(error);
 		});
}


//////// API GET TYPE SIGNALEMENT //////
function getSignalById(idSignal){
  let formDama = new FormData();
  let data = new Object();

  data['url'] = './signalements/Id';
  formData.append("url",'./signalements/Id');

  data['idSignal'] = idSignal;
  formData.append("idSignal", idSignal);

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php', {
    method: "POST",
    body: formData})
  .then(res => res.json())
  .then(function(data){
  displaySignal(data);
  inputForm(data);
  })
  .catch( error => {
    window.alert(error);
  })
  }


///////// API UPDATE CITATION ////////
function updateCitation(){

alert("Citation éditée ! \n Redirection...");
// document.location.href="./";
}

//////// API DELETE CITATION /////////
function deleteCitation(idCitation, idSignal){

alert("Citation supprimée ! \n Redirection...");
// document.location.href="./";
}

/////// API UPDATE SIGNALEMENT STATUT /////
function updateSignalStatus(idSignal){

alert("Aucune modification appliquée ! \n Redirection...");
// document.location.href="./";
}

function AllTypesAuteurSignal(){
  let formData = new FormData();
  let data = new Object();
  data['url'] = './typesAuteur/All';
  formData.append("url", './typesAuteur/All');

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php', {
    method: "POST",
    body: formData}) // chooseRoute() est une fonction en php, qui est dans routeur.php
  .then(res => res.json())
  .then(function(data){
    displayTypesAuteurSignal(data);
  })
  .catch( error => {
    window.alert(error);
  })
}

/*******************************************************/
/////////////////// GESTION EVENEMENTS /////////////////
/******************************************************/

//////////////////////////////////////////////////////////////////
////////////////////////// AU CHARGEMENT ////////////////////////

document.addEventListener('DOMContentLoaded', function(){
  urlData = window.location.search;
  idSignal = urlData.substr(urlData.length-1);
  console.log(urlData);
  console.log(idSignal);
  getSignalById(idSignal);
  AllTypesAuteurSignal();
});



//////////////////////////////////////////////////////////////////
////////////////////////// DISPLAY SIGNAL ////////////////////////

function displaySignal(dataSignal){

 let signalTextBlock = document.getElementById("messageSignalAdmin");
 signalTextBlock.appendChild("\""+dataSignal['messageSignalement']+"\"");

 let signalCitationBlock = document.getElementById("citationSignal");
 signalCitationBlock.setAttribute('idCitation', dataSignal['citation']['idCitation']);

 let info_block = document.createElement("div");
 info_block.setAttribute('class', 'infos-citation');
 signalCitationBlock.appendChild(info_block);

 //// Remplissage premier DIV : info_block ////

 // On remplit les tags //
 let tags_block = document.createElement("ul");
 tags_block.setAttribute('class','list-tags');

   dataSignal['citation']['tags'].forEach(tag=>{
   let tagnom=tag['nomTag'];
   let one_tag = document.createElement("li");
   one_tag.innerHTML = tagnom;
   tags_block.appendChild(one_tag);
 });

 // On remplit la date //
 let date = dataSignal['citation']['dateCitation'];
 let date_block = document.createElement("p");
 date_block.setAttribute('class','quote_date');
 date_block.innerHTML = date;

 // On remplit la citation //
 let contenu = "\""+dataSignal['citation']['contenuCitation']+"\"";
 let quote_block = document.createElement("p");
 quote_block.setAttribute('class','quote');
 quote_block.innerHTML = contenu;

 // On remplit l'auteur + Le type //
 let auteur = dataSignal['citation']['auteurCitation']+" - "+dataSignal['citation']['typeAuteur'];
 let author_block = document.createElement("p");
 author_block.setAttribute('class','quote_author');
 author_block.innerHTML = auteur;

 // On met tout dans le div  info_block
 info_block.appendChild(tags_block);
 info_block.appendChild(date_block);
 info_block.appendChild(quote_block);
 info_block.appendChild(author_block);

}

function inputForm(dataSignal){
  let idBlock = document.getElementById("citationEditId");
  idBlock.setAttribute('value',dataSignal['citation']['idCitation']);

  let auteurBlock = document.getElementById("citationEditAuteur");
  auteurBlock.setAttribute('value',dataSignal['citation']['auteurCitation']);

  let textBlock = document.getElementById("citationEditText");
  textBlock.setAttribute('value',dataSignal['citation']['contenuCitation']);

  let typesValue = document.querySelectorAll("option[name='type_auteur_signal']");
  typesValue.forEach(function(option){
    if(option.value == dataSignal['citation']['typeAuteur']){
        option.setAttribute('selected',"selected");
    }
  });

}

//////////////////////////////////////////////////////////////////
///////////// FONCTION AFFICHE TYPES AUTEUR POP UP ///////////// - FAIT
function displayTypesAuteurSignal(dataTypes){

  let authorFormBlock = document.getElementById("type_auteur_signal");

  dataTypes.forEach(author => {
    let one_author = document.createElement("option");
    one_author.setAttribute('value',author['idTypeAuteur']);
    one_author.setAttribute('name',"type_auteur_signal");
    one_author.innerHTML = author['nomTypeAuteur'];

    authorFormBlock.appendChild(one_author);
  });
}

//////////////////////////////////////////////////////////////////
///////////////////////////// POP UP ////////////////////////////


/////////////////// POP UP SIGNALEMENT FORM //////////////////
function signalPopUp(idCitation){
  let blockSignal = document.getElementById("formSignal");
  let inputIdCitation = document.createElement("input");
  inputIdCitation.setAttribute('type','hidden');
  inputIdCitation.setAttribute('value',idCitation);
  inputIdCitation.setAttribute('id',"idCitationSignal");
  blockSignal.appendChild(inputIdCitation);
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

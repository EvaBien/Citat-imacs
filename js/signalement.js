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
      window.location.href="./index.html";
     })
     .catch( error => {
       window.alert(error);
 		});
}


//////// API GET TYPE SIGNALEMENT //////
function getSignalById(idSignal){
  let formData = new FormData();
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
  event.preventDefault();
  urlData = window.location.search;
  idSignal = urlData.substr(urlData.length-1);

  let formData = new FormData();
  let data = new Object();
  var url = "./citations/Update";

  let idCitation = document.getElementById("citationEditId").value;
  let auteurCitation = document.getElementById("citationEditAuteur").value;
  let contenuCitation = document.getElementById("citationEditText").value;
  let idTypeAuteur = document.getElementById("type_auteur_signal").value;
  let password = document.getElementById("pass").value;

  data['url'] = url;
  formData.append('url',url);

  data['idCitation'] = idCitation;
  formData.append('idCitation',idCitation);

  data['contenuCitation'] = contenuCitation;
  formData.append('contenuCitation',contenuCitation);

  data['auteurCitation'] = auteurCitation;
  formData.append('auteurCitation',auteurCitation);

  data['idTypeAuteur'] = idTypeAuteur;
  formData.append('idTypeAuteur',idTypeAuteur);

  data['idSignal'] = idSignal;
  formData.append('idSignal',idSignal);

  data['password'] = password;
  formData.append('password',password);

  data = JSON.stringify(data);
  formData.append('getData',data);


  fetch('./api/Route/routeur.php',{
    method: "POST",
    body: formData})
  .then(res => res.json())
    .then(function(data){
      if (data ==json_encode("Password Correct - Citation updated")){
     alert("Citation éditée ! \n Redirection...");
     window.location.href="./index.html";
   } else  {
     alert("Mot de passe incorrect ! ");
   }
   })
    .catch( error => {
      window.alert(error);
    })
}

//////// API DELETE CITATION /////////
function deleteCitation(){
  event.preventDefault();
  urlData = window.location.search;
  idSignal = urlData.substr(urlData.length-1);

  let formData = new FormData();
  let data = new Object();
  var url = "./citations/Delete";

  let idCitation = document.getElementById("citationDeleteId").value;
  let password = document.getElementById("pass2").value;

  data['url'] = url;
  formData.append('url',url);

  data['idCitation'] = idCitation;
  formData.append('idCitation',idCitation);

  data['idSignal'] = idSignal;
  formData.append('idSignal',idSignal);

  data['password'] = password;
  formData.append('password',password);

  data = JSON.stringify(data);
  formData.append('getData',data);


  fetch('./api/Route/routeur.php',{
    method: "POST",
    body: formData})
  .then(res => res.json())
    .then(function(data){
    if (data == json_encode("Password Correct - Citation Deleted")){
     alert("Citation supprimée ! \n Redirection...");
     window.location.href="./index.html";
   } else {
     alert("Mot de passe incorrect ! ");
   }
   })
    .catch( error => {
      window.alert(error);
    })
}

/////// API UPDATE SIGNALEMENT STATUT /////
function nothingCitation(){
  event.preventDefault();

  urlData = window.location.search;
  idSignal = urlData.substr(urlData.length-1);

  let formData = new FormData();
  let data = new Object();
  var url = "./signalements/Nothing";

  let idCitation = document.getElementById("citationNothingId").value;
  let password = document.getElementById("pass3").value;

  data['url'] = url;
  formData.append('url',url);

  data['idCitation'] = idCitation;
  formData.append('idCitation',idCitation);

  data['idSignal'] = idSignal;
  formData.append('idSignal',idSignal);

  data['password'] = password;
  formData.append('password',password);

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php',{
    method: "POST",
    body: formData})
  .then(res => res.json())
    .then(function(data){
    if(data == json_encode("Password Correct - Citation non modifiée ! ")){
     alert("Aucune modification appliquée ! \n Redirection...");
     window.location.href="./index.html";
   } else {
     alert("Mot de passe incorrect !");
   }
   })
    .catch( error => {
      window.alert(error);
    })
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
    body: formData})
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
  var url = location.pathname;
  urlData = window.location.search;
  if ((url.search("/admin")!=-1) && (urlData.length>4)){
  idSignal = urlData.substr(urlData.length-1);

  AllTypesAuteurSignal();
  getSignalById(idSignal);

} else if ((url.search("/admin")!=-1) && (urlData.length<5)){
  location.href="./";
} else if (url.search("/index.html")!=-1){
    location.href="./";
}
});



//////////////////////////////////////////////////////////////////
////////////////////////// DISPLAY SIGNAL ////////////////////////

function displaySignal(dataSignal){

 let signalTextBlock = document.getElementById("messageSignalAdmin");
 signalTextBlock.innerHTML = "\""+dataSignal[0]['messageSignalement']+"\"";

 let signalCitationBlock = document.getElementById("citationSignal");
 signalCitationBlock.setAttribute('idCitation', dataSignal[0]['citation']['idCitation']);

 let info_block = document.createElement("div");
 info_block.setAttribute('class', 'infos-citation');
 signalCitationBlock.appendChild(info_block);

 //// Remplissage premier DIV : info_block ////

 // On remplit les tags //
 let tags_block = document.createElement("ul");
 tags_block.setAttribute('class','list-tags');

   dataSignal[0]['citation']['tags'].forEach(tag=>{
   let tagnom=tag['nomTag'];
   let one_tag = document.createElement("li");
   one_tag.innerHTML = tagnom;
   tags_block.appendChild(one_tag);
 });

 // On remplit la date //
 let date = dataSignal[0]['citation']['dateCitation'];
 let date_block = document.createElement("p");
 date_block.setAttribute('class','quote_date');
 date_block.innerHTML = date;

 // On remplit la citation //
 let contenu = "\""+dataSignal[0]['citation']['contenuCitation']+"\"";
 let quote_block = document.createElement("p");
 quote_block.setAttribute('class','quote');
 quote_block.innerHTML = contenu;

 // On remplit l'auteur + Le type //
 let auteur = dataSignal[0]['citation']['auteurCitation']+" - "+dataSignal[0]['citation']['typeAuteur'];
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
  let idEditBlock = document.getElementById("citationEditId");
  idEditBlock.setAttribute('value',dataSignal[0]['citation']['idCitation']);

  let idDeleteBlock = document.getElementById("citationDeleteId");
  idDeleteBlock.setAttribute('value',dataSignal[0]['citation']['idCitation']);

  let idNothingBlock = document.getElementById("citationNothingId");
  idNothingBlock.setAttribute('value',dataSignal[0]['citation']['idCitation']);

  let auteurBlock = document.getElementById("citationEditAuteur");
  auteurBlock.setAttribute('value',dataSignal[0]['citation']['auteurCitation']);

  let textBlock = document.getElementById("citationEditText");
  textBlock.setAttribute('value',dataSignal[0]['citation']['contenuCitation']);

  let typesValue = document.getElementsByName("type_auteur_signal");
  let idTypeA = dataSignal[0]['citation']['idTypeAuteur'];

  typesValue.forEach(function(option){
    if(option.value == idTypeA){
        option.setAttribute('selected',true);
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
	displayCoverSignal();
 	document.getElementById("pop-edit").style.display = "block";
}

//////////////////// NOTHING CITATION  ///////////////////
      //////////////// POP UP APPEAR ///////////
function displayNothingCitation(){
	displayCoverSignal();
 	document.getElementById("pop-nothing").style.display = "block";
}

//////////////////// DELETE CITATION  ///////////////////
      //////////////// POP UP APPEAR ///////////
function displayDelCitation(){
	displayCoverSignal();
 	document.getElementById("pop-delete").style.display = "block";
}



//////////////// POP UP VANISH ///////////////
function cancelPopUpAdmin(){
	document.getElementById("coverSignal").style.display = "none";
 	document.getElementById("pop-delete").style.display = "none";
  document.getElementById("pop-edit").style.display = "none";
  document.getElementById("pop-nothing").style.display = "none";
}

function displayCoverSignal(){
  document.getElementById("coverSignal").style.display = "block";
}

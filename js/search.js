/****************************************************/
////////////////////// APPELS API ////////////////////
/****************************************************/

//////////////////// ALL TAGS /////////////////// - FAIT
function AllTags(){
  let formData = new FormData();
  let data = new Object();
  data['url'] = './tags/All';
  formData.append("url", './tags/All');

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php', {
		method: "POST",
		body: formData})
  .then(function(response) { return response.json(); })
  .then(function(data){
    console.log(data);
    displayTagsNav(data);
    displayTagsPop(data);
  })
  .catch( error => {
    window.alert(error);
  })
}

//////////////////// ALL TYPES AUTEUR /////////////////// - FAIT

function AllTypesAuteur(){
  let formData = new FormData();
  let data = new Object();
  data['url'] = './typesAuteur/All';
  formData.append("url", './typesAuteur/All');

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php', {
    method: "POST",
    body: formData}) // chooseRoute() est une fonction en php, qui est dans routeur.php
  .then(function(response) { return response.json(); })
  .then(function(data){
    console.log(data);
    displayTypesAuteurNav(data);
    displayTypesAuteurPop(data);
  })
  .catch( error => {
    window.alert(error);
  })
}

//////////////////// ALL CITATIONS /////////////////// - FAIT
function AllCitations(){
  let formData = new FormData();
  let data = new Object();
  data['url'] = './citations/All';
  formData.append("url",  './citations/All');

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php', {
    method: "POST",
    body: formData})
  .then(function(response) { return response.json(); })
  .then(function(data){
    console.log(data);
    displayCitation(data);
  })
  .catch( error => {
    window.alert(error);
  })
}

//////////////////// ALL TYPES SIGNALEMENT ///////////////////
function  AllTypesSignal(){
  let formData = new FormData();
  let data = new Object();
  data['url'] = './typesSignalement/All';
  formData.append("url",  './typesSignalement/All');

  data = JSON.stringify(data);
  formData.append('getData',data);

  fetch('./api/Route/routeur.php', {
    method: "POST",
    body: formData})
  .then(function(response) { return response.json(); })
  .then(function(data){
    displayTypesSignal(data);
  })
  .catch( error => {
    window.alert(error);
  })
}




/////////////////////// ADD CITATION //////////////////////

function addCitation(){
  event.preventDefault();

  let formData = new FormData();
  let data = new Object();

  let url = "./citation/New";
  let date = new Date();
  let nom_auteur = document.getElementById("auteur_form").value;
  let type_auteur = document.getElementById("type_auteur_form").value;
  let citation = document.getElementById("citation_form").value;
  let tags = document.getSelectorAll("input[name='tag_form']");
  let tagsChecked = new Array();
  tags.forEach(function(checkBtn){
    if(checkBtn.checked){
      tagsChecked.push(checkBtn.value);
    }
  });


    data['url']= url;
    formData.append('url',url);

  if(citation){
    data['contenuCitation'] = citation;
    formData.append("contenuCitation", citation);
  }

  data['dateCitation'] = date;
  formData.append("dateCitation",date);

  if(nom_auteur){
    data['auteurCitation'] = nom_auteur;
    formData.append("auteurCitation", nom_auteur);
  }

  if(type_auteur){
    data['idTypeAuteur'] = type_auteur;
    formData.append("idTypeAuteur", type_auteur);
  }

  if(tagsChecked){
    data['tagsCitation'] = tagsChecked;
    formData.append("tagsCitation",tagsChecked);
  }

  data = JSON.stringify(data);
 formData.append('postData',data);
 console.log(data);

 fetch("./api/Route/routeur.php", {
		method: "POST",
		body: formData})
    .then( response => response.json() )
		.then( data => {
      alert("Citation créée ! \n Redirection...");
      window.location.reload();
    })
    .catch( error => {
      window.alert(error);
		});
}



/*******************************************************/
/////////////////// GESTION EVENEMENTS /////////////////
/******************************************************/

//////////////////// AU CHARGEMENT /////////////////// - FAIT

document.addEventListener('DOMContentLoaded', function(){
  AllCitations();
  AllTags();
  AllTypesAuteur();
  AllTypesSignal();
});

//////////////////////////////////////////////////////////////////
//////////////// FONCTION AFFICHE CITATIONS //////////////// - FAIT

function displayCitation(dataCitation){
  var data = JSON.parse(dataCitation);
  data.forEarch(citation => {
    let block = document.getElementById("block_citations");
    let section_block = document.createElement("section");
    section_block.setAttribute('class', 'one_citation');
    section_block.setAttribute('idCitation', citation['idCitation']);

    let info_block = document.createElement("div");
    info_block.setAttribute('class', 'infos-citation');

    let commands_block = document.createElement("div");
    commands_block.setAttribute('class', 'commands-citation');

    //// Remplissage premier DIV : info_block ////

    // On remplit les tags //
    let tags_block = document.createElement("ul");
    tags_block.setAttributee('class','list-tags');

    citation.tags.forEarch(tag=>{
      let tagnom=tag.nomTag;
      let one_tag = document.createElement("li");
      one_tag.innerHTML(tagnom);
      tags_block.appendChild(one_tag);
    });

    // On remplit la date //
    let date = citation.dateCitation;
    let date_block = document.createElement("p");
    date_block.setAttribute('class','quote_date');
    date_block.innerHTML(date);

    // On remplit la citation //
    let contenu = citation.contenuCitation;
    let quote_block = document.createElement("p");
    quote_block.setAttribute('class','quote');
    quote_block.innerHTML(contenu);

    // On remplit l'auteur + Le type //
    let auteur = citation['auteurCitation']+" - "+citation['typeAuteur']['nomTypeAuteur'];
    let author_block = document.createElement("p");
    author_block.setAttribute('class','quote_author');
    author_block.innerHTML(auteur);

    // On met tout dans le div  info_block
    info_block.appendChild(tags_block);
    info_block.appendChild(date_block);
    info_block.appendChild(quote_block);
    info_block.appendChild(author_block);

    //// Remplissage second DIV : commands_block ////

    // On remplit bouton like //
    let liker_block = document.createElement("button");
    liker_block.setAttribute('class','like-button');
    liker_block.setAttribute('idCitation',citation['idCitation']);
    liker_block.setAttribute('onclick','likeCitation()');
    liker_block.innerHTML("J\'aime");

    // On remplit le nombre de like //
    let nblikes = citation.likesCitation;
    let likes_block = document.createElement("p");
    likes_block.setAttribute('class','number_likes');
    likes_block.innerHTML(nbLikes);

    // On remplit le bouton signalement //
    let signal_block = document.createElement("a");
    signal_block.setAttribute('onclick','signalPopUp()');
    signal_block.setAttribute('idCitation',citation['idCitation']);
    signal_block.innerHTML("Signaler un problème");

    // On met le tout dans le div command_block //
    commands_block.appendChild(liker_block);
    commands_block.appendChild(likes_block);
    commands_block.appendChild(signal_block);

    /// On ajoute la section au block global ///
    block.appendChild(section_block);
  });
}

//////////////////////////////////////////////////////////////////
/////////////////// FONCTION AFFICHE TAGS NAV /////////////// - FAIT
function displayTagsNav(dataTags){
  var data = JSON.parse(dataTags);
  let count=0; // Je met un compteur pour savoir à quel moment je passe dans le second block
  var lenght = Object.keys(dataTags).length ; // Je récupère la taille du json
  var middle = lenght/2;
  let tagsBlock = document.getElementById("Tags");
  let tagsLeft = "section class=\"left_tight\"></section>";
  let tagsRight = "section class=\"left_tight\"></section>";
  let all_tag = "<div class=\"input_display\"> <input type=\"checkbox\" id=\"checkbox0\" name=\"navTagsCheckbox\" class=\"tag_checkbox\" value=\"All\" checked> <label for = \"checkbox0\" class=\"tag\" onclick=\"handleAll()\">All</label></div>"

  data.forEarch(tag => {
    let one_tag = "<div class=\"input_display\"> <input type=\"checkbox\" id=\"checkbox"+tag['idTag']+" \" name=\"navTagsCheckbox\" class=\"tag_checkbox\" value=\""+tag['nomTag']+"\" checked> <label for = \"checkbox"+tag['idTag']+"\" class=\"tag\" onclick=\"checkedButAll()\">"+tag['nomTag']+"</label></div>"

    if (count<middle){
      tagsLeft.appendChild(one_tag);
    } else {
      tagsRight.appendChild(one_tag);
    }
    count = count+1;
  });

  // On met le tout dans le block principal
  tagsBlock.appendChild(tagsLeft);
  tagsBlock.appendChild(tagsRight);
}

//////////////////////////////////////////////////////////////////
///////////// FONCTION AFFICHE TYPES AUTEUR NAV ///////////// - FAIT
function displayTypesAuteurNav(dataTypes){
  var data = JSON.parse(dataTypes);
  var count=0; // Je met un compteur pour savoir à quel moment je passe dans le second block
  var lenght = Object.keys(dataTypes).length ; // Je récupère la taille du json
  var middle = lenght/2;
  let authorBlock = document.getElementById("author");

  let authorLeft = "section class=\"left_tight\"></section>";
  let authorRight = "section class=\"left_tight\"></section>";
  let all_author = "<div class=\"input_display\"> <input type=\"checkbox\" id=\"authorCheckbox0\" name=\"navAuthorCheckbox\" class=\"tag_checkbox\" value=\"All\" checked onclick=\"handleAllAuthor()> <label for = \"authorCheckbox0\" class=\"tag\" \">All</label></div>"

  data.forEarch(author => {
    let one_author = "<div class=\"input_display\"> <input type=\"checkbox\" id=\"authorCheckbox"+author['idTypeAuteur']+" \" name=\"navAuthorCheckbox\" class=\"tag_checkbox\" value=\""+author['nomTypeAuteur']+"\" checked onclick=\"checkedButAllAuthor()\"> <label for = \"authorCheckbox"+author['idTypeAuteur']+"\" class=\"tag\">"+author['nomTypeAuteur']+"</label></div>"

    if (count<middle){
      authorLeft.appendChild(one_author);
    } else {
      authorRight.appendChild(one_author);
    }
    count = count+1;
  });

  // On met le tout dans le block principal
  tagsBlock.appendChild(tagsLeft);
  tagsBlock.appendChild(tagsRight);
}


//////////////////////////////////////////////////////////////////
///////////// FONCTION AFFICHE TYPES AUTEUR POP UP ///////////// - FAIT
function displayTypesAuteurPop(dataTypes){
  var data = JSON.parse(dataTypes);

  let authorFormBlock = document.getElementById("type_auteur_form");

  data.forEarch(author => {
    let one_author = "<option value=\""+author['idTypeAuteur']+" \">"+author['nomTypeAuteur']+"</option>"

    authorFormBlock.appendChild(one_author);
  });
}


//////////////////////////////////////////////////////////////////
/////////////////// FONCTION AFFICHE TAGS POP UP /////////////// - FAIT
function displayTagsPop(dataTags){
  var data = JSON.parse(dataTags);

  let tagsFormBlock = document.getElementById("tag_form");

  data.forEarch(tag => {
    let one_tag = "<input type=\"checkbox\" id=\"popup_checkbox"+tag['idTag']+"\" class=\"tag_checkbox\" name=\"tag_form\" value=\""+tag['idTag']+"\"><label for=\"popup_checkbox"+tag['idTag']+"\" class=\"tag\">"+tag['nomTag']+"</label>"

    tagsFormBlock.appendChild(one_tag);
  });
}


//////////////////////////////////////////////////////////////////
/////////////////// FONCTION AFFICHE TYPES SIGNAL ///////////////
function displayTypesSignal(dataTypes){
  var data = JSON.parse(dataTypes);

  let typesFormBlock = document.getElementByName("type_signalement");

  data.forEarch(type => {
    let one_type = "<option value=\""+type['nomTypeSignalement']+"\">"+type['nomTypeSignalement']+"</option>"
    typesFormBlock.appendChild(one_type);
  });
}

//////////////////////////////////////////////////////////////////
//////////////////// GESTION DES CHECKED /////////////////// - FAIT

function handleAll() {
  /*C'est le statut avant qu'on clique qui est pris en compte*/
  if(!document.getElementById("checkbox0").checked){
    var items = document.getElementsByName('navTagsCheckbox');
    for (var i = 1; i < items.length; i++) {
      if (items[i].type == 'checkbox'){
      items[i].checked = false;
    }
    }
  }
}

function checkedButAll(){
  if(document.getElementById("checkbox0").checked){
    var items = document.getElementsByName('navTagsCheckbox');
    items[0].checked = false;
  }
}

function handleAllAuthor() {
  all = document.getElementsByName('navAuthorCheckbox')[0];
  if(all.checked){
    var items = document.getElementsByName('navAuthorCheckbox');
    for (var i = 1; i < items.length; i++) {
      if (items[i].type == 'checkbox')
      items[i].checked = false;
    }
  }
}

function checkedButAllAuthor(){
  all = document.getElementsByName('navAuthorCheckbox')[0];
  if(all.checked){
    all.checked = false;
  }
}



//////////////////////////////////////////////////////////////////
/////////////////////// CITATION (ADD) //////////////////////

//////////////// POP UP APPEAR /////////// - FAIT
function addCitationPopUp(){
  displayCover();
  document.getElementById("pop_new_citation").style.display = "block";
}

function displayCover(){
  document.getElementById("cover").style.display = "block";
}

//////////////// POP UP VANISH /////////// - FAIT
function cancelPopUp(){
  document.getElementById("cover").style.display = "none";
  document.getElementById("pop_new_citation").style.display = "none";
  document.getElementById("pop_signal").style.display = "none";
}


/////////// LIKES CITATIONS //////// - FAIT
function likeCitation(){ // A modifier ?

  let divId=button.getAttribut(idCitation); // On récupère l'id
  let currentLikes;
  let newValue;

  let formDataGet = new FormData();
  let dataGet = new Object();
  dataGet['url'] = './typesAuteur/All';
  formDataGet.append("url", './typesAuteur/All');
  dataGet['idCitation'] = divId;
  formDataGet.append("idCitation", divId);

  dataGet = JSON.stringify(dataGet);
  formDataGet.append('getData',dataGet);

  /// On get la valeur actuelle des likes ///
  fetch('./api/Route/routeur.php',  {
		method: "POST",
		body: formDataGet})
  .then(function(response) { return response.json(); })
  .then(function(data){
    currentLikes = data['likes'];
  })
  .catch( error => {
    window.alert(error);
  })


  button = event.target;
  if(button.classList.contains('clicked')){
    newValue=currentLikes-1;
    button.classList.remove("clicked");
  }else{
    newValue=currentLikes-1;
    button.classList.add("clicked");
  }

  /// On update les likes ///

  let formDataUpd = new FormData();
  let dataUpd = new Object();
  dataUpd['likes'] = newValue;
  formDataUpd.append("likes", newValue);
  dataUpd['idCitation'] = divId;
  formDataUpd.append("idCitation", divId);

  dataUpd = JSON.stringify(dataUpd);
  formDataUpd.append('getData',dataUpd);

  fetch('./api/Route/routeur.php', {
		method: "PUT",
		body: formDataUpd})
  .then(function(response) { return response.json(); })
  .catch( error => {
    window.alert(error);
  })

  /// On get la nouvelle valeur dans la BDD directement ///
  fetch('./api/Route/routeur.php',  {
		method: "POST",
		body: formDataGet})
  .then(function(response) { return response.json(); })
  .then(function(data){
    currentLikes = data['likes'];
  })
  .catch( error => {
    window.alert(error);
  })

  likeDiv = button.parentNode.children[1];
  likeDiv.innerHTML = currentLikes;
}


//////////////////////////////////////////////////////////////////
///////////////// CRITERES DE RECHERCHE - CAS ////////////////

document.getElementById('valid_search').onclick = event => {
	event.preventDefault();
        keywordForm = document.getElementById("input[name='inputKeyword']").value;
        tagsForm = document.querySelectorAll("input[name='navTagsCheckbox']");
         let tagsChecked = new Array();
          tagsForm.forEach(function(checkBtn) {
              if (checkBtn.checked) {
	                 tagsChecked.push(JSON.stringify(checkBtn.value));
                   }
                  });
        typesAuteurForm = document.querySelectorAll("input[name='navAuthorCheckbox']");
         let typesAuteurChecked = new Array();
          typesAuteurForm.forEach(function(radioBtn) {
              if (radioBtn.checked) {
	                 typesAuteurChecked.push(JSON.stringify(radioBtn.value));
                   }
                  });

        let formData = new FormData();
        let data = new Object();

        data['keyWord'] = keywordForm;
        formData.append("keyword",keywordForm );
        data['tags'] = tagsChecked;
        formData.append("tags", tagsChecked);
        data['typesAuteur'] = typesAuteurChecked;
        formData.append("typesAuteur", typesAuteurChecked);

        var url;

        if ((keywordForm.length > 0) && (tagsChecked.length > 0) && (typesAuteurChecked.length > 0)){
          url = './citations/Allfactors';
        } else if ((keywordForm.length > 0) && !(tagsChecked.length > 0) && !(typesAuteurChecked.length > 0)){
          url = './citations/Keyword';
        } else if (!(keywordForm.length > 0) && (tagsChecked.length > 0) && !(typesAuteurChecked.length > 0)){
          url = './citations/Tags';
        } else if (!(keywordForm.length > 0) && !(tagsChecked.length > 0) && (typesAuteurChecked.length > 0)){
          url = './citations/Typesauteur';
        } else if ((keywordForm.length > 0) && (tagsChecked.length > 0) && !(typesAuteurChecked.length > 0)){
          url = './citations/TagsKeyword';
        } else if ((keywordForm.length > 0) && !(tagsChecked.length > 0) && (typesAuteurChecked.length > 0)){
          url = './citations/TypesauteurKeyword';
        } else if (!(keywordForm.length > 0) && (tagsChecked.length > 0) && (typesAuteurChecked.length > 0)){
          url = './citations/TypesauteurTags';
        } else { // Aucun ou que des all
          url = './citations/All'; // Mon url
        }

        data['url'] = url;
        formData.append("url", url);


        data = JSON.stringify(data);
        formData.append('getData',data);

          fetch('./api/Route/routeur.php',{
            method: "POST",
            body: formData})
          .then(function(response) { return response.json(); })
            .then(displayCitation(data))
            .catch( error => {
              window.alert(error);
            })
  }

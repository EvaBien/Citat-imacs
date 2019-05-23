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
  .then(res => res.json())
  .then(function(data){
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
  .then(res => res.json())
  .then(function(data){
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
  .then(res => res.json())
  .then(function(data){
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
  .then(res => res.json())
  .then(function(data){
    displayTypesSignal(data);
  })
  .catch( error => {
    window.alert(error);
  })
}




/////////////////////// ADD CITATION //////////////////////

function AddCitation(){
  event.preventDefault();

  let formData = new FormData();
  let data = new Object();

  let url = "./citations/New";
  let nom_auteur = document.getElementById("auteur_form").value;
  let type_auteur = document.getElementById("type_auteur_form").value;
  let citation = document.getElementById("citation_form").value;
  let tags = document.querySelectorAll("input[name='tag_form']");
  let tagsChecked = new Array();
  tags.forEach(checkBtn => {
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
 formData.append('getData',data);

 fetch("./api/Route/routeur.php", {
		method: "POST",
		body: formData})
    .then( response => response.json() )
		.then( data => {
      window.alert("Citation créée ! \n Redirection...");
      location.reload();
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

  let block = document.getElementById("block_citations");
  block.innerHTML="";

  dataCitation.forEach(citation => {
    let section_block = document.createElement("section");
    section_block.setAttribute('class', 'one_citation');
    section_block.setAttribute('idCitation', citation['idCitation']);

    let info_block = document.createElement("div");
    info_block.setAttribute('class', 'infos-citation');
    section_block.appendChild(info_block);

    let commands_block = document.createElement("div");
    commands_block.setAttribute('class', 'commands-citation');
    section_block.appendChild(commands_block);


    //// Remplissage premier DIV : info_block ////

    // On remplit les tags //
    let tags_block = document.createElement("ul");
    tags_block.setAttribute('class','list-tags');

      citation['tags'].forEach(tag=>{
      let tagnom=tag['nomTag'];
      let one_tag = document.createElement("li");
      one_tag.innerHTML = tagnom;
      tags_block.appendChild(one_tag);
    });

    // On remplit la date //
    let date = citation['dateCitation'];
    let date_block = document.createElement("p");
    date_block.setAttribute('class','quote_date');
    date_block.innerHTML = date;

    // On remplit la citation //
    let contenu = "\""+citation['contenuCitation']+"\"";
    let quote_block = document.createElement("p");
    quote_block.setAttribute('class','quote');
    quote_block.innerHTML = contenu;

    // On remplit l'auteur + Le type //
    let auteur = citation['auteurCitation']+" - "+citation['typeAuteur'];
    let author_block = document.createElement("p");
    author_block.setAttribute('class','quote_author');
    author_block.innerHTML = auteur;

    // On met tout dans le div  info_block
    info_block.appendChild(tags_block);
    info_block.appendChild(date_block);
    info_block.appendChild(quote_block);
    info_block.appendChild(author_block);

    //// Remplissage second DIV : commands_block ////

    // On remplit bouton like //
    let liker_block = document.createElement("button");
    liker_block.setAttribute('class','like-button');
    liker_block.setAttribute('idcitation',citation['idCitation']);
    liker_block.setAttribute('onclick','likeCitation()');
    liker_block.innerHTML = "J\'aime" ;

    // On remplit le nombre de like //
    let nblikes = citation['likesCitation'];
    let likes_block = document.createElement("p");
    likes_block.setAttribute('class','number_likes');
    likes_block.innerHTML = nblikes;

    // On remplit le bouton signalement //
    let signal_block = document.createElement("a");
    signal_block.setAttribute('onclick','signalPopUp()');
    signal_block.setAttribute('idCitation',citation['idCitation']);
    signal_block.innerHTML = "Signaler un problème";

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
  // var data = JSON.parse(dataTags);
  let count=0; // Je met un compteur pour savoir à quel moment je passe dans le second block
  var lenght = Object.keys(dataTags).length ; // Je récupère la taille du json
  var middle = lenght/2;
  let tagsBlock = document.getElementById("Tags");
  let tagsLeft = document.createElement("section");
  tagsLeft.setAttribute('class', 'left_right');
  let tagsRight = document.createElement("section");
  tagsRight.setAttribute('class', 'left_right');

  let all_tag=document.createElement("div");
  all_tag.setAttribute('class','input-display');
  let all_tag_input = document.createElement("input");
  all_tag_input.setAttribute('type',"checkbox");
  all_tag_input.setAttribute('id',"checkbox0");
  all_tag_input.setAttribute('name',"allTagsCheckbox");
  all_tag_input.setAttribute('class',"tag_checkbox");
  all_tag_input.setAttribute('value',"All");
  all_tag_input.setAttribute('checked',"");

  all_tag_label=document.createElement("label");
  all_tag_label.setAttribute('for',"checkbox0");
  all_tag_label.setAttribute('class',"tag");
  all_tag_label.setAttribute('onclick',"handleAll()");
  all_tag_label.innerHTML="All";

  all_tag.appendChild(all_tag_input);
  all_tag.appendChild(all_tag_label);
  tagsLeft.appendChild(all_tag);
  dataTags.forEach(tag => {

    let one_tag=document.createElement("div");
    one_tag.setAttribute('class','input-display');
    let one_tag_input = document.createElement("input");
    one_tag_input.setAttribute('type',"checkbox");
    one_tag_input.setAttribute('id',"checkbox"+tag['idTag']);
    one_tag_input.setAttribute('name',"navTagsCheckbox");
    one_tag_input.setAttribute('class',"tag_checkbox");
    one_tag_input.setAttribute('value',tag['nomTag']);

    one_tag_label=document.createElement("label");
    one_tag_label.setAttribute('for',"checkbox"+tag['idTag']);
    one_tag_label.setAttribute('class',"tag");
    one_tag_label.setAttribute('onclick',"checkedButAll()");
    one_tag_label.innerHTML = tag['nomTag'];

    one_tag.appendChild(one_tag_input);
    one_tag.appendChild(one_tag_label);
    if (count<middle-1){
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
  // var data = JSON.parse(dataTypes);
  var count=0; // Je met un compteur pour savoir à quel moment je passe dans le second block
  var lenght = Object.keys(dataTypes).length ; // Je récupère la taille du json
  var middle = lenght/2;
  let authorBlock = document.getElementById("author");
  let authorLeft = document.createElement("section");
  authorLeft.setAttribute('class', 'left_right');
  let authorRight = document.createElement("section");
  authorRight.setAttribute('class', 'left_right');

  let all_author = document.createElement("div");
  all_author.setAttribute('class','input_display');
  let all_author_input = document.createElement("input");
  all_author_input.setAttribute('type',"checkbox");
  all_author_input.setAttribute('id',"authorCheckbox0");
  all_author_input.setAttribute('name',"allAuthorCheckbox");
  all_author_input.setAttribute('class',"tag_checkbox","author");
  all_author_input.setAttribute('value',"All");
  all_author_input.setAttribute('checked',"");

  let all_author_label=document.createElement("label");
  all_author_label.setAttribute('for',"authorCheckbox0");
  all_author_label.setAttribute('class',"tag");
  all_author_label.setAttribute('onclick',"handleAllAuthor()");
  all_author_label.innerHTML="All";

  all_author.appendChild(all_author_input);
  all_author.appendChild(all_author_label);
  authorLeft.appendChild(all_author);

  dataTypes.forEach(author => {

    let one_author = document.createElement("div");
    one_author.setAttribute('class','input_display');

    let one_author_input= document.createElement("input");

    one_author_input.setAttribute('type',"checkbox");
    one_author_input.setAttribute('id',"authorCheckbox"+author['idTypeAuteur']);
    one_author_input.setAttribute('name',"navAuthorCheckbox");
    one_author_input.setAttribute('class',"tag_checkbox","author");
    one_author_input.setAttribute('value',author['nomTypeAuteur']);
    one_author_input.setAttribute('onclick',"checkedButAllAuthor()");

    one_author_label=document.createElement("label");
    one_author_label.setAttribute('for',"authorCheckbox"+author['idTypeAuteur']);
    one_author_label.setAttribute('class',"tag");
    one_author_label.innerHTML= author['nomTypeAuteur'];

    one_author.appendChild(one_author_input);
    one_author.appendChild(one_author_label);

    if (count<middle-1){
      authorLeft.appendChild(one_author);
    } else {
      authorRight.appendChild(one_author);
    }
    count = count+1;
  });

  // On met le tout dans le block principal
  authorBlock.appendChild(authorLeft);
  authorBlock.appendChild(authorRight);
}


//////////////////////////////////////////////////////////////////
///////////// FONCTION AFFICHE TYPES AUTEUR POP UP ///////////// - FAIT
function displayTypesAuteurPop(dataTypes){

  let authorFormBlock = document.getElementById("type_auteur_form");

  dataTypes.forEach(author => {
    let one_author = document.createElement("option");
    one_author.setAttribute('value',author['idTypeAuteur']);
    one_author.innerHTML = author['nomTypeAuteur'];

    authorFormBlock.appendChild(one_author);
  });
}


//////////////////////////////////////////////////////////////////
/////////////////// FONCTION AFFICHE TAGS POP UP /////////////// - FAIT
function displayTagsPop(dataTags){
  // var data = JSON.parse(dataTags);

  let tagsFormBlock = document.getElementById("tag_form");


  dataTags.forEach(tag => {

    let one_tag_input=document.createElement("input");
    one_tag_input.setAttribute('type','checkbox');
    one_tag_input.setAttribute('id',"popup_checkbox"+tag['idTag']);
    one_tag_input.setAttribute('class',"tag_checkbox");
    one_tag_input.setAttribute('name',"tag_form");
    one_tag_input.setAttribute('value',tag['idTag']);

    let one_tag_label = document.createElement("label");
    one_tag_label.setAttribute('for',"popup_checkbox"+tag['idTag']);
    one_tag_label.setAttribute('class',"tag");
    one_tag_label.innerHTML = tag['nomTag'];

    tagsFormBlock.appendChild(one_tag_input);
    tagsFormBlock.appendChild(one_tag_label);
  });
}


//////////////////////////////////////////////////////////////////
/////////////////// FONCTION AFFICHE TYPES SIGNAL ///////////////
function displayTypesSignal(dataTypes){
  // var data = JSON.parse(dataTypes);

  var typesFormBlock = document.getElementById("type_signalement");

  dataTypes.forEach(type => {
    let one_type=document.createElement("option");
    one_type.setAttribute('value',type['nomTypeSignalement']);
    one_type.innerHTML=type['nomTypeSignalement'];

    typesFormBlock.appendChild(one_type);
  });
}

//////////////////////////////////////////////////////////////////
//////////////////// GESTION DES CHECKED /////////////////// - FAIT

function handleAll() {
  if(!document.getElementById("checkbox0").checked){
    var items = document.getElementsByName('navTagsCheckbox');
    for (var i = 0; i < items.length; i++) {
      if (items[i].type == 'checkbox'){
      items[i].checked = false;
    }
    }
  }
}

function checkedButAll(){
  all = document.getElementById("checkbox0");
  if(all.checked){
  all.checked = false;
  }
}

function handleAllAuthor() {
  if(!document.getElementById('authorCheckbox0').checked){
    var items = document.getElementsByName('navAuthorCheckbox');
    for (var i = 0; i < items.length; i++) {
      if (items[i].type == 'checkbox')
      items[i].checked = false;
    }
  }
}

function checkedButAllAuthor(){
  all = document.getElementById('authorCheckbox0');
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
  button=event.currentTarget;
  let divId=button.getAttribute("idcitation"); // On récupère l'id
  var currentLikes;
  var newValue;

  let formDataGet = new FormData();
  let dataGet = new Object();
  dataGet['url'] = './citations/GetLikes';
  formDataGet.append("url", './citations/GetLikes');
  dataGet['idCitation'] = divId;
  formDataGet.append("idCitation", divId);

  dataGet = JSON.stringify(dataGet);
  formDataGet.append('getData',dataGet);

  /// On get la valeur actuelle des likes ///
  fetch('./api/Route/routeur.php',  {
		method: "POST",
		body: formDataGet})
  .then(res => res.json())
  .then(function(data){
    currentLikes = Number(data['likesCitation']);

    if(button.classList.contains('clicked')){
            if (Number(currentLikes-1)>0){
          newValue=Number(currentLikes-1);
          } else { newValue=Number(0);
              }
      button.classList.remove("clicked");
    } else{
      newValue=Number(currentLikes+1);
      button.classList.add("clicked");
    }

    let likeDiv = button.parentNode.children[1];
    likeDiv.innerHTML = newValue;
    /// On update les likes ///
    var formDataUpd = new FormData();
    var dataUpd = new Object();
    dataUpd['likes'] = newValue;
    formDataUpd.append("likes", newValue);
    dataUpd['idCitation'] = divId;
    formDataUpd.append("idCitation", divId);
    dataUpd['url'] = "./citations/UpdateLikes";
    formDataUpd.append("url", "./citations/UpdateLikes");

    dataUpd = JSON.stringify(dataUpd);
    formDataUpd.append('getData',dataUpd);

      fetch('./api/Route/routeur.php', {
    		method: "POST",
    		body: formDataUpd})
      .then(res => res.json())
      .catch( error => {
        window.alert(error);
      })
  })
  .catch( error => {
    window.alert(error);
  })
}


//////////////////////////////////////////////////////////////////
///////////////// CRITERES DE RECHERCHE - CAS ////////////////

document.getElementById('valid_search').onclick = event => {
	event.preventDefault();
        keywordForm = document.getElementById("inputKeyword").value;
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

        data['keyword'] = keywordForm;
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
          .then(res => res.json())
            .then(function(data){
             displayCitation(data);
           })
            .catch( error => {
              window.alert(error);
            })
  }

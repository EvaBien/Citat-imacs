import '../Route/routeur.php'

//
// var monElement = document.querySelector('.oJSON').getAttribute('data-edimestre');
// var monJSON = JSON.parse(monElement); // Ici le miracle sans jQuery!
// alert(monJSON.nom); // Retourne : Oznog
// alert(monJSON.fonction); // Retourne : Analyste-programmeur

/****************************************************/
////////////////////// APPELS API ////////////////////
/****************************************************/

//////////////////// ALL CITATIONS /////////////////// - FAIT
function AllTags(){
  let url = './tags/All'; // Mon url
  var request = {
      url :  url,
      method: 'GET'
  }

  fetch(chooseRoute(request))
    .then(displayTagsNav(data))
    .then(displayTagsPop(data))
    .catch(error => { console.log(error) });
  }

//////////////////// ALL CITATIONS /////////////////// - FAIT

function AllTypesAuteur(){
  let url = './typesAuteur/All'; // Mon url
  var request = {
      url :  url,
      method: 'GET'
  }

  fetch(chooseRoute(request))
    .then(displayTypesAuteurNav(data))
    .then(displayTypesAuteurPop(data))
    .catch(error => { console.log(error) });
  }

//////////////////// ALL CITATIONS /////////////////// - FAIT
function AllCitations(){
  let url = './citations/All'; // Mon url
  var request = {
      url :  url,
      method: 'GET'
  }

  fetch(chooseRoute(request))
    .then(displayCitation(data))
    .catch(error => { console.log(error) });
  }


  /*******************************************************/
  /////////////////// GESTION EVENEMENTS /////////////////
  /******************************************************/

  //////////////////// AU CHARGEMENT /////////////////// - FAIT

  document.ready( () => {
    AllCitations();
    AllTags();
    AllTypesAuteur();
  });

//////////////////////////////////////////////////////////////////
  //////////////// FONCTION AFFICHE CITATIONS //////////////// - FAIT

  function displayCitation(dataCitation){
    var data = JSON.parse(dataCitation);
    data.forEarch(citation => {
      let block = document.getElementById("block_citations");
      let section_block = "<section class=\'one_citation\' idCitation=\"".data.idCitation."\"></section>"
      let info_block = "<div class=\'infos-citation\'></div>";
      let commands_block="div class=\'commands-citation\'></div>"

      //// Remplissage premier DIV : info_block ////

      // On remplit les tags //
      let tags_block = "<ul class=\'list-tags\'></ul>";
      citation.tags.forEarch(tag=>{
        let tagnom=tag.nomTag;
        let one_tag = "<li>".tagnom."</li>"; // On ajoute les tags
        tags_block.appendChild(one_tag);
      });

      // On remplit la date //
      let date = citation.dateCitation;
      let date_block = "<p class=\'quote_date\'>".date."</p>";

      // On remplit la citation //
      let contenu = citation.contenuCitation;
      let quote_block = "<p class=\'quote\'>".contenu."</p>";

      // On remplit l'auteur + Le type //
      let auteur = citation.auteurCitation;
      let typeauteur = citation.typeAuteur.nomTypeAuteur;
      let author_block = "<p class=\'quote_author\'>".auteur."-\- ".typeauteur."</p>";

      // On met tout dans le div  info_block
      info_block.appendChild(tags_block);
      info_block.appendChild(date_block);
      info_block.appendChild(quote_block);
      info_block.appendChild(author_block);

      //// Remplissage second DIV : commands_block ////

      // On remplit bouton like //
      let liker_block ="<button class=\'like-button\' onclick=\"likeCitation()\">J\'aime</button>";

      // On remplit le nombre de like //
      let nblikes = citation.likesCitation;
      let likes_block = "<p class=\'number_likes\'>".nblikes."</p>";

      // On remplit le bouton signalement //
      let signal_block =  "<a onclick=\"signalPopUp()\">signaler un problème</a>"

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
      let one_tag = "<div class=\"input_display\"> <input type=\"checkbox\" id=\"checkbox".tag['idTag']." \" name=\"navTagsCheckbox\" class=\"tag_checkbox\" value=\"".tag['nomTag']."\" checked> <label for = \"checkbox".tag['idTag']."\" class=\"tag\" onclick=\"checkedButAll()\">".tag['nomTag']."</label></div>"

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
    let one_author = "<div class=\"input_display\"> <input type=\"checkbox\" id=\"authorCheckbox".author['idTypeAuteur']." \" name=\"navAuthorCheckbox\" class=\"tag_checkbox\" value=\"".author['nomTypeAuteur']."\" checked onclick=\"checkedButAllAuthor()\"> <label for = \"authorCheckbox".author['idTypeAuteur']."\" class=\"tag\">".author['nomTypeAuteur']."</label></div>"

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
    let one_author = "<option value=\"".author['nomTypeAuteur']." \">".author['nomTypeAuteur']."</option>"

    authorFormBlock.appendChild(one_author);
  });
}


//////////////////////////////////////////////////////////////////
  /////////////////// FONCTION AFFICHE TAGS POP UP /////////////// - FAIT
  function displayTagsPop(dataTags){
    var data = JSON.parse(dataTags);

    let tagsFormBlock = document.getElementById("tag_form");

    data.forEarch(tag => {
      let one_tag = "<input type=\"checkbox\" id=\"popup_checkbox".tag['idTag']."\" class=\"tag_checkbox\" value=\"".tag['nomTag']."\"><label for=\"popup_checkbox".tag['idTag']."\" class=\"tag\">".tag['nomTag']."</label>"

      tagsFormBlock.appendChild(one_tag);
    });
  }

//////////////////////////////////////////////////////////////////
  //////////////////// GESTION DES CHECKED /////////////////// - FAIT

  function handleAll() {
    /*C'est le statut avant qu'on clique qui est pris en compte*/
    if(!document.getElementById("checkbox0").checked){
      var items = document.getElementsByName('navTagsCheckbox');
          for (var i = 1; i < items.length; i++) {
              if (items[i].type == 'checkbox')
                  items[i].checked = false;
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

        /////////// VALID NEW CITATION ////////
      function callAddCitation(){
        // Appel API Create
      }

      /////////// LIKES CITATIONS //////// - FAIT
      function likeCitation(){ // A modifier ?
        button = event.target;
        divId=button.parentElement.parentElement.getAttribut(idCitation); // On récupère l'id
        currentLikes = getCitationLikes(divId);
        divId = button.parentNode.parentNode.getAttribut(id);
        if(button.classList.contains('clicked')){
          updateCitationLikes(divId, currentLikes-1); //On unlike, changer dans la BDD
          button.classList.remove("clicked");
        }else{
          updateCitationLikes(divId, currentLikes+1);//On like
          button.classList.add("clicked");
        }
        numberLikes = getCitationLikes(divId);
        likeDiv = button.parentNode.children[1];
        likeDiv.innerHTML = numberLikes;
      }


  //////////////////////////////////////////////////////////////////
    ///////////////// CRITERES DE RECHERCHE - CAS ////////////////

    document.getElementById('valid_search').onclick = event => {
    	event.preventDefault();
            keywordForm = document.getElementById("input[name='inputKeyword']").value;
            tagsForm = document.querySelectorAll("input[name='navTagsCheckbox']");
	           let tagsChecked = new Array();
	            tagsForm.forEach(function(radioBtn) {
		              if (radioBtn.checked) {
			                 tagsChecked.push(JSON.stringify(radioBtn.nomTag));
		                   }
	                    });
            typesAuteurForm = document.querySelectorAll("input[name='navAuthorCheckbox']");
	           let typesAuteurChecked = new Array();
	            typesAuteurForm.forEach(function(radioBtn) {
		              if (radioBtn.checked) {
			                 typesAuteurChecked.push(JSON.stringify(radioBtn.nomTag));
		                   }
	                    });

            var data = {
                keyWord : keywordForm,
                tags : tagsChecked,
                typeAuteur : typesAuteurChecked
              }

              var url;

            if (/* Tous les critères keyword + T + TA */){
              url = './citations/Allfactors';
            } else if (/* juste keyword */){
              url = './citations/Keyword';
            } else if (/* juste tags */){
              url = './citations/Tags';
            } else if (/* juste type auteur*/){
              url = './citations/Typesauteur';
            } else if (/* keyword + tags*/){
              url = './citations/TagsKeyword';
            } else if (/* keyword + typeAuteur */){
              url = './citations/TypesauteurKeyword';
            } else if (/* tags + types auteur */){
              url = './citations/TypesauteurTags';
            } else { // Aucun ou que des all
              url = './citations/All'; // Mon url
            }

            var request = {
                url :  url,
                method: 'GET',
                body : data
            }
              fetch(chooseRoute(request))
                .then(displayCitation(data))
                .catch(error => { console.log(error) });
          }

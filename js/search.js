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
    .then(displayAllTags(data))
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
    .then(displayAllTypesAuteur(data))
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
  /////////////////// FONCTION AFFICHE TAGS NAV ///////////////
  function displayAllTags(dataTags){
    var data = JSON.parse(dataTags);
    var dataLeft =;
    var dataRight = ;
    dataLeft.forEarch(tag => {

    });

    dataRight.forEach(tag=>{

    });
  }

  //////////////////////////////////////////////////////////////////
    ///////////// FONCTION AFFICHE TYPES AUTEUR NAV /////////////
function displayAllTypesAuteur(dataTypes){
  var data = JSON.parse(dataTypes);
  data.forEarch(type => {

  });
}

//////////////////////////////////////////////////////////////////
  //////////////////// GESTION DES CHECKED /////////////////// - FAIT

  function handleAll() {
    /*C'est le statut avant qu'on clique qui est pris en compte*/
    if(!document.getElementById("checkbox1").checked){
      var items = document.getElementsByName('navTagsCheckbox');
          for (var i = 1; i < items.length; i++) {
              if (items[i].type == 'checkbox')
                  items[i].checked = false;
          }
    }
  }

  function checkedButAll(){
    if(document.getElementById("checkbox1").checked){
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

      //////////////// POP UP VANISH ///////////
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
        divId = button.parentNode.parentNode.id;
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

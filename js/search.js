
/*document.ready( () => {
  displayAllCitations();
});*/
//
// var monElement = document.querySelector('.oJSON').getAttribute('data-edimestre');
// var monJSON = JSON.parse(monElement); // Ici le miracle sans jQuery!
// alert(monJSON.nom); // Retourne : Oznog
// alert(monJSON.fonction); // Retourne : Analyste-programmeur

/****************************************************/
///////////////// TYPES DE RECHERCHES ///////////////
/****************************************************/

// FONCTION ALL CITATIONS

function displayAllCitations(){

  fetch("./controller/ControllerCitations/getAllCitations") // à corriger si cela ne fonctionne pas
    .then( response => response.json() )
    .then( data => {
      let citations = document.getElementById('list-citations');
      data.forEach( citation => {
        // Afficher élements
      });
    })
    .catch(error => { console.log(error) });
  }





// FONCTION LIKE CITATION

//Pop up


function displayCover(){
  document.getElementById("cover").style.display = "block";
}

function cancelPopUp(){
  document.getElementById("cover").style.display = "none";
  document.getElementById("pop_new_citation").style.display = "none";
  document.getElementById("pop_signal").style.display = "none";
}

// ON CLICK BUTTON ADD CITATION --> pop-up appear

function addCitationPopUp(){
  displayCover();
  document.getElementById("pop_new_citation").style.display = "block";
}

// FONCTION ADD CITATION

//LIKE CITATION
function getCitationLikes(id){
  nb = 3 //Chercher dans la BDD à partir de l'ID
  return nb;
}

function likeCitation(){
  button = event.target;
  divId = button.parentNode.parentNode.id;
  if(button.classList.contains('clicked')){
    //On unlike, changer dans la BDD
    button.classList.remove("clicked");
  }else{
    //On like
    button.classList.add("clicked");
  }
  numberLikes = getCitationLikes(divId);
  likeDiv = button.parentNode.children[1];
   likeDiv.innerHTML = numberLikes;

}

// FONCTION SEND SIGNALEMENT

//ALL CHECKED OR NOT

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

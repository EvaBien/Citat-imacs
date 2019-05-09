
document.ready( () => {
  displayAllCitations();
}

/****************************************************/
///////////////// TYPES DE RECHERCHES ///////////////
/****************************************************/

// FONCTION ALL CITATIONS

function displayAllCitations(){

  fetch("controller/ControllerCitations/getAllCitations") // à corriger si cela ne fonctionne pas
    .then( response => response.json() )
    .then( data => {
      let citations = document.getElementById('list-citations');
      data.forEach( citation => {
        // Afficher élements
      });
    })
    .catch(error => { console.log(error) });
  });

}


// FONCTION LIKE CITATION


// ON CLICK BUTTON ADD CITATION --> pop-up appear


// FONCTION ADD CITATION


// ON CLICK BUTTON SIGNAL --> pop-up appear


// FONCTION SEND SIGNALEMENT

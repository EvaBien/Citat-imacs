
document.ready( () => {
	fetch("controller/api all citations") // à corriger si cela ne fonctionne pas
		.then( response => response.json() )
		.then( data => {
			let citations = document.getElementById('list-citations');
			data.forEach( citation => {
				// Afficher élements
			});
		})
		.catch(error => { console.log(error) });
});


// FONCTION ALL CITATIONS

// FONCTION CITATION BY WORD

// FONCTION CITATION BY TAG

// FONCTION CITATION BY DATE (?)

// FONCTION CITATION BY AUTEUR

// FONCTION CHECK SEARCH

// FONCTION ADD CITATION

// FONCTION LIKE CITATION

// FONCTION BUTTON SIGNAL

// FONCTION SEND SIGNALEMENT

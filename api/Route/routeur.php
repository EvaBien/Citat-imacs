<?php

header("Content-Type: application/json; charset=UTF-8");
require '../controller/ControllerCitations.php';
require '../controller/ControllerSignalements.php';
require '../controller/ControllerTags.php';
require '../controller/ControllerTypesAuteur.php';
require '../controller/ControllerTypesSignalements.php';

$method = $_SERVER['REQUEST_METHOD'];

echo "\n Affiche request : \n \n";
var_dump($request)

if ($method == 'GET'){
  $url = $_GET['url']; // A tester dans le switch case
  $request = $_GET['request'];
} else if ($method == 'POST'){
  $url = $_POST['url']; // A tester dans le switch case
  $request = $_POST['request'];
} else if ($method == 'PUT'){
  $url = $_PUT['url']; // A tester dans le switch case
  $request = $_PUT['request'];
} else {
  $url = $_DELETE['url']; // A tester dans le switch case
  $request = $_DELETE['request'];
}

//https://www.primfx.com/tuto-php-creer-router-479/
//https://www.grafikart.fr/tutoriels/router-628
//
///// TOUS LES URLS POSSIBLES /////
switch ($url) {
  case '' :
  apiGetAllCitations($request); // GET ALL
  break;
  /////CITATIONS//////
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/New' : // Create
  apiCreateCitation($request);
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/All' :
  apiGetAllCitations($request); // GET ALL
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Id' :
  apiGetCitationById($request); // Get By Id
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Tags' :
  apiGetCitationByTags($request); // Get by Tags
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Keyword' :
  apiGetCitationByKeyword($request); // Get by keyword
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Typesauteur' :
  apiGetCitationByTypeAuteur($request); // Get by typesAuteur
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/TagsKeyword' :
  apiGetCitationByTagsAndKeyword($request); // Get by tags & keywords
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/TypesauteurKeyword' :
  apiGetCitationByTypeAuteurAndKeyword($request); // Get by typesAuteur & keyword
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/TypesauteurTags' :
  apiGetCitationByTypeAuteurAndTags($request); // Get by typesAuteur & Tags
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Allfactors' :
  apiGetCitationByAll($request); // Get by typesAut+keyword+tags
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Update' :
  apiUpdateCitation($request); // Update
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/Delete' :
  apiDeleteCitation($request); // Delete
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/GetLikes' :
  getCitationLikes($request); // get citation likes
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/citations/UpdateLikes' :
  updateCitationLikes($request); // Update citation likes
  break;
  ///////////// ALL TAGS, TYPESAUTEURS & TYPESSIGNALEMENTS /////////////
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/tags/All' :
  apiGetAllTags($request); // Get All Tags
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/typesAuteur/All' :
  apiGetAllTypeAuteurs($request); // Get AlL TypesAuteur
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/typesSignalement/All' :
  apiGetAllTypesSignalement($request); // GetAllTypesSignalement
  break;
  //////////////// SIGNALEMENTS /////////
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/signalement/New' :
  apiCreateSignalement($request); // Create Signalement
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/signalement/Id' :
  apiGetSignalementById($request); // Get By id
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/signalement/Update' :
  apiUpdateSignalement($request); // Update signalement
  break;
  case 'http://perso-etudiant.u-pem.fr/~akohlmul/signalement/Send' :
  sendMailSignalement($request); // Send Mail
  break;
  default:
  throwAnError($request);
  break;
}

?>

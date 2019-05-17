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
  case '/~akohlmul/citations/New' : // Create
  apiCreateCitation($request);
  break;
  case '/~akohlmul/citations/All' :
  apiGetAllCitations($request); // GET ALL
  break;
  case '/~akohlmul/citations/Id' :
  apiGetCitationById($request); // Get By Id
  break;
  case '/~akohlmul/citations/Tags' :
  apiGetCitationByTags($request); // Get by Tags
  break;
  case '/~akohlmul/citations/Keyword' :
  apiGetCitationByKeyword($request); // Get by keyword
  break;
  case '/~akohlmul/citations/Typesauteur' :
  apiGetCitationByTypeAuteur($request); // Get by typesAuteur
  break;
  case '/~akohlmul/citations/TagsKeyword' :
  apiGetCitationByTagsAndKeyword($request); // Get by tags & keywords
  break;
  case '/~akohlmul/citations/TypesauteurKeyword' :
  apiGetCitationByTypeAuteurAndKeyword($request); // Get by typesAuteur & keyword
  break;
  case '/~akohlmul/citations/TypesauteurTags' :
  apiGetCitationByTypeAuteurAndTags($request); // Get by typesAuteur & Tags
  break;
  case '/~akohlmul/citations/Allfactors' :
  apiGetCitationByAll($request); // Get by typesAut+keyword+tags
  break;
  case '/~akohlmul/citations/Update' :
  apiUpdateCitation($request); // Update
  break;
  case '/~akohlmul/citations/Delete' :
  apiDeleteCitation($request); // Delete
  break;
  case '/~akohlmul/citations/GetLikes' :
  getCitationLikes($request); // get citation likes
  break;
  case '/~akohlmul/citations/UpdateLikes' :
  updateCitationLikes($request); // Update citation likes
  break;
  ///////////// ALL TAGS, TYPESAUTEURS & TYPESSIGNALEMENTS /////////////
  case '/~akohlmul/tags/All' :
  apiGetAllTags($request); // Get All Tags
  break;
  case '/~akohlmul/typesAuteur/All' :
  apiGetAllTypeAuteurs($request); // Get AlL TypesAuteur
  break;
  case '/~akohlmul/typesSignalement/All' :
  apiGetAllTypesSignalement($request); // GetAllTypesSignalement
  break;
  //////////////// SIGNALEMENTS /////////
  case '/~akohlmul/signalement/New' :
  apiCreateSignalement($request); // Create Signalement
  break;
  case '/~akohlmul/signalement/Id' :
  apiGetSignalementById($request); // Get By id
  break;
  case '/~akohlmul/signalement/Update' :
  apiUpdateSignalement($request); // Update signalement
  break;
  case '/~akohlmul/signalement/Send' :
  sendMailSignalement($request); // Send Mail
  break;
  default:
  throwAnError($request);
  break;
}

?>

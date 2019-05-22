<?php

header("Content-Type: application/json; charset=UTF-8");
require '../controller/ControllerCitations.php';
require '../controller/ControllerSignalements.php';
require '../controller/ControllerTags.php';
require '../controller/ControllerTypesAuteur.php';
require '../controller/ControllerTypesSignalements.php';

$method = $_SERVER['REQUEST_METHOD'];

$url='';
if ($method == 'GET'){
  $url = $_GET['url'];
  $request = $_GET['getData'];
} else if ($method == 'POST'){
  $url = $_POST["url"];
  $request = $_POST['getData'];
} else if ($method == 'PUT'){
  $url = $_PUT['url'];
  $request = $_PUT['getData'];
} else {
  $url = $_DELETE['url'];
  $request = $_DELETE['getData'];
}

///// TOUS LES URLS POSSIBLES /////
switch ($url) {
  /////CITATIONS//////
  case './citations/New' : // Create
  apiCreateCitation($request);
  break;
  case './citations/All' :
  apiGetAllCitations($request); // GET ALL
  break;
  case './citations/Id' :
  apiGetCitationById($request); // Get By Id
  break;
  case './citations/Tags' :
  apiGetCitationByTags($request); // Get by Tags
  break;
  case './citations/Keyword' :
  apiGetCitationByKeyword($request); // Get by keyword
  break;
  case './citations/Typesauteur' :
  apiGetCitationByTypeAuteur($request); // Get by typesAuteur
  break;
  case './citations/TagsKeyword' :
  apiGetCitationByTagsAndKeyword($request); // Get by tags & keywords
  break;
  case './citations/TypesauteurKeyword' :
  apiGetCitationByTypeAuteurAndKeyword($request); // Get by typesAuteur & keyword
  break;
  case './citations/TypesauteurTags' :
  apiGetCitationByTypeAuteurAndTags($request); // Get by typesAuteur & Tags
  break;
  case './citations/Allfactors' :
  apiGetCitationByAll($request); // Get by typesAut+keyword+tags
  break;
  case './citations/Update' :
  apiUpdateCitation($request); // Update
  break;
  case './citations/Delete' :
  apiDeleteCitation($request); // Delete
  break;
  case './citations/GetLikes' :
  getCitationLikes($request); // get citation likes
  break;
  case './citations/UpdateLikes' :
  updateCitationLikes($request); // Update citation likes
  break;
  ///////////// ALL TAGS, TYPESAUTEURS & TYPESSIGNALEMENTS /////////////
  case './tags/All' :
  apiGetAllTags($request); // Get All Tags
  break;
  case './typesAuteur/All' :
  apiGetAllTypeAuteurs($request); // Get AlL TypesAuteur
  break;
  case './typesSignalement/All' :
  apiGetAllTypesSignalement($request); // GetAllTypesSignalement
  break;
  //////////////// SIGNALEMENTS /////////
  case './signalement/New' :
  apiCreateSignalement($id); // Create Signalement
  break;
  case './signalement/Id' :
  apiGetSignalementById($request); // Get By id
  break;
  case './signalement/Update' :
  apiUpdateSignalement($request); // Update signalement
  break;
  // case './signalement/Send' :
  // sendMailSignalement($request); // Send Mail
  // break;
  default:
  echo "\n ERROR ROUTEUR - Try another route ! \n";
  break;
}
 // Verifier pour signalementById

?>

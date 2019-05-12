<?php

function chooseRoute(HttpRequest $request){
header("Content-Type: application/json; charset=UTF-8");
require '../controller/ControllerCitations.php';
require '../controller/ControllerSignalements.php';
require '../controller/ControllerTags.php';
require '../controller/ControllerTypesAuteur.php';
require '../controller/ControllerTypesSignalements.php';

echo "\n Affiche request : \n \n";
var_dump($request)
// $action=$_GET['action'];

$url = $_GET['url']; // ?? OU RECUPERER LA REQUETE
$query=$_GET['request'];

//https://www.primfx.com/tuto-php-creer-router-479/

//https://www.grafikart.fr/tutoriels/router-628
//
///// TOUS LES URLS POSSIBLES /////
  switch ($url) {
  case '' : // Create
  apiGetAllCitations($query); // GET ALL
      break;
    /////CITATIONS//////
    case '/citations/New' : // Create
      apiCreateCitation($query);
        break;
    case '/citations/All' :
      apiGetAllCitations($query); // GET ALL
        break;
    case '/citations/Id' :
      apiGetCitationById($query); // Get By Id
        break;
    case '/citations/Tags' :
      apiGetCitationByTags($query); // Get by Tags
        break;
    case '/citations/Keyword' :
      apiGetCitationByKeyword($query); // Get by keyword
        break;
    case '/citations/Typesauteur' :
      apiGetCitationByTypeAuteur($query); // Get by typesAuteur
        break;
    case '/citations/Allfactors' :
      apiGetCitationByAll($query); // Get by typesAut+keyword+tags
        break;
    case '/citations/TagsKeyword' :
      apiGetCitationByTagsAndKeyword($query); // Get by tags & keywords
        break;
    case '/citations/TypesauteurKeyword' :
      apiGetCitationByTypeAuteurAndKeyword($query); // Get by typesAuteur & keyword
        break;
    case '/citations/TypesauteurTags' :
      apiGetCitationByTypeAuteurAndTags($query); // Get by typesAuteur & Tags
        break;
    case '/citations/Update' :
      apiUpdateCitation($query); // Update
        break;
    case '/citations/Delete' :
      apiDeleteCitation($query); // Delete
        break;
    case '/citations/GetLikes' :
      getCitationLikes($query); // get citation likes
        break;
    case '/citations/UpdateLikes' :
      updateCitationLikes($query); // Update citation likes
        break;
        ///////////// ALL TAGS, TYPESAUTEURS & TYPESSIGNALEMENTS /////////////
    case '/tags/All' :
      apiGetAllTags($query); // Get All Tags
            break;
    case '/typesAuteur/All' :
      apiGetAllTypeAuteurs($query); // Get AlL TypesAuteur
            break;
    case '/typesSignalement/All' :
      apiGetAllTypesSignalement($query); // GetAllTypesSignalement
            break;
        //////////////// SIGNALEMENTS /////////
    case '/signalement/New' :
      apiCreateSignalement($query); // Create Signalement
            break;
    case '/signalement/Id' :
      apiGetSignalementById($query); // Get By id
            break;
    case '/signalement/Update' :
      apiUpdateSignalement($query); // Update signalement
            break;
    case '/signalement/Send' :
        sendMailSignalement($query); // Send Mail
            break;
    default:
      throwAnError($query);
        break;
}

}

 ?>

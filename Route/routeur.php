<?php
header("Content-Type: application/json; charset=UTF-8");
require '../controller/ControllerCitations.php';
require '../controller/ControllerSignalements.php';
require '../controller/ControllerTags.php';
require '../controller/ControllerTypesAuteur.php';

$request = $_SERVER['REDIRECT_URL'];

// $action=$_GET['action'];

// $request = '';
// if(isset($_GET['url'])) {
//     $url = $_GET['url'];
// }



//https://www.primfx.com/tuto-php-creer-router-479/

//https://www.grafikart.fr/tutoriels/router-628
//
//
  switch ($request) {
    /////CITATIONS//////
    case '/' : // Create
      apiCreateCitation($request);
        break;
    case '' :
      apiGetAllCitations($request); // GET ALL
        break;
    case '/' :
      apiGetCitationById($request); // Get By Id
        break;
    case '/' :
      apiGetCitationByTags($request); // Get by Tags
        break;
    case '/' :
      apiGetCitationByKeyword($request); // Get by keyword
        break;
    case '/' :
      apiGetCitationByTypeAuteur($request); // Get by typesAuteur
        break;
    case '/' :
      apiGetCitationByAll($request); // Get by typesAut+keyword+tags
        break;
    case '/' :
      apiGetCitationByTagsAndKeyword($request); // Get by tags & keywords
        break;
    case '/' :
      apiGetCitationByTypeAuteurAndKeyword($request); // Get by typesAuteur & keyword
        break;
    case '/' :
      apiGetCitationByTypeAuteurAndTags($request); // Get by typesAuteur & Tags
        break;
    case '/' :
      apiUpdateCitation($request); // Update
        break;
    case '/' :
      apiDeleteCitation($request); // Delete
        break;
    case '/' :
      getCitationLikes($request); // get citation likes
        break;
    case '/' :
      updateCitationLikes($request); // Update citation likes
        break;
        ///////////// ALL TAGS AND AUTEURS /////////////
    case '/' :
      apiGetAllTags($request); // Get All Tags
            break;
    case '/' :
      apiGetAllTypeAuteurs($request); // Get AlL TypesAuteur
            break;
        //////////////// SIGNALEMENTS /////////
    case '/' :
      apiCreateSignalement($request); // Create Signalement
            break;
    case '/' :
      apiGetSignalementById($request); // Get By id
            break;
    case '/' :
      apiUpdateSignalement($request); // Update signalement
            break;
    case '/' :
        sendMailSignalement($request); // Send Mail
            break;
    default:
      throwAnError($request);
        break;
}


 ?>

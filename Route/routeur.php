<?php
header("Content-Type: application/json; charset=UTF-8");
require '../controller/ControllerCitations.php';
require '../controller/ControllerSignalements.php';
require '../controller/ControllerTags.php';
require '../controller/ControllerTypesAuteur.php';

$request = $_SERVER['REDIRECT_URL'];

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'MONACTION') {
        AppelFonction();
    } // Si on sait à l'avance



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
      apiGetCitationByTagsAndKeyword($request); // Get by typesAuteur
        break;
    case '/' :
      apiGetCitationByTypeAuteurAndKeyword($request); // Get by typesAuteur
        break;
    case '/' :
      apiGetCitationByTypeAuteurAndTags($request); // Get by typesAuteur
        break;
    case '/' :
      apiUpdateCitation($request); // Get by typesAuteur
        break;
    case '/' :
      apiDeleteCitation($request); // Get by typesAuteur
        break;
    case '/' :
      getCitationLikes($request); // Get by typesAuteur
        break;
    case '/' :
      updateCitationLikes($request); // Get by typesAuteur
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
      apiCreateSignalement($request); // Get AlL TypesAuteur
            break;
    case '/' :
      apiGetSignalementById($request); // Get AlL TypesAuteur
            break;
    case '/' :
      apiUpdateSignalement($request); // Get AlL TypesAuteur
            break;
    case '/' :
        sendMailSignalement($request); // Get AlL TypesAuteur
            break;
    default:
      throwAnError($request);
        break;
}


 ?>

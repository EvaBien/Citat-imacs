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
    }

  switch ($request) {
    case '/' :
    //    
        break;
    case '' :
        require __DIR__ . '/views/index.php';
        break;
    case '/about' :
        require __DIR__ . '/views/about.php';
        break;
    default:
        require __DIR__ . '/views/404.php';
        break;
}


 ?>

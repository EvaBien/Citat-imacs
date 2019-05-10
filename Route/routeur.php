<?php
header("Content-Type: application/json; charset=UTF-8");
require '../controller/ControllerCitations.php';
require '../controller/ControllerSignalements.php';
require '../controller/ControllerTags.php';
require '../controller/ControllerTypesAuteur.php';


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'MONACTION') {
        AppelFonction();
    }


 ?>

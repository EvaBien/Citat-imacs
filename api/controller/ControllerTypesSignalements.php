<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../model/ModelCitations.php';
require_once '../model/ModelSignalements.php';
require_once '../model/ModelTypesSignalement.php';

////////////////////// GET ALL TYPES SIGNALEMENT ///////////////////
function apiGetAllTypesSignalement()($query){

  // response status
  http_response_code(200);

  ////SEARCH TYPE SIGNALEMENT IN DB ////
  $typesSignalement = array();
  $queryStmt = "SELECT * FROM s2_typessignalement;";
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();

  while (($row = $stmt->fetch()) !== false) {
    array_push($typesSignalement, $row); // Ajoute chaque citation au tableau citations
  }
  echo json_encode($typesSignalement);

  exit();
}



////////////////////////////////////////////////////////////////
///////////////////////////// ERROR //////////////////////////
//////////////////////////////////////////////////////////////

function throwAnErrorTypesSignal()
 {
   echo json_encode("An error occured.");
   http_response_code(500);
   exit();
 }


?>

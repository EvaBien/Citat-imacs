<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../model/ModelTypesAuteur.php';

////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


///////////////////////// GET ALL TYPES AUTEUR //////////////////////
// Pour mettre les champs dynamiquement dans search
function apiGetAllTypeAuteurs($query){


  ////SEARCH TYPE AUTEUR IN DB ////
  $typeAuteur = array();
  $queryStmt = "SELECT * FROM S2_TypesAuteur;";
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();

  while (($row = $stmt->fetch()) !== false) {
    array_push($typeAuteur, $row);
  }
  echo json_encode($typeAuteur);
  exit();
}



////////////////////////////////////////////////////////////////
///////////////////////////// ERROR //////////////////////////
//////////////////////////////////////////////////////////////

function throwAnErrorTypesAuteur()
 {
   echo json_encode("An error occured.");
   http_response_code(500);
   exit();
 }


?>

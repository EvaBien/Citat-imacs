<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelSignalements.php';
require '../model/ModelTypesSignalement.php';

////////////////////// GET ALL TYPES SIGNALEMENT ///////////////////
public function apiGetAllTypeSignalement(HttpRequest $query){
// Pour récupérer dynamiquement
// check HTTP method //
  $method = strtolower($_SERVER['REQUEST_METHOD']);

  if ($method !== 'get') {
  	http_response_code(405);
  	echo json_encode(array('message' => 'This method is not allowed.'));
  	exit();
  }
  // response status
  http_response_code(200);

  ////SEARCH TYPE SIGNALEMENT IN DB ////
  $typeSignalement = array();
  $queryStmt = "SELECT * FROM S2_TypesSignalement;"
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();
  exit();
}



////////////////////////////////////////////////////////////////
///////////////////////////// ERROR //////////////////////////
//////////////////////////////////////////////////////////////

public static function throwAnError()
 {
   echo json_encode("An error occured.");
   http_response_code(500);
   exit();
 }


?>
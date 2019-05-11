<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelTypesAuteur.php';

////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


///////////////////////// GET ALL TYPES AUTEUR //////////////////////
// Pour mettre les champs dynamiquement dans search
public function apiGetAllTypeAuteurs(HTTPRequest $request){
   // check HTTP method //
  $method = strtolower($_SERVER['REQUEST_METHOD']);

  if ($method !== 'get') {
  	http_response_code(405);
  	echo json_encode(array('message' => 'This method is not allowed.'));
  	exit();
  }
  // response status
  http_response_code(200);

  ////SEARCH TYPE AUTEUR IN DB ////
  $typeAuteur = array();
  $queryStmt = "SELECT * FROM S2_TypesAuteur;"
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

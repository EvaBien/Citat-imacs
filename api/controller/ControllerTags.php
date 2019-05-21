<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../model/ModelTags.php';

////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


///////////////////////// GET ALL TAGS //////////////////////

function apiGetAllTags($query){
  // response status
  http_response_code(200);

  ////SEARCH TAGS IN DB ////
  $tags = array();
  $queryStmt = "SELECT * FROM S2_Tags;";
  $stmt = MyPDO::getInstance()->prepare($queryStmt);
  $stmt->execute();

  while (($row = $stmt->fetch()) !== false) {
  	array_push($tags, $row);
  }
  echo json_encode($tags);


  exit();
}



////////////////////////////////////////////////////////////////
///////////////////////////// ERROR //////////////////////////
//////////////////////////////////////////////////////////////

function throwAnErrorTags()
 {
   echo json_encode("An error occured.");
   http_response_code(500);
   exit();
 }


?>

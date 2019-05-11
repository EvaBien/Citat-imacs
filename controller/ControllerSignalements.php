<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelSignalements.php';
require '../model/ModelTypesSignalement.php';

////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


public function apiCreateSignalement(HTTPRequest $query)
{
  ////// VERIF/////
  
  // check HTTP method //
  $method = strtolower($_SERVER['REQUEST_METHOD']); // Je verifie si c'est bien un get
  if ($method !== 'post') {
      http_response_code(405);
      echo json_encode(array('message' => 'This method is not allowed.'));
      exit(); // Sinon je sors
  }

  // Creation du nouvel objet//
  $signalement = new signalement($query['typeSignalement'],$query['messageSignalement'],$query['statutSignalement'],$query['idCitation']);

  ////// ADD TO DB //////
  $queryStmt = "INSERT INTO signalement (typeSignalement, messageSignalement, statutSignalement, idCitation) VALUES (?, ?, ?, ?);"

  $stmt = MyPDO::getInstance()->prepare($queryStmt);

  $stmt->bindValue(1, $signalement->getTypeSignalement());
  $stmt->bindValue(2, $signalement->getMessageSignalement());
  $stmt->bindValue(3, $signalement->getStatutSignalement());
  $stmt->bindValue(4, $signalement->getIdCitation());

  $queryStatus = $stmt->execute();

  if ($queryStatus === false) {
    self::throwAnError();
  } else {
    $signalement->id = MyPDO::getInstance()->lastInsertId();
  }
}


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// READ ////////////////////////////
  //////////////////////////////////////////////////////////////


  ////////////////////// GET SIGNALEMENT BY ID ///////////////////

  public function apiGetSignalementById(HttpRequest $query){
    // Penser à recupérer le signalement et la citation associée.
  }

  ////////////////////// GET ALL TYPES SIGNALEMENT ///////////////////
  public function apiGetAllTypeSignalement(HttpRequest $query){
 // Pour récupérer dynamiquement
  }


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// UPDATE //////////////////////////
  //////////////////////////////////////////////////////////////

  public static function apiUpdateSignalement(HTTPRequest $query)
    {
      // Sert uniquement à update le statut
    }



    ////////////////////////////////////////////////////////////////
    ///////////////////////////// OTHER //////////////////////////
    //////////////////////////////////////////////////////////////

    public static function sendMailSignalement(HTTPRequest $query){
      // A appeler quand on a créé le signalement
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

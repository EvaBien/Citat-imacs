<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelSignalements.php';
require '../model/ModelTypesSignalement.php';

////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


public function apiCreateSignalement(HTTPRequest $request)
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
  $citation = new Citation($query['contenuCitation'],$query['dateCitation'],$query['auteurCitation'],$query['idTypeAuteur']);

  // ($typeSignalement, $messageSignalement, $statutSignalement, $idCitation)

  ////// ADD TO DB //////

  }


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// READ ////////////////////////////
  //////////////////////////////////////////////////////////////


  ////////////////////// GET SIGNALEMENT BY ID ///////////////////

  public function apiGetSignalementById(HttpRequest $request){
    // Penser à recupérer le signalement et la citation associée.
  }

  ////////////////////// GET ALL TYPES SIGNALEMENT ///////////////////
  public function apiGetAllTypeSignalement(HttpRequest $request){
 // Pour récupérer dynamiquement
  }


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// UPDATE //////////////////////////
  //////////////////////////////////////////////////////////////

  public static function apiUpdateSignalement(HTTPRequest $request)
    {
      // Sert uniquement à update le statut
    }



    ////////////////////////////////////////////////////////////////
    ///////////////////////////// OTHER //////////////////////////
    //////////////////////////////////////////////////////////////

    public static function sendMailSignalement(HTTPRequest $request){
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

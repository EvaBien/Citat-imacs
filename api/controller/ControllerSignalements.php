<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../model/ModelCitations.php';
require_once '../model/ModelSignalements.php';
require_once '../model/ModelTypesSignalement.php';

////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


function apiCreateSignalement($query)
{
  // Creation du nouvel objet//
  $signalement = new signalement($query['typeSignalement'],$query['messageSignalement'],$query['statutSignalement'],$query['idCitation']);

  ////// ADD TO DB //////
  $queryStmt = "INSERT INTO signalement (typeSignalement, messageSignalement, statutSignalement, idCitation) VALUES (?, ?, ?, ?);";

  $stmt = MyPDO::getInstance()->prepare($queryStmt);

  $stmt->bindValue(1, $signalement->getTypeSignalement());
  $stmt->bindValue(2, $signalement->getMessageSignalement());
  $stmt->bindValue(3, $signalement->getStatutSignalement());
  $stmt->bindValue(4, $signalement->getIdCitation());

  $queryStatus = $stmt->execute();

  if ($queryStatus === false) {
    throwAnErrorSignal();
  } else {
    $signalement->id = MyPDO::getInstance()->lastInsertId();
    sendMailSignalement($query, $signalement->id);

  }
}


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// READ ////////////////////////////
  //////////////////////////////////////////////////////////////


  ////////////////////// GET SIGNALEMENT BY ID ///////////////////
  function apiGetSignalementById($id){

    $queryStmt = "SELECT * FROM S2_Signalements WHERE S2_Signalements.idSignalement = :idsignalement LIMIT 1;";

    $signalement = array();
    $stmt = MyPDO::getInstance()->prepare($queryStmt);

    // $stmt->execute(['idsignalement' => $query['idSignalement']]);
    $stmt->execute(['idsignalement' => $id]);

    while (($row = $stmt->fetch()) !== false) {
      array_push($signalements, $row);
    }

    foreach ($signalements as $signalement) { // On va chercher les tags et le typeAuteur
    $signalement='';
    $citation = '';

    $stmt = MyPDO::getInstance()->prepare(<<<SQL
      SELECT S2_TypesAuteur.nomTypeAuteur FROM `S2_TypesAuteur`
      INNER JOIN S2_Citations ON S2_Citations.idTypeAuteur = S2_TypesAuteur.idTypeAuteur
      WHERE S2_Citations.idCitation = :idcitation;
SQL
    );
    $stmt->execute(['idcitation'=>$citation['idCitation']]);
    while (($row = $stmt->fetch()) !== false) {
      $typeAuteur=$row['nomTypeAuteur'];
    }

    $signalements[$key]['citation'] = $citation;
      }
      echo json_encode($signalements);
      exit();
  }


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// UPDATE //////////////////////////
  //////////////////////////////////////////////////////////////

function apiUpdateSignalement($query){
      // Sert uniquement à update le statut

    }


    ////////////////////////////////////////////////////////////////
    ///////////////////////////// OTHER //////////////////////////
    //////////////////////////////////////////////////////////////

function sendMailSignalement($query, $idSignalement){
      // A appeler quand on a créé le signalement -- Tu peux le faire si tu cherche sur internet

      $to      = "citatimacs@gmail.com";
      $subject = 'Une citation a été signalée - '.$query['typeSignal'];
      $content = $query['message'];
      $content = "
              <html>
              <head>
              <title>".$to."</title>
              </head>
              <body>
              <p>".$message."</p>

              <a href=\"./Route/route.php?url=./admin/signalement/Id&id=".$idSignalement."\">Cliquez ici pour corriger la citation</a>
              </body>
              </html>
              ";
      $headers = 'From:'.$query['mail']. "\r\n" .
          'Content-type:text/html;charset=UTF-8' . "\r\n".
          'X-Mailer: PHP/' . phpversion();

      mail($to, $subject, $content, $headers);

    }


    ////////////////////////////////////////////////////////////////
    ///////////////////////////// ERROR //////////////////////////
    //////////////////////////////////////////////////////////////

function throwAnErrorSignal()
     {
       echo json_encode("An error occured.");
       http_response_code(500);
       exit();
     }


  ?>

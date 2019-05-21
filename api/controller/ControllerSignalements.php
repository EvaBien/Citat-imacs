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
  $queryStmt = "INSERT INTO s2_signalements (typeSignalement, messageSignalement, statutSignalement, idCitation) VALUES (?, ?, ?, ?);";

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

    $queryStmt = "SELECT * FROM s2_signalements WHERE s2_signalements.idSignalement = :idsignalement LIMIT 1;";

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
      SELECT s2_TypesAuteur.nomTypeAuteur FROM `s2_TypesAuteur`
      INNER JOIN s2_Citations ON s2_Citations.idTypeAuteur = s2_TypesAuteur.idTypeAuteur
      WHERE s2_Citations.idCitation = :idcitation;
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
    $queryStmt = "UPDATE s2_signalements SET statutSignalement = 1 WHERE idSignal = :id;";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(
      array(
        ':id'=>$query["idSignal"]
      )
    );

    if ($stmt->rowCount() == 0) {
      return NULL;
    }
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

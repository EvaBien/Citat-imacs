<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../model/ModelCitations.php';
require_once '../model/ModelSignalements.php';
require_once '../model/ModelTypesSignalement.php';

////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////


function apiCreateSignalement($req)
{

  $query=json_decode($req,true);
  // Creation du nouvel objet//

  $signalement = new signalement($query['typeSignal'],$query['messageSignal'],0,$query['idCitationSignal']);
  ////// ADD TO DB //////
  $queryStmt = "INSERT INTO s2_signalements (idCitation, idTypeSignalement, messageSignalement, statutSignalement) VALUES (?, ?, ?, ?);";

  $stmt = MyPDO::getInstance()->prepare($queryStmt);

  $stmt->bindValue(1, $signalement->getIdCitation());
  $stmt->bindValue(2, $signalement->getTypeSignalement());
  $stmt->bindValue(3, $signalement->getMessageSignalement());
  $stmt->bindValue(4, $signalement->getStatutSignalement());

  $queryStatus = $stmt->execute();

  if ($queryStatus === false) {
    throwAnErrorSignal();
  } else {
    $signalement->setIdSignalement(MyPDO::getInstance()->lastInsertId());
    sendMailSignalement($query, $signalement->getIdSignalement());

  }

  echo json_encode("Signalement créé");
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

      $citation = '';

      $stmt2 = MyPDO::getInstance()->prepare(<<<SQL
        SELECT s2_TypesAuteur.nomTypeAuteur FROM `s2_TypesAuteur`
        INNER JOIN s2_Citations ON s2_Citations.idTypeAuteur = s2_TypesAuteur.idTypeAuteur
        WHERE s2_Citations.idCitation = :idcitation;
SQL
      );
      $stmt2->execute(['idcitation'=>$row['idCitation']]);

      while (($row2 = $stmt2->fetch()) !== false) {
        $row['citation'] = $row2;
      }
      array_push($signalement, $row);
    }
  echo json_encode($signalements);
  exit();
}


  ////////////////////////////////////////////////////////////////
  ///////////////////////////// UPDATE //////////////////////////
  //////////////////////////////////////////////////////////////

  function apiUpdateSignalement($req){
    // Sert uniquement à update le statut
    $query=json_decode($req, true);
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
    echo json_encode("Statut du signalement mis à jour ! ");
  }


    ////////////////////////////////////////////////////////////////
    ///////////////////////////// OTHER //////////////////////////
    //////////////////////////////////////////////////////////////

function sendMailSignalement($query, $idSignalement){
      // A appeler quand on a créé le signalement -- Tu peux le faire si tu cherche sur internet

      $to      = "citatimacs@gmail.com";
      $subject = 'Une citation a été signalée - '.$query['typeSignal'];
      $content = "
              <html>
              <head>
              <title>".$to."</title>
              </head>
              <body>
              <p>".$query['messageSignal']."</p>

              <a href=\"./Route/route.php?url=./admin/signalement/Id&id=".$idSignalement."\">Cliquez ici pour corriger la citation</a>
              </body>
              </html>
              ";
      $headers = 'From:'.$query['mailSignal']. "\r\n" .
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

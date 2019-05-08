<?php
header("Content-Type: application/json; charset=UTF-8");
require '../model/ModelCitations.php';
require '../model/ModelTagCitation.php';
require '../model/ModelTypesAuteur.php';
require '../model/ModelTypesAuteur.php';



////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////

public function apiCreateCitation(HTTPRequest $request)
  {
    /////////////// CREATE OBJECT ////////////////


    //////////////////// ADD TO DB //////////////
    $stmt = MyPDO::getInstance()->prepare("INSERT INTO Citations (contenu, date, auteur, typeAuteur) VALUES (?, ?, ?, ?);");
    $queryStatus = $stmt->execute(
      array(
        $this->contenu,
        $this->date,
        $this->auteur,
        $this->typeAuteur
      )
    );
    $this->id = MyPDO::getInstance()->lastInsertId();
}

// protected function add(News $news)
//  {
//    $requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');
//
//    $requete->bindValue(':titre', $news->titre());
//    $requete->bindValue(':auteur', $news->auteur());
//    $requete->bindValue(':contenu', $news->contenu());
//
//    $requete->execute();
//  }

////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


// GET ALL CITATIONS //
public function apiGetAllCitations(HTTPRequest $request){
  $citations[]= '';


}

// GET CITATION BY ID //

// GET CITATION BY TAGS //

// GET CITATION BY KEYWORD //

// GET CITATION BY AUTOR //

// CLEAN REQUEST ? //

////////////////////////////////////////////////////////////////
///////////////////////////// UPDATE //////////////////////////
//////////////////////////////////////////////////////////////

public static function apiUpdateCitation(HTTPRequest $request)
  {
    $queryStmt = "UPDATE Casts SET firstname = :name WHERE id = :id";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(
      array(
        ':name' => $fnameCast,
        ':id' => $id
      )
    );

    if ($stmt->rowCount() == 0) {
      return NULL;
    }
  }


////////////////////////////////////////////////////////////////
///////////////////////////// DELETE //////////////////////////
//////////////////////////////////////////////////////////////


public static function apiDeleteCitation(HTTPRequest $request)
  {
    $queryStmt = "DELETE FROM Casts WHERE id = ?";

    $stmt = MyPDO::getInstance()->prepare($queryStmt);
    $stmt->execute(
      array(
        $id
      )
    );

    if ($stmt->rowCount() == 0) {
      return NULL;
    }
  }





?>

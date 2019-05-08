<?php
require '../model/ModelCitations.php';


////////////////////////////////////////////////////////////////
///////////////////////////// CREATE //////////////////////////
//////////////////////////////////////////////////////////////

public function createCitation()
  {
    /////////////// CREATE OBJECT ////////////////


    //////////////////// ADD TO DB //////////////
    $stmt = MyPDO::getInstance()->prepare("INSERT INTO Casts (firstname, lastname, birth_year, death_year) VALUES (?, ?, ?, ?);");
    $queryStatus = $stmt->execute(
      array(
        $this->_firstname,
        $this->_lastname,
        $this->_birthDate,
        $this->_deathDate
      )
    );

    if ($queryStatus === false) {
      //TODO throwAnError
    }
    else {
      $this->_idCast = MyPDO::getInstance()->lastInsertId();
    }
  }

////////////////////////////////////////////////////////////////
///////////////////////////// READ ////////////////////////////
//////////////////////////////////////////////////////////////


// GET ALL CITATIONS //


// GET CITATION BY ID //

// GET CITATION BY TAGS //

// GET CITATION BY KEYWORD //

// GET CITATION BY AUTOR //

// CLEAN REQUEST ? //

////////////////////////////////////////////////////////////////
///////////////////////////// UPDATE //////////////////////////
//////////////////////////////////////////////////////////////

public static function updateCitation($id, $vvv)
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


public static function deleteCitation($id)
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

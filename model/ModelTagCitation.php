<?php
require 'MyPDO.citatimac.include.php';

class tagCitation {
    private $idCitation;
    private $idTag;
    public function __construct($idTag, $idCitation){
                                  // Id auto
      $this->idTag=$idTag; //Lier à la table Tags
      $this->idCitation=$idCitation; // Lier à la table Citation
    }
}

///////////////////////FUNCTIONS GETTER////////////////////////

  function getIdCitation(){

  }

  function getIdTag(){

  }

  ///////////////////////FUNCTIONS SETTER///////////////////////
  
  function setIdCitation(){

  }

  function setIdTag(){

  }

?>

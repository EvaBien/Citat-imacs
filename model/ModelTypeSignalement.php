<?php
require 'MyPDO.citatimac.include.php';

class typeSignalement {
    private $id;
    private $typeSignalement;
    public function __construct($typeSignalement, $messageSignalement, $statutSignalement, $idCitation){
                                  // Id auto
      $this->typeSignalement=$typeSignalement;
      $this->messageSignalement=$messageSignalement ; // A récupérer dans ce qu'écrit l'utilisateur ?
      $this->statutSignalement=$statutSignalement;
      $this->idCitation=$idCitation; // Lier à la table Citation
    }
}

///////////////////////FUNCTIONS GETTER////////////////////////

  function getSignalement(){

  }

  ///////////////////////FUNCTIONS SETTER///////////////////////
  
    function setSignalement(){
    }

?>

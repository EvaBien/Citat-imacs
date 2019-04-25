<?php
require 'MyPDO.citatimac.include.php';

class signalement {
    private $id;
    private $typeSignalement;
    private $messageSignalement;
    private $statutSignalement;
    private $idCitation;
    public function __construct($typeSignalement, $messageSignalement, $statutSignalement, $idCitation){
                                  // Id auto
      $this->typeSignalement=$typeSignalement;
      $this->messageSignalement=$messageSignalement ; // A récupérer dans ce qu'écrit l'utilisateur ?
      $this->statutSignalement=$statutSignalement;
      $this->idCitation=$idCitation; // Lier à la table Citation
    }


///////////////////////FUNCTIONS GETTER////////////////////////

  function getSignalement(){

  }

  function getIdSignalement(){

  }

  function getIdCitation(){

  }

  function getTypeSignalement(){

  }
  
  function getMessageSignalement(){

  }

  function getStatutSignalement(){

  }

  ///////////////////////FUNCTIONS SETTER///////////////////////
  
    function setSignalement(){
    }
    function setIdSignalement(){
    }
    function setIdCitation(){
    }
    function setTypeSignalement(){
    }
    function setMessageSignalement(){
    }
    function setStatutSignalement(){
    }
}


?>

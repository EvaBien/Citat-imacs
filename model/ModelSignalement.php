<?php
require 'MyPDO.citatimac.include.php';
date_default_timezone_set('UTC');

class signalement {
    private $id;
    private $typeSignalement;
    private $messageSignalement;
    private $statutSignalement;
    public function __construct($typeSignalement, $messageSignalement, $statutSignalement){
                                  // Id auto
      $this->typeSignalement=$typeSignalement;
      $this->getMessageSignalement=new ; // A récupérer dans ce qu'écrit l'utilisateur ?
      $this->statutSignalement=$statutSignalement;
      $this->idCitation=$idCitation; // Lier à la table Citation
    }
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



?>

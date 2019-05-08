<?php
require 'MyPDO.citatimac.include.php';
require 'ModelTypeSignalement.php';
require 'ModelCitation.php';


class signalement {
    private $id;
    private $idTypeSignalement;
    private $messageSignalement;
    private $statutSignalement;
    private $idCitation;
    public function __construct($typeSignalement, $messageSignalement, $statutSignalement, $idCitation){
                                  // Id auto
      $this->idTypeSignalement=$typeSignalement;
      $this->messageSignalement=$messageSignalement ; // A récupérer dans ce qu'écrit l'utilisateur ?
      $this->statutSignalement=$statutSignalement;
      $this->Citation=$idCitation; // Lier à la table Citation
    }


///////////////////////FUNCTIONS GETTER////////////////////////

  function getSignalement(){
    $formatted = array(
      'idSignal'=> $this->id,
      'nomTypeSignal'=> $this->idTypeSignalement, // id ou getTypeSignal by id ?
      'message'=> $this->messageSignalement,
      'statut'=> $this->statutSignalement,
      'citation'=> $this->idCitation; // id ou getCitation by id ?
    );

    return json_encode($formatted);
  }

  function getIdSignalement(){
    return $this->id;
  }

  function getTypeSignalement(){
    return $this->îdTypeSignalement;
  }

  function getMessageSignalement(){
    return $this->messageSignalement;
  }

  function getStatutSignalement(){
    return  $this->statutSignalement;
  }

  function getIdCitation(){
    return $this->idCitation;
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
